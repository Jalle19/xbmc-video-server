<?php

/**
 * Keeps track of what languages are available and which one should be used
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
	 * Initializes the component. The current language is loaded here
	 */
	public function init()
	{
		$this->setCurrent(Yii::app()->session->get(self::SESSION_KEY));

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
	public function getAvailableLanguages()
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
