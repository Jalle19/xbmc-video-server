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

	/**
	 * @var string the current language
	 */
	private $_currentLanguage;

	/**
	 * Initializes the component. The application language is set here. The 
	 * language selected is the one the user has specified, if not the 
	 * configured application language (which defaults to en).
	 */
	public function init()
	{
		$userLanguage = $this->getUserLanguage();
		$applicationLanguage = Setting::getString('language');

		if ($userLanguage)
			$this->_currentLanguage = $userLanguage;
		else
			$this->_currentLanguage = $applicationLanguage;

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
	 * Changes the current language
	 * @param string $language the new language
	 */
	public function setCurrent($language)
	{
		$this->_currentLanguage = Yii::app()->language = $language;
	}
	
	/**
	 * Returns the language the current user has specifically chosen, or null 
	 * if no choice has been made
	 * @return string|null the language or null
	 */
	private function getUserLanguage()
	{
		if (!Yii::app()->user->isGuest)
		{
			$user = User::model()->findByPk(Yii::app()->user->id);

			if ($user)
				return $user->language;
		}
		
		return null;
	}

	/**
	 * Returns a key value map of the available languages, including the source 
	 * language, where the key is the locale and the value is the display name
	 * @return array
	 */
	public static function getAvailableLanguages()
	{
		$locales = array('en');
		$translations = new FilesystemIterator(Yii::app()->basePath.'/messages');

		foreach ($translations as $fileInfo)
		{
			// Skip the .gitkeep file
			if ($fileInfo->isDir())
				$locales[] = $fileInfo->getFilename();
		}

		$languages = array();

		foreach ($locales as $language)
			$languages[$language] = CLocale::getInstance('en')->getLanguage($language);

		return $languages;
	}

}
