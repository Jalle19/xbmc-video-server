<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $role
 * @property string $username
 * @property string $password
 * @property string $language
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
use Hautelook\Phpass\PasswordHash;

class User extends CActiveRecord
{

	const ROLE_ADMIN = 'admin';
	const ROLE_USER = 'user';
	const ROLE_SPECTATOR = 'spectator';
	const ROLE_NONE = '';
	
	/**
	 * The base-2 logarithm of the iteration count used for password stretching
	 */
	const PHPASS_ITERATIONS = 8;
	
	/**
	 * @var boolean whether to hash the password before saving the model. 
	 * Defaults to true.
	 */
	private $_hashPasswordBeforeSave = true;

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
			// recommended by phpass
			array('password', 'length', 'max'=>72),
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
				$this->addError($attribute, Yii::t('User', 'There must be at least one administrator'));
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'role'=>Yii::t('User', 'Role'),
			'username'=>Yii::t('User', 'Username'),
			'password'=>Yii::t('User', 'Password'),
		);
	}
	
	/**
	 * Hashes the password before saving the model, unless hashing has been 
	 * inhibited
	 */
	protected function beforeSave()
	{
		if ($this->_hashPasswordBeforeSave)
			$this->password = $this->hashPassword($this->password);

		return parent::beforeSave();
	}

	/**
	 * Inhibits the automatic hashing of the password on save
	 */
	public function inhibitPasswordHash()
	{
		$this->_hashPasswordBeforeSave = false;
	}
	
	/**
	 * @return User the model for the currently logged in user
	 */
	public function findCurrent()
	{
		return $this->findByPk(Yii::app()->user->id);
	}

	/**
	 * Returns the possible roles
	 * @return array
	 */
	public function getRoles()
	{
		return array(
			self::ROLE_ADMIN=>Yii::t('UserRole', 'Administrator'),
			self::ROLE_USER=>Yii::t('UserRole', 'User'),
			self::ROLE_SPECTATOR=>Yii::t('UserRole', 'Spectator'),
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
	
	/**
	 * Returns the hash for the specified password
	 * @param string $password
	 * @return string
	 */
	public static function hashPassword($password)
	{
		return self::getPasswordHasher()->hashPassword($password);
	}

	/**
	 * Checks whether the given password matches the given hash
	 * @param string $password
	 * @param string $hash
	 * @return boolean
	 */
	public static function checkPassword($password, $hash)
	{
		return self::getPasswordHasher()->checkPassword($password, $hash);
	}

	/**
	 * Returns the password hasher
	 * @return \Hautelook\Phpass\PasswordHash
	 */
	private static function getPasswordHasher()
	{
		return new PasswordHash(self::PHPASS_ITERATIONS, false);
	}

}

