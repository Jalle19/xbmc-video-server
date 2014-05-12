<?php

/**
 * Handles application settings
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class SettingController extends AdminOnlyController
{

	/**
	 * Displays a form with all settings and allows the user to save or reset 
	 * them.
	 */
	public function actionAdmin()
	{
		$settings = Setting::model()->findAll();
		$definitions = Setting::model()->getDefinitions();
		
		// Sort the settings according to their defined order
		usort($settings, function($a, $b) use($definitions)
		{
			return $definitions[$a->name]['order'] > 
				   $definitions[$b->name]['order'];
		});

		if (isset($_POST['Setting']))
		{
			$allValid = true;

			// Validate all settings
			foreach ($settings as $setting)
			{
				$value = $_POST['Setting'][$setting->name];
				
				// Update the application language and reset any user 
				// language preferences if the default is changed
				if ($setting->name === 'language' && $setting->value !== $value)
				{
					User::model()->updateAll(array(
						'language'=>null));

					Yii::app()->languageManager->setCurrent($value);
				}

				$setting->{$setting->name} = $value;
				$setting->value = $value;

				if (!$setting->validate(array($setting->name)))
					$allValid = false;
				
				// Flush the API call cache whenever its option is disabled
				if ($setting->name == 'cacheApiCalls' && !$value)
					Yii::app()->apiCallCache->flush();
			}

			// Only if all are valid we save them and refresh the page
			if ($allValid)
			{
				foreach ($settings as $setting)
					$setting->save(false);

				$this->log('"%s" updated the application settings', Yii::app()->user->name);
				Yii::app()->user->setFlash('success', Yii::t('Settings', 'Settings updated successfully'));
				$this->refresh();
			}
		}

		$this->render('admin', array(
			'settings'=>$settings,
			'definitions'=>$definitions,
		));
	}

	/**
	 * Resets all settings to their default values and redirects to the admin 
	 * page
	 */
	public function actionReset()
	{
		foreach (Setting::model()->getDefinitions() as $name=> $definition)
			$this->saveSetting($name, $definition['default']);

		Yii::app()->user->setFlash('success', Yii::t('Settings', 'Settings have been reset to defaults'));

		$this->redirect(array('setting/admin'));
	}

	/**
	 * Saves the specified setting
	 * @param string $name the setting name
	 * @param string $value the setting value
	 */
	private function saveSetting($name, $value)
	{
		$setting = $this->loadModel($name);
		$setting->value = $value;
		$setting->save(false);
	}

	/**
	 * Loads the model for the specified setting
	 * @param string $name the setting name
	 * @return Setting the setting model
	 */
	private function loadModel($name)
	{
		return Setting::model()->findByPk($name);
	}

}