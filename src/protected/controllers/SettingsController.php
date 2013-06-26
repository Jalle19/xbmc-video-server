<?php

/**
 * Handles the application settings
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class SettingsController extends Controller
{

	/**
	 * Returns the filters for this controller. In addition to parent filters 
	 * we want access control.
	 * @return array
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'accessControl',
		));
	}
	
	/**
	 * Returns the access control rules
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'expression'=>function() {
					return Yii::app()->user->role == User::ROLE_ADMIN;
				},
			),
			array('deny'),
		);
	}
	
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