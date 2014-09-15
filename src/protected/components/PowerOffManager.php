<?php

/**
 * Determines which actions the user is allowed to perform to power off the
 * backend depending on the user's role and the current settings.
 *
 * @author Pascal Weisenburger <pascal.weisenburger@web.de>
 * @copyright Copyright &copy; Pascal Weisenburger 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PowerOffManager extends CApplicationComponent
{

	/**
	 * @return boolean whether the user is allowed to shut down the backend
	 */
	public function shutdownAllowed()
	{
		return
			Yii::app()->user->role == User::ROLE_ADMIN ||
			Setting::getBooleanOption('allowUserPowerOff', Setting::POWER_OPTION_SHUTDOWN);
	}

	/**
	 * @return boolean whether the user is allowed to suspend the backend
	 */
	public function suspendAllowed()
	{
		return
			Yii::app()->user->role == User::ROLE_ADMIN ||
			Setting::getBooleanOption('allowUserPowerOff', Setting::POWER_OPTION_SUSPEND);
	}

	/**
	 * @return boolean whether the user is allowed to hibernate the backend
	 */
	public function hibernateAllowed()
	{
		return
			Yii::app()->user->role == User::ROLE_ADMIN ||
			Setting::getBooleanOption('allowUserPowerOff', Setting::POWER_OPTION_HIBERNATE);
	}

	/**
	 * @return boolean whether the user is allowed to reboot the backend
	 */
	public function rebootAllowed()
	{
		return
			Yii::app()->user->role == User::ROLE_ADMIN ||
			Setting::getBooleanOption('allowUserPowerOff', Setting::POWER_OPTION_REBOOT);
	}

	/**
	 * @return boolean whether the user is allowed to performs any actions that
	 * powers off the backend, like shutting down, suspending, hibernating or
	 * rebooting
	 */
	public function powerOffAllowed()
	{
		return
			$this->shutdownAllowed() || $this->suspendAllowed() ||
			$this->hibernateAllowed() || $this->rebootAllowed();
	}
}
