<?php

/**
 * Handles power off actions
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PowerOffController extends AdminOnlyController
{

	/**
	 * @return array the filters
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			array('application.filters.CheckBackendConnectivityFilter + '.
				'shutdown, suspend, hibernate, reboot'),
		));
	}
	
	/**
	 * @return array the access control rules
	 */
	public function accessRules()
	{
		return array_merge(array(
			// Allow normal users to optionally power off the backend
			array('allow', 'actions'=>Yii::app()->powerOffManager->getAllowedActions())
		), parent::accessRules());
	}
	
	/**
	 * Shuts down the backend
	 */
	public function actionShutdown()
	{
		$this->powerOff(PowerOffManager::SHUTDOWN);
	}

	/**
	 * Suspends the backend
	 */
	public function actionSuspend()
	{
		$this->powerOff(PowerOffManager::SUSPEND);
	}

	/**
	 * Hibernates the backend
	 */
	public function actionHibernate()
	{
		$this->powerOff(PowerOffManager::HIBERNATE);
	}

	/**
	 * Reboots the backend
	 */
	public function actionReboot()
	{
		$this->powerOff(PowerOffManager::REBOOT);
	}
	
	/**
	 * Powers off the backend by shutting down, suspending, hibernating or
	 * rebooting the backend
	 * @param string $action the power off action to use, 
	 * could be either of the strings 'shutdown', 'suspend', 'hibernate' or 'reboot'
	 */
	private function powerOff($action)
	{
		if (Yii::app()->powerOffManager->hasBackendCapability($action))
		{
			Yii::app()->powerOffManager->powerOff($action);

			$messages = array(
				PowerOffManager::SHUTDOWN => Yii::t('Backend', 'The current backend is shutting down'),
				PowerOffManager::SUSPEND => Yii::t('Backend', 'The current backend is suspending'),
				PowerOffManager::HIBERNATE => Yii::t('Backend', 'The current backend is hibernating'),
				PowerOffManager::REBOOT => Yii::t('Backend', 'The current backend is rebooting'));
			
			$message = $messages[$action];
			
			Yii::app()->user->setFlash('success', $message);
		}
		else
		{
			$messages = array(
				PowerOffManager::SHUTDOWN => Yii::t('Backend', 'The current backend cannot be shut down'),
				PowerOffManager::SUSPEND => Yii::t('Backend', 'The current backend cannot be suspended'),
				PowerOffManager::HIBERNATE => Yii::t('Backend', 'The current backend cannot be hibernated'),
				PowerOffManager::REBOOT => Yii::t('Backend', 'The current backend cannot be rebooted'));
			
			$message = $messages[$action];
			
			Yii::app()->user->setFlash('error', $message);
		}

		$this->redirectToPrevious(Yii::app()->homeUrl);
	}

}
