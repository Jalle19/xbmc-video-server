<?php

/**
 * Base controller class for controllers that operate mainly on a specific type 
 * of model. It provides a loadModel() method which loads a model with a 
 * specified ID.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class ModelController extends Controller
{

	/**
	 * Finds and returns a model with the specified ID
	 * @param mixed $id a primary key
	 * @return CActiveRecord the model
	 * @throws PageNotFoundException if the model is not found
	 */
	protected function loadModel($id)
	{
		// Determine the model class name
		$controllerClass = get_class($this);
		$modelClass = substr($controllerClass, 0, -(strlen('Controller')));

		$model = $modelClass::model()->findByPk($id);

		if ($model === null)
			throw new PageNotFoundException();

		return $model;
	}

}
