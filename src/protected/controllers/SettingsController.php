<?php

/**
 * Handles the application settings
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class SettingsController extends Controller
{

	/**
	 * Override parent implementation so we don't get stuck in a redirect loop
	 * @param CFilterChain $filterChain
	 */
	public function filterCheckConfiguration($filterChain)
	{
		$filterChain->run();
	}

	/**
	 * Displays and updates the application settings
	 */
	public function actionIndex()
	{
		$configForm = new ConfigForm();

		if (isset($_POST['ConfigForm']))
		{
			$configForm->attributes = $_POST['ConfigForm'];

			if ($configForm->validate())
			{
				// Update the configuration
				foreach ($configForm->attributes as $attribute=> $value)
				{
					Config::model()->updateByPk($attribute, array(
						'value'=>$value));
				}
				
				// Mark configuration as done
				Config::model()->updateByPk('isConfigured', array(
					'value'=>'true'));
				
				Yii::app()->user->setFlash('success', 'Configuration saved');

				// Refresh to avoid re-submitting the form
				$this->refresh();
			}
		}
		else
		{
			// Populate the form based on the current settings.
			foreach (array_keys($configForm->attributes) as $attribute)
				$configForm->{$attribute} = Yii::app()->config->get($attribute);
		}
		
		$this->render('index', array(
			'model'=>$configForm,
		));
	}

}