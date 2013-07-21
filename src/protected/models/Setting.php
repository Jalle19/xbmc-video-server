<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property string $name
 * @property string $value
 */
class Setting extends CActiveRecord
{

	// Setting types
	const TYPE_TEXT = 'text';
	const TYPE_CHECKBOX = 'checkbox';

	// We need one attribute per setting
	public $applicationName;
	public $singleFilePlaylist;
	
	/**
	 * @var array setting definitions
	 */
	public static $definitions = array(
		'applicationName'=>array(
			'label'=>'Application name',
			'type'=>self::TYPE_TEXT,
			'default'=>'XBMC Video Server',
		),
		'singleFilePlaylist'=>array(
			'label'=>'Use playlists even when movie consists of a single file',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'You may have to right-click and copy the address in order to stream (not download) the file',
		),
	);
	
	/**
	 * Returns the value for the specified setting
	 * @param string $name the name of the setting
	 * @return mixed the setting value
	 * @throws CHttpException if the specified setting doesn't exist
	 */
	public static function getValue($name)
	{
		if (!isset(self::$definitions[$name]))
			throw new CHttpException(400, "Unknown setting $name");

		$setting = self::model()->findByAttributes(array('name'=>$name));
		return $setting->value;
	}
	
	/**
	 * Populates the model attributes
	 */
	protected function afterFind()
	{
		$this->{$this->name} = $this->value;

		parent::afterFind();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$attributeLabels = array();
		
		foreach(self::$definitions as $setting => $definition)
			$attributeLabels[$setting] = $definition['label'];

		return $attributeLabels;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Settings the static model class
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
		return 'settings';
	}

}

