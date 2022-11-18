<?php

/**
 * This is the model class for table "backend".
 *
 * The followings are the available columns in table 'backends':
 * @property int $id
 * @property string $name
 * @property string $hostname
 * @property int $port
 * @property int $tcp_port
 * @property string $username
 * @property string $password
 * @property string $proxyLocation
 * @property int $default
 * @property string $macAddress
 * @property string $subnetMask
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * @method Backend default() applies the "default" scope
 */
class Backend extends CActiveRecord
{
	
	const DEFAULT_HOSTNAME = 'localhost';
	const DEFAULT_PORT = 8080;
	const DEFAULT_TCP_PORT = 9090;
	const DEFAULT_USERNAME = 'kodi';
	const DEFAULT_PASSWORD = 'kodi';

	/**
	 * Timeout (in seconds) limit while checking if a backend is connectable
	 */
	const SOCKET_TIMEOUT = 5;

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
			array('name, hostname, port, tcp_port, username, password', 'required'),
			array('default', 'requireDefaultBackend'),
			array('default', 'numerical', 'integerOnly'=>true),
			array('port, tcp_port', 'numerical', 'integerOnly'=>true, 'max'=>65535),
			array('proxyLocation', 'safe'),
			// the following rules depend on each other so they must come in this order
			array('hostname', 'checkConnectivity'),
			array('hostname', 'checkServerType'),
			array('username', 'checkCredentials'),
			array('macAddress', 'validateMacAddress'),
			array('subnetMask', 'validateSubnetMask'),
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
			'name'=>Yii::t('Backend', 'Backend name'),
			'hostname'=>Yii::t('Backend', 'Hostname'),
			'port'=>Yii::t('Backend', 'Port'),
			'tcp_port'=>Yii::t('Backend', 'TCP port'),
			'username'=>Yii::t('Backend', 'Username'),
			'password'=>Yii::t('Backend', 'Password'),
			'proxyLocation'=>Yii::t('Backend', 'Proxy location'),
			'default'=>Yii::t('Backend', 'Set as default'),
			'macAddress'=>Yii::t('Backend', 'MAC address'),
			'subnetMask'=>Yii::t('Backend', 'Subnet mask'),
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
		if (!$this->areAttributesValid(array('hostname', 'port')))
			return;

		if (!$this->isConnectable())
			$this->addError($attribute, Yii::t('Backend', 'Unable to connect to {hostname}:{port}, make sure XBMC is running and has its web server enabled', 
					array('{hostname}'=>$this->hostname, '{port}'=>$this->port)));
	}

	/**
	 * Checks that the credentials entered are valid
	 * @param string $attribute the attribute being validated ("username" in 
	 * this case)
	 */
	public function checkCredentials($attribute)
	{
		if (!$this->areAttributesValid(array('hostname', 'port', 'username', 'password')))
			return;

		$webserver = new WebServer($this->hostname, $this->port);

		if (!$webserver->checkCredentials($this->username, $this->password))
			$this->addError($attribute, Yii::t('Backend', 'Invalid credentials'));
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
		if (!$this->areAttributesValid(array('hostname', 'port')))
			return;

		$webserver = new WebServer($this->hostname, $this->port);

		// Check that the server requires authentication
		if (!$webserver->requiresAuthentication())
			$this->addError($attribute, Yii::t('Backend', 'The server does not ask for authentication'));
		else
		{
			// Check the authentication realm
			$realm = $webserver->getAuthenticationRealm();

			if (strtolower($realm) !== 'xbmc' && strtolower($realm) !== 'kodi')
			{
				$message = 'The server at '.$webserver->getHostInfo()." doesn't seem to be an XBMC instance";

				// Log whatever string the server identified as for debugging purposes
				Yii::log($message.' (the server identified as "'.$realm.'")', CLogger::LEVEL_ERROR, 'Backend');
				$this->addError($attribute, $message);
			}
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
			$error = Yii::t('Backend', 'There must be a default backend');
			
			// If this backend is currently the default one it must remain so
			if (!$this->isNewRecord)
			{
				$model = $this->findByPk($this->id);

				if ($model->default)
					$this->addError($attribute, $error);
			}

			// If there are no other backends then this must be the default one
			if (count(Backend::model()->findAll()) === 0)
				$this->addError($attribute, $error);
		}
	}
	
	/**
	 * Validates the MAC address attribute
	 * @param string $attribute the attribute being validated
	 */
	public function validateMacAddress($attribute)
	{
		if (!empty($this->macAddress) && !preg_match('/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i', $this->macAddress))
			$this->addError($attribute, Yii::t('Backend', 'Invalid MAC address'));
	}
	
	/**
	 * Validates the subnet mask attribute
	 * @param string $attribute the attribute being validated
	 */
	public function validateSubnetMask($attribute)
	{
		if (!empty($this->subnetMask) && !filter_var($this->subnetMask, FILTER_VALIDATE_IP))
			$this->addError($attribute, Yii::t('Backend', 'Invalid subnet mask'));
	}

	/**
	 * Formats the MAC address and sets the default subnet mask before saving 
	 * the model
	 * @return boolean whether the save should happen or not
	 */
	protected function beforeSave()
	{
		$this->macAddress = strtolower(str_replace('-', ':', $this->macAddress));
		
		if (!empty($this->macAddress) && empty($this->subnetMask))
			$this->subnetMask = '255.255.255.0';

		return parent::beforeSave();
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
	 * Resets some attributes to their default values
	 */
	public function setDefaultValues()
	{
		$this->hostname = self::DEFAULT_HOSTNAME;
		$this->port = self::DEFAULT_PORT;
		$this->tcp_port = self::DEFAULT_TCP_PORT;
		$this->username = self::DEFAULT_USERNAME;
		$this->password = self::DEFAULT_PASSWORD;
	}

	/**
	 * @return boolean whether this backend is connectable
	 * @param int $port the port to try to connect to. Defaults to null, meaning 
	 * the HTTP port configured for this backend
	 * @param boolean $logFailure whether unsuccessful attempts should be logged. Defaults 
	 * to true.
	 */
	public function isConnectable($port = null, $logFailure = true)
	{
		$errno = 0;
		$errStr = '';
		
		if ($port === null)
			$port = $this->port;
		
		if (@fsockopen(Backend::normalizeAddress($this->hostname), $port, $errno, $errStr, 
				self::SOCKET_TIMEOUT) === false || $errno !== 0)
		{
			if ($logFailure)
				Yii::log('Failed to connect to '.$this->hostname.':'.$this->port.'. The exact error was: '.$errStr.' ('.$errno.')', CLogger::LEVEL_ERROR, 'Backend');
			
			return false;
		}

		return true;
	}
	
	/**
	 * @return boolean whether the backend can be contacted over a WebSocket
	 */
	public function hasWebSocketConnectivity()
	{
		return $this->isConnectable($this->tcp_port, false);
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
	 * Optionally mangles the specified address so it can be used properly, e.g. 
	 * by adding braces around IPv6 addresses.
	 * @param string $address a hostname or IP address
	 * @return string the normalized address
	 */
	public static function normalizeAddress($address)
	{
		// If the address is an IPv6 address we need to wrap it in square brackets
		if (strpos($address, ':') !== false)
			return '[' . $address . ']';
		else
			return $address;
	}
	
	/**
	 * Checks whether any of the specified attributes have errors and returns 
	 * true if they don't
	 * @param string[] $attributes the attributes to check
	 * @return boolean whether any of the attributes have errors
	 */
	private function areAttributesValid($attributes)
	{
		foreach ($attributes as $attribute)
			if ($this->hasErrors($attribute))
				return false;

		return true;
	}

}
