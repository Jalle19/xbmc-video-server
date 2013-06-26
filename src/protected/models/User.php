<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $role
 * @property string $username
 * @property string $password
 */
class User extends CActiveRecord
{

	const ROLE_ADMIN = 'admin';
	const ROLE_USER = 'user';
	const ROLE_NONE = '';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('role, username, password', 'required'),
			array('username', 'unique'),
			array('role', 'in', 'range'=>array_keys($this->getRoles())),
			array('role', 'validateRole', 'on'=>'update'),
		);
	}
	
	/**
	 * Checks that there is at least one administrator configured
	 * @param string $attribute
	 */
	public function validateRole($attribute)
	{
		$role = $this->{$attribute};

		if ($role != self::ROLE_ADMIN)
		{
			$administrators = $this->findAll('role = :role AND id != :id', array(
				':role'=>self::ROLE_ADMIN,
				':id'=>$this->id));
			
			if (count($administrators) === 0)
				$this->addError($attribute, 'There must be at least one administrator');
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'role'=>'Role',
			'roleName'=>'Role', // used in the manage view
			'username'=>'Username',
			'password'=>'Password',
		);
	}

	/**
	 * Returns the possible roles
	 * @return array
	 */
	public function getRoles()
	{
		return array(
			self::ROLE_ADMIN=>'Administrator',
			self::ROLE_USER=>'User',
		);
	}
	
	/**
	 * Returns the name of the user's role
	 * @return string
	 */
	public function getRoleName()
	{
		$roles = $this->getRoles();

		return $roles[$this->role];
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

}

