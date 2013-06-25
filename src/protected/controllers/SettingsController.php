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
				// Try to save the configuration to file
				try
				{
					Yii::app()->config->save($configForm->attributes);
				}
				catch (CException $e)
				{
					Yii::app()->user->setFlash('error', $e->getMessage());
				}

				Yii::app()->user->setFlash('success', 'Configuration saved');

				// Refresh to avoid re-submitting the form
				$this->refresh();
			}
		}
		else
		{
			// Populate the form based on the current settings. We ignore 
			// exceptions that get thrown when the desired attribute isn't 
			// available in the current configuration.
			foreach (array_keys($configForm->attributes) as $attribute)
			{
				try
				{
					$configForm->{$attribute} = Yii::app()->config->{$attribute};
				}
				catch (CException $e)
				{
					;
				}
			}
		}

		$this->render('index', array(
			'model'=>$configForm,
		));
	}

}