<?php

/**
 * Base controller which only grants access to actions for administrators
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class AdminOnlyController extends ModelController
{
	
	/**
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'admin';
	}

	/**
	 * Returns the filters for this controller. In addition to parent filters 
	 * we want access control.
	 * @return array
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'accessControl',
		));
	}

	/**
	 * Returns the access control rules
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'expression'=>function() {
					return Yii::app()->user->role == User::ROLE_ADMIN;
				},
			),
			array('deny'),
		);
	}

}