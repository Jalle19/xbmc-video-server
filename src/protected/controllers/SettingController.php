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
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'admin';
	}

	/**
	 * Displays a form with all settings and allows the user to save or reset 
	 * them.
	 */
	public function actionAdmin()
	{
		$settings = Setting::model()->findAll();

		if (isset($_POST['Setting']))
		{
			foreach ($_POST['Setting'] as $name=> $value)
				$this->saveSetting($name, $value);
			
			Yii::app()->user->setFlash('success', 'Settings updated successfully');
			$this->refresh();
		}

		$this->render('admin', array(
			'settings'=>$settings,
		));
	}

	/**
	 * Resets all settings to their default values and redirects to the admin 
	 * page
	 */
	public function actionReset()
	{
		foreach (Setting::$definitions as $name=> $definition)
			$this->saveSetting($name, $definition['default']);

		Yii::app()->user->setFlash('success', 'Settings have been reset to defaults');

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
		$setting->save();
	}

	/**
	 * Loads the model for the specified setting
	 * @param string $name the setting name
	 * @return Setting the setting model
	 */
	private function loadModel($name)
	{
		return Setting::model()->findByAttributes(array('name'=>$name));
	}

}