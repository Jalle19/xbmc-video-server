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
	public $showHelpBlocks;
	public $cacheApiCalls;
	public $pagesize;
	public $disableFrodoWarning;
	public $useHttpsForVfsUrls;
	
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
			'label'=>"Don't use playlists when item consists of a single file",
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'You may have to right-click and copy the address in order to stream (not download) the file',
		),
		'showHelpBlocks'=>array(
			'label'=>'Show help blocks throughout the site',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'1',
		),
		'cacheApiCalls'=>array(
			'label'=>'Cache all API results',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'Useful on slow hardware. A refresh button will appear in the menu which flushes the cache',
		),
		'pagesize'=>array(
			'label'=>'Amount of results to show per page',
			'type'=>self::TYPE_TEXT,
			'default'=>'60',
			'description'=>'Leave empty to disable pagination altogether',
			'htmlOptions'=>array(
				'span'=>1,
			),
		),
		'disableFrodoWarning'=>array(
			'label'=>"Don't warn about XBMC version incompatibility",
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
		),
		'useHttpsForVfsUrls'=>array(
			'label'=>"Use HTTPS when streaming",
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'When checked, streaming will be done over HTTPS if 
				the application is accessed over HTTPS. This will usually only 
				work if the server uses a real signed certificate, thus it is 
				not enabled by default.',
		),
	);
	
	/**
	 * @var Setting[] list of all settings and their current values (runtime 
	 * cache)
	 */
	private static $_settings;
	
	/**
	 * Returns the value for the specified setting. All settings are cached for 
	 * the duration of the request since this method can be called quite a lot.
	 * @param string $name the name of the setting
	 * @return mixed the setting value
	 * @throws CHttpException if the specified setting doesn't exist
	 */
	public static function getValue($name)
	{
		if (!isset(self::$definitions[$name]))
			throw new CHttpException(400, "Unknown setting $name");

		if (self::$_settings === null)
			self::$_settings = self::model()->findAll();

		foreach (self::$_settings as $setting)
			if ($setting->name == $name)
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
	
	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array(
			array('applicationName', 'required'),
			array('pagesize', 'numerical', 'integerOnly'=>true, 'min'=>1),
		);
	}
}

