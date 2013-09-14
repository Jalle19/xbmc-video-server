<?php

/**
 * Handles logging
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class LogController extends AdminOnlyController
{

	/**
	 * Lists all items in the log
	 */
	public function actionAdmin()
	{
		$model = Log::model();
		$model->scenario = 'search';

		if (isset($_GET['Log']))
		{
			$model->unsetAttributes();
			$model->attributes = $_GET['Log'];
		}

		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Flushes the system log and redirects to the admin page
	 */
	public function actionFlush()
	{
		Yii::app()->db->createCommand()->truncateTable('log');
		Yii::app()->user->setFlash('success', 'System log flushed successfully');
		$this->redirect(array('admin'));
	}

	/**
	 * Displays the specified log item in its fullness
	 * @param int $id the item primary key
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);

		$this->render('view', array(
			'model'=>$model
		));
	}

	/**
	 * Loads the specified model and returns it
	 * @param int $id the primary key of the model
	 * @return Log the model
	 * @throws CHttpException if the model does not exist
	 */
	private function loadModel($id)
	{
		$model = Log::model()->findByPk($id);

		if ($model === null)
			throw new CHttpException(404, 'Not found');

		return $model;
	}

}
