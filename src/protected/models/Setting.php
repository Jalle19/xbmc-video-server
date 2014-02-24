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

	// We need one attribute per setting
	public $applicationName;
	public $singleFilePlaylist;
	public $showHelpBlocks;
	public $cacheApiCalls;
	public $pagesize;
	public $disableFrodoWarning;
	public $useHttpsForVfsUrls;
	public $whitelist;
	public $ignoreArticle;

	/**
	 * @var array setting definitions
	 */
	public static $definitions = array(
		'applicationName'=>array(
			'label'=>'Application name',
			'type'=>self::TYPE_TEXT,
			'default'=>'XBMC Video Server',
			'order'=>100,
		),
		'singleFilePlaylist'=>array(
			'label'=>"Don't use playlists when item consists of a single file",
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'You may have to right-click and copy the address in order to stream (not download) the file',
			'order'=>200,
		),
		'showHelpBlocks'=>array(
			'label'=>'Show help blocks throughout the site',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'1',
			'order'=>300,
		),
		'cacheApiCalls'=>array(
			'label'=>'Cache all API results',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'Useful on slow hardware. A refresh button will appear in the menu which flushes the cache',
			'order'=>400,
		),
		'pagesize'=>array(
			'label'=>'Amount of results to show per page',
			'type'=>self::TYPE_TEXT,
			'default'=>'60',
			'description'=>'Leave empty to disable pagination altogether',
			'htmlOptions'=>array(
				'span'=>1,
			),
			'order'=>500,
		),
		'ignoreArticle'=>array(
			'label'=>'Ignore article ("the") in results',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'',
			'order'=>550,
		),
		'disableFrodoWarning'=>array(
			'label'=>"Don't warn about XBMC version incompatibility",
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'order'=>600,
		),
		'useHttpsForVfsUrls'=>array(
			'label'=>'Use HTTPS when streaming',
			'type'=>self::TYPE_CHECKBOX,
			'default'=>'0',
			'description'=>'When checked, streaming will be done over HTTPS if 
				the application is accessed over HTTPS. This will usually only 
				work if the server uses a real signed certificate, thus it is 
				not enabled by default.',
			'order'=>700,
		),
		'whitelist'=>array(
			'label'=>'Access whitelist',
			'type'=>self::TYPE_TEXT_WIDE,
			'default'=>'',
			'description'=>"If specified, access is restricted to the defined 
				whitelist. Valid values are IP addresses, IP subnets and 
				domain names (including wildcards). Example: 192.168.1.0/24,1.2.3.4,example.com,*.user.com",
			'order'=>800,
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
			$this->addError($attribute, 'Invalid whitelist definition');
		else
		{
			$whitelist->setDefinitions($definitions);
			if (!$whitelist->check(true))
				Yii::app()->user->setFlash('warning', 'The specified whitelist restrictions will lock you out from this location');
		}
	}

}
