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
			'checkBackendConnectivity',
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
	 * Checks whether the current backend is connectable. If it is we continue 
	 * as normal, if not we redirect to the "waiting for WOL" page if a MAC 
	 * address has been configured, otherwise we just error out.
	 * @param CFilterChain $filterChain
	 */
	public function filterCheckBackendConnectivity($filterChain)
	{
		/* @var $backend Backend */
		$backend = Yii::app()->backendManager->getCurrent();

		if (!$backend->isConnectable())
		{
			if ($backend->macAddress)
				$this->redirect(array('backend/waitForConnectivity'));
			else
				throw new CHttpException(500, Yii::t('Backend', 'The current backend is not connectable at the moment'));
		}

		$filterChain->run();
	}

	/**
	 * Displays a flash if the backend doesn't support streaming
	 * @param CFilterChain $filterChain the filter chain
	 */
	public function filterCheckBackendCanStream($filterChain)
	{
		// Check backend version and warn about incompatibilities
		if (!Yii::app()->xbmc->meetsMinimumRequirements() && !Setting::getBoolean('disableFrodoWarning'))
			Yii::app()->user->setFlash('info', Yii::t('Misc', 'Streaming of video files is not possible from XBMC 12 "Frodo" backends'));

		$filterChain->run();
	}

	/**
	 * Sets the display mode for the specified context and redirects the user 
	 * back to where he came from.
	 * @param string $mode the desired display mode
	 * @param string $context the context
	 */
	public function actionSetDisplayMode($mode, $context)
	{
		$this->setDisplayMode(new DisplayMode($mode, $context));
		$this->redirectToPrevious(Yii::app()->homeUrl);
	}

	/**
	 * Persists the specified display mode
	 * @param DisplayMode $displayMode the display mode
	 * @throws CHttpException if the specified mode is invalid
	 */
	public function setDisplayMode($displayMode)
	{
		if (!$displayMode->validate())
			throw new InvalidRequestException();

		Yii::app()->session->add($this->getSessionKey($displayMode->context), $displayMode);
	}

	/**
	 * Returns the current display mode for the specified context
	 * @param string $context the context
	 */
	public function getDisplayMode($context)
	{
		/* @var $model DisplayMode */
		$model = Yii::app()->session->get($this->getSessionKey($context));

		// Use default display mode if no specific one is stored
		if ($model === null)
			$displayMode = $this->getDefaultDisplayMode($context);
		else
			$displayMode = $model->mode;
		
		return $displayMode;
	}
	
	/**
	 * Returns the default display mode for the specified context
	 * @param string $context the context
	 * @return string the display mode
	 */
	private function getDefaultDisplayMode($context)
	{
		// Mobile browsers always get list mode regardless of context, and 
		// the season context defaults to list mode too
		if ($this->isMobile() || $context === DisplayMode::CONTEXT_SEASONS)
			return DisplayMode::MODE_LIST;

		return DisplayMode::MODE_GRID;
	}

	/**
	 * Returns the session key to use for storing a display mode with the 
	 * specified context
	 * @param string $context the display mode context
	 * @return string the session key
	 */
	private function getSessionKey($context)
	{
		return 'mediaDisplayMode['.$context.']';
	}

	/**
	 * Returns an array of playlist items for the specified media item's files.
	 * Most items have just one file but some have more, hence it returns an 
	 * array.
	 * @param Media $media the media
	 * @return array
	 */
	protected function getPlaylistItems(Media $media)
	{
		$items = array();
		$name = $media->getDisplayName();

		$links = VideoLibrary::getVideoLinks($media->file);
		$linkCount = count($links);

		foreach ($links as $k => $link)
		{
			$label = $linkCount > 1 ? $name.' (#'.++$k.')' : $name;

			$items[] = array(
				'runtime'=>(int)$media->runtime,
				'label'=>$label,
				'url'=>$link);
		}

		return $items;
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
