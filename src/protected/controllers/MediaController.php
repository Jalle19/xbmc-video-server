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
	 * Some parts of the code assume we're in either Movie or TvShowController 
	 * so make sure the common actions are actually available
	 */
	abstract public function actionDetails($id);

	/**
	 * Override parent implementation to add access control filter
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'accessControl',
			'checkBackendCanStream + details',
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
	 * Displays a flash if the backend doesn't support streaming
	 * @param CFilterChain $filterChain the filter chain
	 */
	public function filterCheckBackendCanStream($filterChain)
	{
		// Check backend version and warn about incompatibilities
		if (!Yii::app()->xbmc->meetsMinimumRequirements() && !Setting::getValue('disableFrodoWarning'))
			Yii::app()->user->setFlash('info', Yii::t('Misc', 'Streaming of video files is not possible from XBMC 12 "Frodo" backends'));

		$filterChain->run();
	}

	/**
	 * Sets the display mode for results and redirects the user back to where 
	 * he came from.
	 * @param string $mode the desired display mode
	 */
	public function actionSetDisplayMode($mode)
	{
		$this->setDisplayMode($mode);
		$this->redirectToPrevious(Yii::app()->homeUrl);
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
			throw new InvalidRequestException();

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

		// Use list mode by default for phones
		if ($displayMode === null)
			$displayMode = $this->isMobile() ? self::DISPLAY_MODE_LIST : self::DISPLAY_MODE_GRID;

		return $displayMode;
	}

	/**
	 * Checks whether the visitor is using a mobile device or not (tablets are 
	 * not counted as mobile)
	 * @return boolean
	 */
	private function isMobile()
	{
		// A device cannot suddenly become a mobile so we store the result
		if (!isset($_SESSION['isMobile']))
			$_SESSION['isMobile'] = Browser::isMobile();

		return $_SESSION['isMobile'];
	}

}
