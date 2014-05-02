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
			'title',
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
		
		// Check backend version and warn about incompatibilities
		if (!Yii::app()->xbmc->meetsMinimumRequirements() && !Setting::getValue('disableFrodoWarning'))
			Yii::app()->user->setFlash('info', 'Streaming of video files is not possible from XBMC 12 "Frodo" backends');

		$this->render('details', array(
			'details'=>$movieDetails,
			'actorDataProvider'=>$actorDataProvider,
			'movieLinks'=>VideoLibrary::getVideoLinks($movieDetails->file),
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
			'title',
			'year'
		));
		
		if ($movieDetails === null)
			throw new PageNotFoundException();

		$links = VideoLibrary::getVideoLinks($movieDetails->file);
		$name = $movieDetails->title.' ('.$movieDetails->year.')';
		$playlist = new M3UPlaylist();
		$linkCount = count($links);

		foreach ($links as $k=> $link)
		{
			$label = $linkCount > 1 ? $name.' (#'.++$k.')' : $name;
			
			$playlist->addItem(array(
				'runtime'=>(int)$movieDetails->runtime,
				'label'=>$label,
				'url'=>$link));
		}
		
		$this->log('"%s" streamed "%s"', Yii::app()->user->name, $movieDetails->title);

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.$name.'.m3u"');

		echo $playlist;
	}
	
}