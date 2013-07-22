<?php

/**
 * Handles the backends
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class BackendController extends AdminOnlyController
{

	/**
	 * Override parent implementation to allow everyone to change backend
	 * @return array the access control rules
	 */
	public function accessRules()
	{
		$rules = array(
			array('allow', 'actions'=>array('change'))
		);
		return array_merge($rules, parent::accessRules());
	}

	/**
	 * Override parent implementation so we don't get stuck in a redirect loop
	 * @param CFilterChain $filterChain
	 */
	public function filterCheckConfiguration($filterChain)
	{
		if ($this->route === 'backend/create')
			$filterChain->run();
		else
			parent::filterCheckConfiguration($filterChain);
	}
	
	public function actionChange($id)
	{
		$model = $this->loadModel($id);

		Yii::app()->session->add('currentBackendId', $model->id);
		Yii::app()->user->setFlash('success', 'Changed backend to '.$model->name);
		
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Manages all backends
	 */
	public function actionAdmin()
	{
		$model = new Backend();
		$model->unsetAttributes();
		if (isset($_GET['Backend']))
			$model->attributes = $_GET['Backend'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model = new Backend();

		if (isset($_POST['Backend']))
		{
			$model->attributes = $_POST['Backend'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Backend created successfully');
				
				$this->redirect(array('admin'));
			}
		}
		else
		{
			$model->hostname = 'localhost';
			$model->port = 8080;
			$model->username = 'xbmc';
			$model->password = 'xbmc';

			// Check "default" if there are no other backends
			$backends = Backend::model()->findAll();

			if (count($backends) === 0)
				$model->default = true;
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}
	
	public function actionUpdate($id) {
		$model = $this->loadModel($id);

		if (isset($_POST['Backend']))
		{
			$model->attributes = $_POST['Backend'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Backend updated successfully');

				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Deletes a backend
	 * @param int $id the backend ID
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * Finds and returns the backend with the specified ID
	 * @param int $id the backend ID
	 * @return Backend the backend model
	 * @throws CHttpException if the model is not found
	 */
	private function loadModel($id)
	{
		$model = Backend::model()->findByPk($id);

		if ($model === null)
			throw new CHttpException(404, 'Could not find the specified backend');

		return $model;
	}

}