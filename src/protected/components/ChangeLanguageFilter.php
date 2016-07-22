<?php

/**
 * Pre-filter which checks if the user form has been submitted 
 * and changes the application language accordingly
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ChangeLanguageFilter extends CFilter
{

	/**
	 * @inheritdoc
	 */
	protected function preFilter($filterChain)
	{
		if (isset($_POST['User']))
		{
			$previousLanguage = $this->getCurrentUserLanguage();
			$userId = $_POST['User']['id'];
			$newLanguage = $_POST['User']['language'];
			
			if ($previousLanguage !== $newLanguage && Yii::app()->user->id === $userId)
			{
				// Get the display name of the new language
				$languages = LanguageManager::getAvailableLanguages();

				Yii::app()->languageManager->setCurrent($newLanguage);
				Yii::app()->user->setFlash('info', Yii::t('Language', 'Language changed to {newLanguage}', array('{newLanguage}'=>$languages[$newLanguage])));
			}
		}

		return true;
	}


	/**
	 * @return string the user's current language
	 */
	private function getCurrentUserLanguage()
	{
		/* @var User $user */
		$user = User::model()->findByPk(Yii::app()->user->id);
		
		return $user->language;
	}

}
