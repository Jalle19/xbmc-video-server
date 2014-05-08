<?php

/**
 * Keeps track of what languages are available and provides functionality for 
 * setting and changing the language
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class LanguageManager extends CApplicationComponent
{

	const SESSION_KEY = 'currentLanguage';

	/**
	 * @var string the current language
	 */
	private $_currentLanguage;

	/**
	 * Initializes the component. The application language is set here. The 
	 * language selected is the one the user has specified, if not the 
	 * configured application language (which defaults to en_us).
	 */
	public function init()
	{
		$applicationLanguage = Setting::getValue('language');
		$this->setCurrent(Yii::app()->session->get(self::SESSION_KEY, $applicationLanguage));
		
		parent::init();
	}

	/**
	 * @return string the current language
	 */
	public function getCurrent()
	{
		return $this->_currentLanguage;
	}

	/**
	 * Sets and changes the current language
	 * @param string $language the new language
	 */
	public function setCurrent($language)
	{
		$this->_currentLanguage = Yii::app()->language = $language;
		Yii::app()->session->add(self::SESSION_KEY, $language);
	}

	/**
	 * Returns a key value map of the available languages, including the source 
	 * language, where the key is the locale and the value is the display name
	 * @return array
	 */
	public static function getAvailableLanguages()
	{
		$locales = array('en_us');
		$translations = new FilesystemIterator(Yii::app()->basePath.'/messages');

		foreach ($translations as $fileInfo)
		{
			// Skip the .gitkeep file
			if ($fileInfo->isDir())
				$locales[] = $fileInfo->getFilename();
		}

		$languages = array();

		foreach ($locales as $language)
			$languages[$language] = CLocale::getInstance('en_us')->getLanguage($language);

		return $languages;
	}

}
