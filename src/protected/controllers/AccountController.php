<?php

/**
 * Handles user accounts
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class AccountController extends Controller
{

	/**
	 * Updates a password
	 */
	public function actionUpdate()
	{
		$model = $this->loadModel(Yii::app()->user->id);
		
		// Clear the password
		$model->password = '';

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if ($model->save())
			{
				$this->log('"%s" updated password\'s user "%s"', Yii::app()->user->name, 
						$model->username);
				Yii::app()->user->setFlash('success', 'Password updated');

				$this->redirect(array('movie/index'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param int $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = User::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

}
