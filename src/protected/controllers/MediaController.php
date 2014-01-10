<?php

/**
 * Base class for controllers that serve some kind of media to the user
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * Virtual properties in this class
 * @property string $displayMode
 */
abstract class MediaController extends Controller
{
	
	const DISPLAY_MODE_GRID = 'grid';
	const DISPLAY_MODE_LIST = 'list';

	/**
	 * @return array list of actions that a spectator should be prohibited from 
	 * performing
	 */
	abstract protected function getSpectatorProhibitedActions();

	/**
	 * Override parent implementation to add access control filter
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'accessControl'
		));
	}

	/**
	 * @return array the access rules for this controller
	 */
	public function accessRules()
	{
		return array(
			// Prevent spectators from performing certain actions
			array('deny',
				'actions'=>$this->getSpectatorProhibitedActions(),
				'expression'=>function() {
					return Yii::app()->user->role === User::ROLE_SPECTATOR;
				}
			)
		);
	}
	
	/**
	 * Sets the display mode for results and redirects the user back to where 
	 * he came from.
	 * @param string $mode the desired display mode
	 */
	public function actionSetDisplayMode($mode)
	{
		$this->setDisplayMode($mode);

		if (Yii::app()->request->urlReferrer)
			$this->redirect(Yii::app()->request->urlReferrer);
		else
			$this->redirect(Yii::app()->homeUrl);
	}
	
	/**
	 * Setter for displayMode.
	 * @param string $mode the desired display mode
	 * @throws CHttpException if the specified mode is invalid
	 */
	public function setDisplayMode($mode)
	{
		// Check that the mode is valid
		if (!in_array($mode, array(self::DISPLAY_MODE_GRID, self::DISPLAY_MODE_LIST)))
			throw new CHttpException(400, 'Invalid request');

		Yii::app()->session->add('mediaDisplayMode', $mode);
	}

	/**
	 * Getter for displayMode. If no mode has been set we default to grid mode 
	 * when the browser is determined to be a desktop or tablet and list mode 
	 * for phones.
	 */
	public function getDisplayMode()
	{
		$displayMode = Yii::app()->session->get('mediaDisplayMode');

		if ($displayMode === null)
		{
			// TODO: Determine by browser
			$displayMode = self::DISPLAY_MODE_GRID;
		}

		return $displayMode;
	}

}