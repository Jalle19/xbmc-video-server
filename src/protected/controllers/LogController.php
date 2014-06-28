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
	 * Override parent implementation in order to force POST for the logEvent 
	 * action
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'postOnly + logEvent',
		));
	}

	/**
	 * Override parent implementation so the user can check the logs even when 
	 * a backend is not yet configured
	 * @param CFilterChain $filterChain
	 */
	public function filterCheckConfiguration($filterChain)
	{
		$filterChain->run();
	}

	/**
	 * Override parent implementation to allow anyone to post log events
	 * @return array the access rules for this controller
	 */
	public function accessRules()
	{
		return array_merge(array(
			array('allow',
				'actions'=>array('logEvent'),
			),
			), parent::accessRules());
	}

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
		Yii::app()->user->setFlash('success', Yii::t('Log', 'System log flushed successfully'));
		$this->redirect(array('admin'));
	}
	
	/**
	 * AJAX-only action which logs the event described in the POST data. Used 
	 * to log link clicks using JavaScript.
	 * @throws CHttpException if the request is invalid
	 */
	public function actionLogEvent()
	{
		foreach (array('logCategory', 'logMessage') as $attribute)
			if (!isset($_POST[$attribute]))
				throw new InvalidRequestException();

		// The message may be HTML-encoded
		$message = html_entity_decode($_POST['logMessage']);
		Yii::log($message, CLogger::LEVEL_INFO, $_POST['logCategory']);
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

}
