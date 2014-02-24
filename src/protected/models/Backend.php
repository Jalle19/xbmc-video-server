<?php

/**
 * This is the model class for table "backend".
 *
 * The followings are the available columns in table 'backends':
 * @property int $id
 * @property string $name
 * @property string $hostname
 * @property int $port
 * @property string $username
 * @property string $password
 * @property string $proxyLocation
 * @property int $default
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Backend extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Backend the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'backend';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, hostname, port, username, password', 'required'),
			array('default', 'requireDefaultBackend'),
			array('default', 'numerical', 'integerOnly'=>true),
			array('port', 'numerical', 'integerOnly'=>true, 'max'=>65535),
			array('proxyLocation', 'safe'),
			// the following rules depend on each other so they must come in this order
			array('hostname', 'checkConnectivity'),
			array('hostname', 'checkServerType'),
			array('username', 'checkCredentials'),
		);
	}
	
	/**
	 * @return array the scopes for this model
	 */
	public function scopes()
	{
		return array(
			'default'=>array(
				'condition'=>'`default` = 1',
			)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Backend name',
			'hostname'=>'Hostname',
			'port'=>'Port',
			'username'=>'Username',
			'password'=>'Password',
			'proxyLocation'=>'Proxy location',
			'default'=>'Set as default',
		);
	}
	
	/**
	 * Checks that there is actually something listening on the specified 
	 * hostname and port.
	 * @param string $attribute the attribute being validated ("hostname" in 
	 * this case)
	 */
	public function checkConnectivity($attribute)
	{
		if (!$this->isAttributesValid(array('hostname', 'port')))
			return;

		if (!$this->isConnectable())
			$this->addError($attribute, "Unable to connect to $this->hostname:$this->port, make sure XBMC is running and has its web server enabled");
	}

	/**
	 * Checks that the credentials entered are valid. We do this by requesting 
	 * "/" on the server and checking the HTTP status code.
	 * @param string $attribute the attribute being validated ("username" in 
	 * this case)
	 */
	public function checkCredentials($attribute)
	{
		if (!$this->isAttributesValid(array('hostname', 'port', 'username', 'password')))
			return;

		// Create a HTTP client and a GET request
		$httpClient = new Zend\Http\Client();
		$httpClient->setAuth($this->username, $this->password);
		$httpRequest = new \Zend\Http\Request();
		$httpRequest->setUri('http://'.$this->hostname.':'.$this->port.'/');

		// Perform the request
		try
		{
			$httpResponse = $httpClient->dispatch($httpRequest);

			if ($httpResponse->getStatusCode() === 401)
				throw new Exception('Invalid credentials');
		}
		catch (\Exception $e)
		{
			unset($e); // avoid IDE warnings

			$this->addError($attribute, 'Invalid credentials');
		}
	}
	
	/**
	 * Checks that the server running on hostname:port is actually XBMC and not 
	 * some other software. We do this by looking at the authentication realm 
	 * string.
	 * @param string $attribute the attribute being validated ("username" in 
	 * this case)
	 */
	public function checkServerType($attribute)
	{
		if (!$this->isAttributesValid(array('hostname', 'port')))
			return;

		// Create a HTTP client and a GET request
		$httpClient = new Zend\Http\Client();
		$httpRequest = new \Zend\Http\Request();
		$httpRequest->setUri('http://'.$this->hostname.':'.$this->port.'/');

		// Perform the request (we deliberately don't catch Zend exceptions 
		// since we can't recover from them anyway)
		try
		{
			$httpResponse = $httpClient->dispatch($httpRequest);

			// We expect there to be WWW-Authenticate header with the realm "XBMC"
			$requiresAuthentication = false;
			
			foreach ($httpResponse->getHeaders() as $header)
			{
				if ($header instanceof Zend\Http\Header\WWWAuthenticate)
				{
					$requiresAuthentication = true;

					if (!preg_match('/xbmc/i', $header->value))
						throw new InvalidRealmException($header->value);
				}
			}

			if (!$requiresAuthentication)
				throw new AuthenticationLessServerException();
		}
		catch (AuthenticationLessServerException $e)
		{
			$this->addError($attribute, 'The server does not ask for authentication');
		}
		catch (InvalidRealmException $e)
		{
			$message = "The server at $this->hostname:$this->port doesn't seem to be an XBMC instance";

			Yii::log($message.' (the server identified as "'.$e->getMessage().'")', CLogger::LEVEL_ERROR, 'Backend');
			$this->addError($attribute, $message);
		}
	}

	/**
	 * Checks that there is another backend set as default if the default 
	 * checkbox is unchecked for this one
	 * @param string $attribute the attribute being validated
	 */
	public function requireDefaultBackend($attribute)
	{
		if (!$this->{$attribute})
		{
			// If this backend is currently the default one it must remain so
			if (!$this->isNewRecord)
			{
				$model = $this->findByPk($this->id);

				if ($model->default)
					$this->addError($attribute, 'There must be a default backend');
			}

			// If there are no other backends then this must be the default one
			if (count(Backend::model()->findAll()) === 0)
				$this->addError($attribute, 'There must be a default backend');
		}
	}
	
	/**
	 * Makes sure that no other backend is set as default if this one is
	 */
	protected function afterSave()
	{
		if ($this->default)
		{
			Yii::app()->db->createCommand()->update($this->tableName(), 
					array('default'=>0), 'id != :id', array(':id'=>$this->id));
		}

		parent::afterSave();
	}
	
	/**
	 * @return boolean whether this backend is connectable
	 */
	public function isConnectable()
	{
		$errno = 0;
		$errStr = '';

		// The default timeout is 1 minute, reduce that to 5 seconds
		//
		if (@fsockopen($this->hostname, $this->port, $errno, $errStr, 5) === false || $errno !== 0)
		{
			Yii::log('Failed to connect to '.$this->hostname.':'.$this->port.'. The exact error was: '.$errStr.' ('.$errno.')', CLogger::LEVEL_ERROR, 'Backend');
			return false;
		}

		return true;
	}

	/**
	 * Returns a data provider for this model
	 * @return \CActiveDataProvider
	 */
	public function getDataProvider()
	{
		return new CActiveDataProvider(__CLASS__, array(
			'pagination'=>false
		));
	}
	
	/**
	 * Checks whether any of the specified attributes have errors and returns 
	 * true if they don't
	 * @param array $attributes the attributes to check
	 * @return boolean whether any of the attributes have errors
	 */
	private function isAttributesValid($attributes)
	{
		foreach ($attributes as $attribute)
			if ($this->hasErrors($attribute))
				return false;

		return true;
	}

}
