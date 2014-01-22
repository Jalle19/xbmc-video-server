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
			array('port, default', 'numerical', 'integerOnly'=>true),
			array('proxyLocation', 'safe'),
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
