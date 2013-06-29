<?php

/**
 * Represents the data needed to authenticate a user. It also provides the 
 * getId() method which is used to find the current user model through 
 * Yii::app()->user->id
 */
class UserIdentity extends CUserIdentity
{

	/**
	 * @var int the user ID
	 */
	private $_userId;

	/**
	 * Authenticates a user
	 * @return int the error code (ERROR_NONE if authentication succeeded)
	 */
	public function authenticate()
	{
		// Try to match the user based on both the plain-text password and a 
		// hashed varient. The default "admin" user has its password stored in 
		// plaintext so we need to hash it on first login.
		$user = User::model()->findByAttributes(array(
			'username'=>$this->username));

		$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

		if ($user !== null)
		{
			// Password is stored as plain-text
			if ($user->password === $this->password)
			{
				// Re-save the user, that way the password will get hashed
				$user->save();
				$this->errorCode = self::ERROR_NONE;
			}
			elseif (User::checkPassword($this->password, $user->password))
				$this->errorCode = self::ERROR_NONE;

			if ($this->errorCode === self::ERROR_NONE)
				$this->_userId = $user->id;
		}

		return $this->errorCode;
	}

	/**
	 * Returns the user ID
	 * @return int
	 */
	public function getId()
	{
		return $this->_userId;
	}

}