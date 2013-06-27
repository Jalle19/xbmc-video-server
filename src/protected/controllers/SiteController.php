<?php

/**
 * Displays exceptions and handles authentication
 */
class SiteController extends Controller
{

	/**
	 * Returns the filters for this controller. We don't want any filters to 
	 * apply in this context so we return an empty array.
	 * @return array
	 */
	public function filters()
	{
		return array();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if (($error = Yii::app()->errorHandler->error))
		{
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page and logs in the user if correct credentials are 
	 * entered
	 */
	public function actionLogin()
	{
		$this->layout = 'login';

		$model = new LoginForm();

		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}

		$this->render('login', array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to the login page (since all 
	 * other pages require authentication)
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('site/login'));
	}

}