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
			array('application.filters.CheckBackendConnectivityFilter'),
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
			),
			array('deny',
				'actions'=>array('playOnBackend'),
				'expression'=>function() {
					return Yii::app()->user->role !== User::ROLE_ADMIN;
				}
			),
		);
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
	 * @param IStreamable $media the media item
	 * @param string $format the desired playlist format. Defaults to null, 
	 * meaning the configured default format will be used
	 * @return Playlist the playlist
	 */
	private function createPlaylist($media, $format = null)
	{
		// Create the playlist
		$name = $media->getDisplayName();
		$playlist = PlaylistFactory::create($name, $format);

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
	 * @param IStreamable $media a media item
	 * @param string $format the desired playlist format. Defaults to null, 
	 * meaning the configured default format will be used
	 */
	protected function servePlaylist($media, $format = null)
	{
		$playlist = $this->createPlaylist($media, $format);
		
		header('Content-Type: '.$playlist->getMIMEType());
		header('Content-Disposition: attachment; filename="'.$playlist->getSanitizedFileName().'"');

		echo $playlist;
	}
	
	/**
	 * Tells the current backend to play the specified file, then redirects 
	 * to the previous page
	 * @param string $file the file path
	 */
	public function actionPlayOnBackend($file)
	{
		Yii::app()->xbmc->performRequestUncached('Player.Open', array(
			'item'=>array('file'=>$file)));

		// Go back to the previous page and inform the user
		Yii::app()->user->setFlash('info', Yii::t('RetrieveMediaWidget', 'The item should now be playing on {backend}', array(
			'{backend}'=>Yii::app()->backendManager->getCurrent()->name)));

		$this->redirectToPrevious('index');
	}
	
	/**
	 * Plays the specified URL in the in-browser player
	 * @param string $url the URL to the media
	 */
	public function actionWatchInBrowser($url)
	{
		// Create a tuple containing the URL and the MIME type of the file
		$item = new stdClass();
		$item->url = $url;
		$item->mimeType = MediaInfoHelper::getMIMEType($url);

		$this->render('//videoLibrary/browserPlayer', array(
			'items'=>array($item)));
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
