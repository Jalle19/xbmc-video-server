<?php

/**
 * Provides a convenient way to access the current backend and its configuration
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class BackendManager extends CApplicationComponent
{

	/**
	 * @var Backend the current backend
	 */
	private $_backend;

	/**
	 * Initializes the component. The backend to use is chosen here. If a 
	 * current backend is stored in the session we use that, otherwise we fall 
	 * back on whatever backend is set as default.
	 */
	public function init()
	{
		$currentBackendId = Yii::app()->session->get('currentBackendId');

		if ($currentBackendId !== null)
			$this->_backend = Backend::model()->findByPk($currentBackendId);
		else
			$this->_backend = Backend::model()->default()->find();

		parent::init();
	}

	/**
	 * @return Backend the current backend, or null if no backend is configured
	 */
	public function getCurrent()
	{
		return $this->_backend;
	}

}