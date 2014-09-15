<?php

/**
 * Checks whether the current backend is connectable. If it is we continue 
 * as normal, if not we redirect to the "waiting for WOL" page if a MAC 
 * address has been configured for the backend, otherwise we just error out.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class CheckBackendConnectivityFilter extends CFilter
{

	/**
	 * @param CFilterChain $filterChain
	 * @return boolean whether to continue the filtering
	 * @throws CHttpException if the current backend is not connectable and 
	 * cannot be awakened
	 */
	protected function preFilter($filterChain)
	{
		/* @var $backend Backend */
		$backend = Yii::app()->backendManager->getCurrent();

		if (!$backend->isConnectable())
		{
			if ($backend->macAddress)
				$filterChain->controller->redirect(array('backend/waitForConnectivity'));
			else
				throw new CHttpException(500, Yii::t('Backend', 'The current backend is not connectable at the moment'));
		}

		return true;
	}

}
