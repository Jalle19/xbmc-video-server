<?php

/**
 * Pre-filter which checks if the change language modal form has been submitted 
 * and changes the application language accordingly
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ChangeLanguageFilter extends CFilter
{

	/**
	 * Renders the change language form and handles its submission
	 * @param CFilterChain $filterChain
	 * @return boolean whether to continue execution
	 */
	protected function preFilter($filterChain)
	{
		if (isset($_POST['ChangeLanguageForm']))
		{
			$model = new ChangeLanguageForm();
			$model->attributes = $_POST['ChangeLanguageForm'];

			if ($model->validate())
			{
				// Get the display name of the new language
				$languages = Yii::app()->languageManager->getAvailableLanguages();
				$newLanguage = $languages[$model->language];
				
				// Update and inform
				Yii::app()->languageManager->setCurrent($model->language);
				Yii::app()->user->setFlash('success', Yii::t('Language', 'Language changed to {newLanguage}', array('{newLanguage}'=>$newLanguage)));
			}
		}

		return true;
	}

}
