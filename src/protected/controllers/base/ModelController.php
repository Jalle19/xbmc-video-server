<?php

/**
 * Base controller class for controllers that operate mainly on a specific type 
 * of model. It provides some methods that are usually needed by derived 
 * classes.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class ModelController extends Controller
{

	/**
	 * Generic "admin" action. It renders the "admin" view and handles 
	 * attribute filtering.
	 */
	public function actionAdmin()
	{
		$modelClass = $this->getModelClass();

		$model = $modelClass::model();
		$model->scenario = 'search';

		if (isset($_GET[$modelClass]))
		{
			$model->unsetAttributes();
			$model->attributes = $_GET[$modelClass];
		}

		$this->render('admin', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Attempts to save the specified model with attribute values from POST. If 
	 * the saving fails or if no POST data is available it returns false.
	 * @param CActiveRecord $model the model
	 * @return boolean whether the model was saved
	 */
	protected function saveFromPost(&$model)
	{
		if (isset($_POST[$this->getModelClass()]))
		{
			$model->attributes = $_POST[$this->getModelClass()];

			if ($model->save())
				return true;
		}

		return false;
	}

	/**
	 * Finds and returns a model with the specified ID
	 * @param mixed $id a primary key
	 * @return CActiveRecord the model
	 * @throws PageNotFoundException if the model is not found
	 */
	protected function loadModel($id)
	{
		$modelClass = $this->getModelClass();
		$model = $modelClass::model()->findByPk($id);

		if ($model === null)
			throw new PageNotFoundException();

		return $model;
	}
	
	/**
	 * Performs a standard redirect after a model has been deleted
	 */
	protected function redirectOnDelete()
	{
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * @return string the name of the model class this controller represents
	 */
	private function getModelClass()
	{
		$controllerClass = get_class($this);
		return substr($controllerClass, 0, -(strlen('Controller')));
	}

}
