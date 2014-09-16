<?php

/**
 * Determines the power off capabilities of the backend and which actions the
 * user is allowed to perform to power off the backend depending on the user's
 * role and the current settings.
 *
 * @author Pascal Weisenburger <pascal.weisenburger@web.de>
 * @copyright Copyright &copy; Pascal Weisenburger 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PowerOffManager extends CApplicationComponent
{
	const SHUTDOWN = 'shutdown';
	const SUSPEND = 'suspend';
	const HIBERNATE = 'hibernate';
	const REBOOT = 'reboot';

	/**
	 * @var array currently allowed actions
	 */
	private $_allowedActions;

	/**
	 * Initializes the component. The allowed actions are set here.
	 */
	public function init()
	{
		$this->_allowedActions = array();

		$options = array(
			Setting::POWER_OPTION_SHUTDOWN => self::SHUTDOWN,
			Setting::POWER_OPTION_SUSPEND => self::SUSPEND,
			Setting::POWER_OPTION_HIBERNATE => self::HIBERNATE,
			Setting::POWER_OPTION_REBOOT => self::REBOOT,
		);

		foreach ($options as $option => $action)
		{
			if (Yii::app()->user->role == User::ROLE_ADMIN ||
					Setting::getBooleanOption('allowUserPowerOff', $option))
				$this->_allowedActions[] = $action;
		}

		parent::init();
	}

	/**
	 * Returns the power off capabilities of the currently used backend.
	 * @return array the power off capabilities
	 */
	public function getBackendCapabilities()
	{
		$response = Yii::app()->xbmc->performRequest('System.GetProperties',
			array('properties'=>array(
				'canshutdown', 'cansuspend', 'canhibernate', 'canreboot')));

		$capabilities = array();
		if ($response->result->canshutdown)
			$capabilities[] = self::SHUTDOWN;
		if ($response->result->cansuspend)
			$capabilities[] = self::SUSPEND;
		if ($response->result->canhibernate)
			$capabilities[] = self::HIBERNATE;
		if ($response->result->canreboot)
			$capabilities[] = self::REBOOT;

		return $capabilities;
	}

	/**
	 * Returns whether the current backend is capable of performing the given 
	 * power off action.
	 * @param string $action the power off action to be checked
	 * @return boolean whether the power off action is supported
	 */
	public function hasBackendCapability($action)
	{
		return in_array($action, $this->getBackendCapabilities());
	}

	/**
	 * Returns the power off actions allowed for the current user.
	 * @return array the power off actions
	 */
	public function getAllowedActions()
	{
		return $this->_allowedActions;
	}

	/**
	 * Returns whether the current user is allowed to perform the given power
	 * off action.
	 * @param string $action the power off action to be checked
	 * @return boolean whether the power off action is allowed
	 */
	public function isActionAllowed($action)
	{
		return in_array($action, $this->getAllowedActions());
	}

	/**
	 * Powers off the backend by shutting it down, suspending it, hibernating it
	 * or rebooting it.
	 * @param string $action the power off action to be performed
	 */
	public function powerOff($action)
	{
		$method = array(
			self::SHUTDOWN => 'System.Shutdown',
			self::SUSPEND => 'System.Suspend',
			self::HIBERNATE => 'System.Hibernate',
			self::REBOOT => 'System.Reboot',
		)[$action];

		if ($method)
			Yii::app()->xbmc->sendNotification($method);
	}
}