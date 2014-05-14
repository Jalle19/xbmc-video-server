<?php

/**
 * Handles movie-related actions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MovieController extends MediaController
{
	
	protected function getSpectatorProhibitedActions()
	{
		return array('getMoviePlaylist');
	}
	
	/**
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'ajaxOnly + ajaxDisplayVideoPlayer',
		));
	}

	/**
	 * Lists all movies in the library, optionally filtered
	 */
	public function actionIndex()
	{
		// Get the appropriate request parameters from the filter
		$filterForm = new MovieFilterForm();
		$requestParameters = array();

		if (isset($_GET['MovieFilterForm']))
		{
			$filterForm->attributes = $_GET['MovieFilterForm'];

			if (!$filterForm->isEmpty() && $filterForm->validate())
				$requestParameters['filter'] = $filterForm->getFilter();
		}
		
		$movies = VideoLibrary::getMovies($requestParameters);

		// Go directly to the details page if we have an exact match on the 
		// movie name
		if (count($movies) === 1 && $filterForm->name === $movies[0]->label)
			$this->redirect(array('details', 'id'=>$movies[0]->movieid));

		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($movies, 'movieid'),
			'filterForm'=>$filterForm));
	}
	
	/**
	 * Renders a list of recently added movies
	 */
	public function actionRecentlyAdded()
	{
		$movies = VideoLibrary::getRecentlyAddedMovies();
		
		$this->render('recentlyAdded', array(
			'dataProvider'=>new LibraryDataProvider($movies, 'movieid')));
	}
	
	/**
	 * Shows details and download links for the specified movie
	 * @param int $id the movie ID
	 */
	public function actionDetails($id)
	{
		$movieDetails = VideoLibrary::getMovieDetails($id, array(
			'genre',
			'year',
			'rating',
			'tagline',
			'plot',
			'mpaa',
			'cast',
			'imdbnumber',
			'runtime',
			'streamdetails',
			'votes',
			'thumbnail',
			'file'
		));

		if ($movieDetails === null)
			throw new PageNotFoundException();
		
		// Create a data provider for the actors. We only show one row (first 
		// credited only), hence the 6
		$actorDataProvider = new CArrayDataProvider(
				$movieDetails->cast, array(
			'keyField'=>'name',
			'pagination'=>array('pageSize'=>6)
		));
		
		// Get the movie links. Omit credentials if the browser is determined 
		// to be Internet Explorer since they don't support credentials in URLs
		$movieLinks = VideoLibrary::getVideoLinks($movieDetails->file, Browser::isInternetExplorer());
		
		$this->render('details', array(
			'details'=>$movieDetails,
			'actorDataProvider'=>$actorDataProvider,
			'movieLinks'=>$movieLinks,
		));
	}
	
	/**
	 * Renders a video player for the specified movie
	 * @param int $movieId the movie ID
	 */
	public function actionAjaxDisplayVideoPlayer($movieId)
	{
		$movieDetails = VideoLibrary::getMovieDetails($movieId, array(
					'file'));

		// Render the video player modal
		$this->widget('VideoPlayer', array(
			'id'=>'#'.VideoPlayer::HTML_ID,
			'videoDetails'=>$movieDetails,
			'videoLinks'=>VideoLibrary::getVideoLinks($movieDetails->file),
		));
	}

	/**
	 * Serves a playlist containing the specified movie's files to the browser
	 * @param int $movieId the movie ID
	 */
	public function actionGetMoviePlaylist($movieId)
	{
		$movieDetails = VideoLibrary::getMovieDetails($movieId, array(
			'file',
			'runtime',
			'year'
		));
		
		if ($movieDetails === null)
			throw new PageNotFoundException();

		$name = $movieDetails->getDisplayName();
		$playlist = new M3UPlaylist();
		
		// Add the playlist items
		foreach ($this->getPlaylistItems($movieDetails) as $item)
			$playlist->addItem($item);
		
		$this->log('"%s" streamed "%s"', Yii::app()->user->name, $name);

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.M3UPlaylist::sanitizeFilename($name).'.m3u"');

		echo $playlist;
	}
	
}