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
	 * @return array the filters
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'ajaxOnly + delete, ajaxCheckConnectivity',
			array('application.filters.CheckBackendConnectivityFilter + '.
				'updateLibrary'),
		));
	}

	/**
	 * Override parent implementation to allow everyone to change backend and 
	 * update the library
	 * @return array the access control rules
	 */
	public function accessRules()
	{
		$actions = array('change', 'updateLibrary', 'waitForConnectivity', 
				'ajaxCheckConnectivity', 'waitForLibraryUpdate');

		return array_merge(array(
			array('allow', 'actions'=>$actions)
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
		
		Yii::app()->backendManager->setCurrent($model);

		$this->log('"%s" switched backend to "%s"', Yii::app()->user->name, 
						$model->name);
		Yii::app()->user->setFlash('success', Yii::t('Backend', 'Changed backend to {backendName}', 
				array('{backendName}'=>$model->name)));

		// Redirect to the previous page, unless that page was a details page 
		// or the "waiting for connectivity" page, in which case we go to the 
		// home URL
		$referrer = Yii::app()->request->urlReferrer;
		
		$invalidReferrers = array(
			'tvShow/details',
			'movie/details',
			'backend/waitForConnectivity');

		foreach ($invalidReferrers as $invalidReferrer)
			if (strpos($referrer, $invalidReferrer) !== false)
				$this->redirect(Yii::app()->homeUrl);

		$this->redirectToPrevious(Yii::app()->homeUrl);
	}
	
	/**
	 * Initiates a library update. The update is performed differently 
	 * depending on whether WebSocket connectivity to the backend is available
	 */
	public function actionUpdateLibrary()
	{
		$backend = $this->getCurrent();
		
		Yii::app()->user->setFlash('success', Yii::t('Misc', 'Library update has been initiated'));

		if (!$backend->hasWebSocketConnectivity())
			$this->asynchronousLibraryUpdate();
		else
			$this->synchronousLibraryUpdate();
	}

	/**
	 * Triggers an asynchronous library update, meaning there will be no way to 
	 * determine whether the update finished.
	 */
	private function asynchronousLibraryUpdate()
	{
		// Update the library
		Yii::app()->xbmc->sendNotification('VideoLibrary.Scan');

		// Remind users that they'll have to flush their cache
		if (Setting::getBoolean('cacheApiCalls'))
			Yii::app()->user->setFlash('info', Yii::t('Misc', "You'll have to flush the API call cache to see any newly scanned content"));

		$this->redirectToPrevious(Yii::app()->homeUrl);
	}

	/**
	 * Renders a waiting page which triggers actionWaitForLibraryUpdate()
	 */
	private function synchronousLibraryUpdate()
	{
		$this->render('waitForLibraryUpdate');
	}
	
	/**
	 * Triggers a library update over a WebSocket connection and blocks the 
	 * request until the update has finished
	 */
	public function actionWaitForLibraryUpdate()
	{
		$backend = $this->getCurrent();

		// Create a library update listener
		$listener = new LibraryUpdateListener($backend);

		// Log when the scan was started and finished
		$listener->onScanStarted = function() use($backend)
		{
			$this->log('Library update started on %s', $backend->name);
		};

		$listener->onScanFinished = function() use($backend)
		{
			$this->log('Library update finished on %s', $backend->name);
		};

		// Wait for the library update to finish
		$listener->blockUntil(LibraryUpdateListener::METHOD_ON_SCAN_FINISHED);
		Yii::app()->user->setFlash('success', Yii::t('Misc', 'Library update completed'));
		
		// Flush the cache so potential new content will be available
		if (Setting::getBoolean('cacheApiCalls'))
			Yii::app()->apiCallCache->flush();
	}

	/**
	 * Displays the "waiting for connection" page where the user is asked to 
	 * wait until the backend has been woken using WOL
	 */
	public function actionWaitForConnectivity()
	{
		$backend = $this->getCurrent();

		// Determine the IP address of the backend
		if (filter_var($backend->hostname, FILTER_VALIDATE_IP))
			$ipAddress = $backend->hostname;
		else
			$ipAddress = gethostbyname($backend->hostname);

		// Send the WOL packet
		$wol = new \Phpwol\Factory();
		$magicPacket = $wol->magicPacket();

		if (!$ipAddress || !$magicPacket->send($backend->macAddress, $ipAddress, $backend->subnetMask))
			throw new CHttpException(500, Yii::t('Backend', 'Unable to send WOL packet'));

		// Render the "waiting" page
		Yii::app()->user->setFlash('error', Yii::t('Backend', 'The current backend is not connectable at the moment'));

		$this->render('waitForConnectivity');
	}

	/**
	 * AJAX polling URL which returns whether the current backend is 
	 * connectable or not
	 */
	public function actionAjaxCheckConnectivity()
	{
		$this->layout = false;
		echo json_encode(array('status'=>$this->getCurrent()->isConnectable(null, false)));
		Yii::app()->end();
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

			// Check whether this is the first backend ever created, if so we 
			// redirect to the settings page
			$firstRun = $this->getCurrent() === null;
			
			if ($model->save())
			{
				$this->log('"%s" created backend "%s"', Yii::app()->user->name, 
						$model->name);
				
				Yii::app()->user->setFlash('success', Yii::t('Backend', 'Backend created successfully'));
				$this->checkWebSocketConnectivity($model);
				
				if ($firstRun)
				{
					Yii::app()->user->setFlash('info', Yii::t('Settings', 'Before you get started, please have a look at the application settings'));
					$this->redirect(array('setting/admin'));
				}
				else
					$this->redirect(array('admin'));
			}
		}
		else
		{
			$model->hostname = 'localhost';
			$model->port = 8080;
			$model->tcp_port = 9090;
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

		if ($this->saveFromPost($model))
		{
			$this->log('"%s" updated backend "%s"', Yii::app()->user->name, 
					$model->name);

			Yii::app()->user->setFlash('success', Yii::t('Backend', 'Backend updated successfully'));
			$this->checkWebSocketConnectivity($model);
			
			$this->redirect(array('admin'));
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
		
		if ($model == $this->getCurrent())
			throw new CHttpException(403, Yii::t('Backend', "You can't delete the current backend. Please switch to another one if you want to delete this."));

		$model->delete();

		$this->log('"%s" deleted backend "%s"', Yii::app()->user->name, 
						$model->name);
		
		$this->redirectOnDelete();
	}
	
	/**
	 * @return Backend the current backend
	 */
	private function getCurrent()
	{
		return Yii::app()->backendManager->getCurrent();
	}
	
	/**
	 * Checks whether the specified backend can be contacted on the 
	 * configured TCP port and raises a warning if it can't.
	 * @param Backend $backend
	 */
	private function checkWebSocketConnectivity($backend)
	{
		if (!$backend->hasWebSocketConnectivity())
		{
			Yii::app()->user->setFlash('info', 
					Yii::t('Backend', 'WebSocket connection not available, library updates will be performed synchronously'));
		}
	}

}