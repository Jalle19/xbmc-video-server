<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property string $name
 * @property string $value
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Setting extends CActiveRecord
{

	// Setting types
	const TYPE_TEXT = 'text';
	const TYPE_TEXT_WIDE = 'text-wide';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_CHECKLIST = 'checklist';
	const TYPE_DROPDOWN = 'dropdown';

	// We need one attribute per setting
	public $language;
	public $applicationName;
	public $applicationSubtitle;
	public $singleFilePlaylist;
	public $showHelpBlocks;
	public $cacheApiCalls;
	public $pagesize;
	public $disableFrodoWarning;
	public $useHttpsForVfsUrls;
	public $whitelist;
	public $ignoreArticle;
	public $playlistFormat;
	public $requestTimeout;

	/**
	 * @var Setting[] list of all settings and their current values (runtime 
	 * cache)
	 */
	private static $_settings;
	
	/**
	 * Returns the specified setting's value as a boolean
	 * @param string $setting the setting
	 * @return boolean the value
	 */
	public static function getBoolean($setting)
	{
		return (boolean)self::getValue($setting);
	}

	/**
	 * Returns whether the given option is set for the specified setting
	 * @param string $setting the setting
	 * @param string $option the option
	 * @return whether the option is set or not
	 */
	public static function getBooleanOption($setting, $option)
	{
		$value = self::getValue($setting);
		return is_array($value) && in_array($option, $value);
	}

	/**
	 * Returns the specified setting's value as an integer
	 * @param string $setting the setting
	 * @return int the value
	 */
	public static function getInteger($setting)
	{
		return (int)self::getValue($setting);
	}

	/**
	 * Returns the specified setting's value as a string
	 * @param string $setting the setting
	 * @return string the value
	 */
	public static function getString($setting)
	{
		return (string)self::getValue($setting);
	}

	/**
	 * Returns the value for the specified setting. All settings are cached for 
	 * the duration of the request since this method can be called quite a lot.
	 * @param string $name the name of the setting
	 * @return mixed the setting value
	 * @throws InvalidRequestException if the specified setting doesn't exist
	 */
	private static function getValue($name)
	{
		if (self::$_settings === null)
			self::$_settings = self::model()->findAll();

		foreach (self::$_settings as $setting)
			if ($setting->name == $name)
				return $setting->value;
			
		throw new InvalidRequestException();
	}

	/**
	 * Populates the model attributes
	 */
	protected function afterFind()
	{
		$definitions = $this->getDefinitions();
		if ($definitions[$this->name]['type'] === Setting::TYPE_CHECKLIST)
			$this->value = explode(',', $this->value);

		$this->{$this->name} = $this->value;

		parent::afterFind();
	}

	/**
	 * Prepares the values to be saved
	 */
	protected function beforeSave()
	{
		if (is_array($this->value))
			$this->value = implode(',', $this->value);

		return parent::beforeSave();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$attributeLabels = array();
		$definitions = $this->getDefinitions();

		foreach($definitions as $setting => $definition)
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
			array('language, playlistFormat', 'required'),
			array('pagesize', 'numerical', 'integerOnly'=>true, 'min'=>1),
			array('whitelist', 'validateWhitelist'),
		);
	}

	/**
	 * Validates the "whitelist" attribute. We consider it valid if it is 
	 * empty, if it is not we check if the definitions are legal. Additionally 
	 * we show a warning flash if the definitions would lead to the user being 
	 * locked out.
	 * @param string $attribute the attribute being validated
	 */
	public function validateWhitelist($attribute)
	{
		$definitions = $this->{$attribute};
		if (empty($definitions))
			return;

		$whitelist = Yii::app()->whitelist;
		$definitions = $whitelist->parseDefinitions($definitions);

		if (!$whitelist->validateDefinitions($definitions))
			$this->addError($attribute, Yii::t('Settings', 'Invalid whitelist definition'));
		else
		{
			$whitelist->setDefinitions($definitions);
			if (!$whitelist->check(true))
				Yii::app()->user->setFlash('warning', Yii::t('Settings', 'The specified whitelist restrictions will lock you out from this location'));
		}
	}
	
	/**
	 * Returns the settings definitions
	 * @return array
	 */
	public function getDefinitions()
	{
		return array(
			'language'=>array(
				'label'=>Yii::t('Settings', 'Application language'),
				'separator'=>array(
					'icon'=>'fa fa-pencil-square-o',
					'label'=>Yii::t('Settings', 'Look and feel'),
				),
				'type'=>self::TYPE_DROPDOWN,
				'default'=>'en',
				'description'=>Yii::t('Settings', 'Changing this will reset the default language for all users'),
				'listData'=>LanguageManager::getAvailableLanguages(),
				'order'=>50,
			),
			'applicationName'=>array(
				'label'=>Yii::t('Settings', 'Application name'),
				'type'=>self::TYPE_TEXT,
				'default'=>'XBMC Video Server',
				'order'=>100,
			),
			'applicationSubtitle'=>array(
				'label'=>Yii::t('Settings', 'Application subtitle'),
				'type'=>self::TYPE_TEXT,
				'default'=>Yii::t('Misc', 'Free your library'),
				'order'=>125,
			),
			'playlistFormat'=>array(
				'label'=>Yii::t('Settings', 'Playlist format'),
				'type'=>self::TYPE_DROPDOWN,
				'default'=>'m3u',
				'listData'=>PlaylistFactory::getTypes(),
				'order'=>150,
			),
			'singleFilePlaylist'=>array(
				'label'=>Yii::t('Settings', "Don't use playlists when item consists of a single file"),
				'type'=>self::TYPE_CHECKBOX,
				'default'=>'0',
				'description'=>Yii::t('Settings', 'You may have to right-click and copy the address in order to stream (not download) the file'),
				'order'=>200,
			),
			'showHelpBlocks'=>array(
				'label'=>Yii::t('Settings', 'Show help blocks throughout the site'),
				'type'=>self::TYPE_CHECKBOX,
				'default'=>'1',
				'order'=>300,
			),
			'pagesize'=>array(
				'label'=>Yii::t('Settings', 'Amount of results to show per page'),
				'type'=>self::TYPE_TEXT,
				'default'=>'60',
				'description'=>Yii::t('Settings', 'Leave empty to disable pagination altogether'),
				'htmlOptions'=>array(
					'span'=>1,
				),
				'order'=>500,
			),
			'ignoreArticle'=>array(
				'label'=>Yii::t('Settings', 'Ignore article ("the") in results'),
				'type'=>self::TYPE_CHECKBOX,
				'default'=>'',
				'order'=>550,
			),
			'disableFrodoWarning'=>array(
				'label'=>Yii::t('Settings', "Don't warn about XBMC version incompatibility"),
				'type'=>self::TYPE_CHECKBOX,
				'default'=>'0',
				'order'=>600,
			),
			'requestTimeout'=>array(
				'label'=>Yii::t('Settings', 'Request timeout'),
				'separator'=>array(
					'icon'=>'fa fa-lock',
					'label'=>Yii::t('Settings', 'Security and performance')
				),
				'type'=>self::TYPE_TEXT,
				'default'=>'30',
				'description'=>Yii::t('Settings', 'Determines how long the application should wait for a response from XBMC. Increase this if you get timeout errors.'),
				'htmlOptions'=>array(
					'span'=>1,
				),
				'order'=>625,
			),
			'cacheApiCalls'=>array(
				'label'=>Yii::t('Settings', 'Cache all API results'),
				'type'=>self::TYPE_CHECKBOX,
				'default'=>'0',
				'description'=>Yii::t('Settings', 'Useful on slow hardware. A refresh button will appear in the menu which flushes the cache'),
				'order'=>650,
			),
			'useHttpsForVfsUrls'=>array(
				'label'=>Yii::t('Settings', 'Use HTTPS when streaming'),
				'type'=>self::TYPE_CHECKBOX,
				'default'=>'0',
				'description'=>Yii::t('Settings', 'When checked, streaming will be done over HTTPS if 
					the application is accessed over HTTPS. This will usually only 
					work if the server uses a real signed certificate, thus it is 
					not enabled by default.'),
				'order'=>700,
			),
			'whitelist'=>array(
				'label'=>Yii::t('Settings', 'Access whitelist'),
				'type'=>self::TYPE_TEXT_WIDE,
				'default'=>'',
				'description'=>Yii::t('Settings', "If specified, access is restricted to the defined 
					whitelist. Valid values are IP addresses, IP subnets and 
					domain names (including wildcards). Example: 192.168.1.0/24,1.2.3.4,example.com,*.user.com"),
				'order'=>800,
			),
		);
	}

}
