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
		$user = User::model()->findByAttributes(array(
			'username'=>$this->username,
			'password'=>$this->password));

		if ($user !== null)
		{
			$this->_userId = $user->id;
			$this->errorCode = self::ERROR_NONE;
		}
		else
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

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