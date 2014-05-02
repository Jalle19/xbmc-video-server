<?php

/**
 * Form model for the login page. It also calls the actual login function.
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class LoginForm extends CFormModel
{

	/**
	 * @var string the username
	 */
	public $username;

	/**
	 * @var string the password
	 */
	public $password;

	/**
	 * @var boolean whether to remember the login (cookie-based)
	 */
	public $rememberMe;

	/**
	 * @var UserIdentity the user identity
	 */
	private $_identity;

	/**
	 * Returns the validation rules for this model
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('password', 'authenticate'),
			array('username', 'checkWhitelist'),
			array('rememberMe', 'boolean'),
		);
	}

	/**
	 * Returns the attribute labels for this model
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>Yii::t('Login', 'Username'),
			'password'=>Yii::t('Login', 'Password'),
			'rememberMe'=>Yii::t('Login', 'Remember me'),
		);
	}
	
	/**
	 * Checks the client against the whitelist
	 * @param string $attribute the attribute being validated
	 */
	public function checkWhitelist($attribute)
	{
		if (!Yii::app()->whitelist->check())
		{
			// Display the error as a flash since technically it has nothing 
			// to do with the username attribute
			$this->addError($attribute, '');
			Yii::app()->user->setFlash('error', Yii::t('Login', 'Your location is not whitelisted'));
		}
	}

	/**
	 * Validates the password
	 * @param string $attribute
	 */
	public function authenticate($attribute)
	{
		$this->_identity = new UserIdentity($this->username, $this->password);
		$this->_identity->authenticate();

		if ($this->_identity->errorCode !== UserIdentity::ERROR_NONE)
			$this->addError($attribute, Yii::t('Login', 'Incorrect username or password'));
	}

	/**
	 * Logs in the user using the given username and password
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		// _identity should already have been set by authenticate()
		if ($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			// Set duration to 90 days if user checked "remember me"
			$duration = 0;
			if ($this->rememberMe)
				$duration = 60 * 60 * 24 * 90;

			Yii::app()->user->login($this->_identity, $duration);

			return true;
		}
		else
			return false;
	}

}