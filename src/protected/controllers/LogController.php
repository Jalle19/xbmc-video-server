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

}
