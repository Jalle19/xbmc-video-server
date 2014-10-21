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
				'updateLibrary, shutdown, suspend, hibernate, reboot'),
		));
	}

	/**
	 * Override parent implementation to allow everyone to change backend and 
	 * update the library
	 * @return array the access control rules
	 */
	public function accessRules()
	{
		$allowedActions = array(
			'change', 'updateLibrary', 'waitForConnectivity', 'ajaxCheckConnectivity'
		);

		if (Yii::app()->powerOffManager->shutdownAllowed())
			$allowedActions[] = 'shutdown';

		if (Yii::app()->powerOffManager->suspendAllowed())
			$allowedActions[] = 'suspend';

		if (Yii::app()->powerOffManager->hibernateAllowed())
			$allowedActions[] = 'hibernate';

		if (Yii::app()->powerOffManager->rebootAllowed())
			$allowedActions[] = 'reboot';

		return array_merge(array(
			array('allow', 'actions'=>$allowedActions)
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
	 * Initiates a library update and redirects the user to his previous 
	 * location
	 */
	public function actionUpdateLibrary()
	{
		// Update the library. There will be no indication if success/failure 
		// so we have to assume it worked
		Yii::app()->xbmc->sendNotification('VideoLibrary.Scan');
		Yii::app()->user->setFlash('success', Yii::t('Misc', 'Library update has been initiated'));

		// Remind users that they'll have to flush their cache
		if (Setting::getBoolean('cacheApiCalls'))
			Yii::app()->user->setFlash('info', Yii::t('Misc', "You'll have to flush the API call cache to see any newly scanned content"));

		$this->redirectToPrevious(Yii::app()->homeUrl);
	}

	/**
	 * Shuts down the backend
	 */
	public function actionShutdown()
	{
		$this->powerOff('shutdown');
	}

	/**
	 * Suspends the backend
	 */
	public function actionSuspend()
	{
		$this->powerOff('suspend');
	}

	/**
	 * Hibernates the backend
	 */
	public function actionHibernate()
	{
		$this->powerOff('hibernate');
	}

	/**
	 * Reboots the backend
	 */
	public function actionReboot()
	{
		$this->powerOff('reboot');
	}

	/**
	 * Powers off the backend by shutting down, suspending, hibernating or
	 * rebooting the backend
	 * @param string $method the method used to power off the backend,
	 * could be either of the strings 'shutdown', 'suspend', 'hibernate' or 'reboot'
	 */
	private function powerOff($method)
	{
		$response = Yii::app()->xbmc->performRequest('System.GetProperties', array(
				'properties'=>array('canshutdown', 'cansuspend', 'canhibernate', 'canreboot')));

		$successInfo = false;
		$failureInfo = false;

		switch ($method)
		{
			case 'shutdown':
				$method = 'System.Shutdown';
				if ($response->result->canshutdown)
					$successInfo = Yii::t('Backend', 'The current backend is shutting down');
				else
					$failureInfo = Yii::t('Backend', 'The current backend cannot be shut down');
			break;

			case 'suspend':
				$method = 'System.Suspend';
				if ($response->result->cansuspend)
					$successInfo = Yii::t('Backend', 'The current backend is suspending');
				else
					$failureInfo = Yii::t('Backend', 'The current backend cannot be suspended');
			break;

			case 'hibernate':
				$method = 'System.Hibernate';
				if ($response->result->canhibernate)
					$successInfo = Yii::t('Backend', 'The current backend is hibernating');
				else
					$failureInfo = Yii::t('Backend', 'The current backend cannot be hibernated');
			break;

			case 'reboot':
				$method = 'System.Reboot';
				if ($response->result->canreboot)
					$successInfo = Yii::t('Backend', 'The current backend is rebooting');
				else
					$failureInfo = Yii::t('Backend', 'The current backend cannot be rebooted');
			break;
		}

		if ($successInfo !== false)
		{
			Yii::app()->xbmc->sendNotification($method);
			Yii::app()->user->setFlash('success', $successInfo);
		}

		if ($failureInfo !== false)
			Yii::app()->user->setFlash('error', $failureInfo);

		$this->renderText('');
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

		// Start the poller which redirects once the backend is reachable
		Yii::app()->clientScript->registerScript(__CLASS__.'_startPolling', '
			startPolling();
		', CClientScript::POS_END);

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
		echo json_encode(array('status'=>$this->getCurrent()->isConnectable(false)));
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

}