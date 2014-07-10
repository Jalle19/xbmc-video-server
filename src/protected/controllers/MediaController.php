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
		// See if the user has a stored display mode for this context
		$displayMode = DisplayMode::model()->findByContext($context);

		if ($displayMode === null)
			$displayMode = new DisplayMode();

		$displayMode->mode = $mode;
		$displayMode->context = $context;

		if (!$displayMode->save())
			throw new InvalidRequestException();

		$this->redirectToPrevious(Yii::app()->homeUrl);
	}

	/**
	 * Returns the current display mode for the specified context
	 * @param string $context the context
	 * @return string the display mode
	 */
	public function getDisplayMode($context)
	{
		$model = DisplayMode::model()->findByContext($context);

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
		// Use list view for seasons
		if ($context === DisplayMode::CONTEXT_SEASONS)
			return DisplayMode::MODE_LIST;

		return DisplayMode::MODE_GRID;
	}
	
	/**
	 * Generates a playlist from the specified media item
	 * @param Media $media the media item
	 * @return Playlist the playlist
	 */
	private function createPlaylist($media)
	{
		// Create the playlist
		$name = $media->getDisplayName();
		$playlist = PlaylistFactory::create($name);

		// Add the playlist items
		foreach ($media->getItemLinks() as $itemLink)
		{
			// The item used in the playlist is not necessarily the same item 
			// as $media
			$item = new PlaylistItem($itemLink);
			$playlist->addItem($item);
		}

		return $playlist;
	}

	/**
	 * Creates and serves a playlist based on the specified media item
	 * @param Media $media a media item
	 */
	protected function servePlaylist($media)
	{
		$playlist = $this->createPlaylist($media);
		
		header('Content-Type: '.$playlist->getMIMEType());
		header('Content-Disposition: attachment; filename="'.$playlist->getSanitizedFileName().'"');

		echo $playlist;
	}
	
	/**
	 * Renders an index page based on the specified list of media items and 
	 * the filter form. If the results contain a single item the user is 
	 * redirected to that item's details page instead.
	 * @param Media[] $items the media items
	 * @param VideoFilterForm $filterForm the filter form
	 */
	protected function renderIndex($items, $filterForm)
	{
		if (count($items) === 1 && $filterForm->name === $items[0]->label)
			$this->redirect(array('details', 'id'=>$items[0]->getId()));

		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($items),
			'filterForm'=>$filterForm));
	}

}
