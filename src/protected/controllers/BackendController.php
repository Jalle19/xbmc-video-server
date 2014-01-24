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
	 * Override parent to force AJAX only on the delete action
	 * @return array the filters
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'ajaxOnly + delete',
		));
	}

	/**
	 * Override parent implementation to allow everyone to change backend and 
	 * update the library
	 * @return array the access control rules
	 */
	public function accessRules()
	{
		return array_merge(array(
			array('allow', 'actions'=>array('change', 'updateLibrary'))
		), parent::accessRules());
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
	
	/**
	 * Switches to the specified backend
	 * @param int $id the backend ID
	 */
	public function actionChange($id)
	{
		$model = $this->loadModel($id);

		Yii::app()->session->add('currentBackendId', $model->id);
		$this->log('"%s" switched backend to "%s"', Yii::app()->user->name, 
						$model->name);
		Yii::app()->user->setFlash('success', 'Changed backend to '.$model->name);
		
		// Always redirect to the previous page, except when that page is a 
		// movie or TV show details page since they will most likely not be 
		// the same across backends
		$referrer = Yii::app()->request->urlReferrer;
		
		if (strpos($referrer, 'tvShow/details') !== false || strpos($referrer, 'movie/details') !== false)
			$this->redirect(Yii::app()->homeUrl);
		else
			$this->redirectToPrevious(Yii::app()->homeUrl);
	}
	
	/**
	 * Initiates a library update and redirects the user to his previous 
	 * location
	 */
	public function actionUpdateLibrary()
	{
		// Update the library. There will be no indication if success/failure 
		// so we have to assume it worked
		Yii::app()->xbmc->sendNotification('VideoLibrary.Scan');
		Yii::app()->user->setFlash('success', 'Library update has been initiated');

		// Remind users that they'll have to flush their cache
		if (Setting::getValue('cacheApiCalls'))
			Yii::app()->user->setFlash('info', "You'll have to flush the API call cache to see any newly scanned content");

		$this->redirectToPrevious(Yii::app()->homeUrl);
	}

	/**
	 * Manages all backends
	 */
	public function actionAdmin()
	{
		$this->render('admin');
	}

	/**
	 * Creates a new backend, then return to the admin action
	 */
	public function actionCreate()
	{
		$model = new Backend();

		if (isset($_POST['Backend']))
		{
			$model->attributes = $_POST['Backend'];

			if ($model->save())
			{
				$this->log('"%s" created backend "%s"', Yii::app()->user->name, 
						$model->name);
				
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
	
	/**
	 * Updates the specified backend, then returns to the admin action
	 * @param int $id the backend ID
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (isset($_POST['Backend']))
		{
			$model->attributes = $_POST['Backend'];

			if ($model->save())
			{
				$this->log('"%s" updated backend "%s"', Yii::app()->user->name, 
						$model->name);
				
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
		$model = $this->loadModel($id);
		
		if ($model == Yii::app()->backendManager->getCurrent())
			throw new CHttpException(403, "You can't delete the current backend. Please switch to another one if you want to delete this.");

		$model->delete();

		$this->log('"%s" deleted backend "%s"', Yii::app()->user->name, 
						$model->name);
		
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