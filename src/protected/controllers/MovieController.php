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
		$movies = VideoLibrary::getMovies($filterForm->buildRequestParameters());

		// Redirect to the details page if there's only one result
		if (count($movies) === 1 && $filterForm->name === $movies[0]->label)
			$this->redirect(['details', 'id' => $movies[0]->getId()]);

		$dataProvider = new LibraryDataProvider($movies);
		$dataProvider->makeSortable();

		$this->render('index', [
			'dataProvider' => $dataProvider,
			'filterForm'   => $filterForm,
		]);
	}


	/**
	 * Renders a list of recently added movies
	 */
	public function actionRecentlyAdded()
	{
		$movies = VideoLibrary::getRecentlyAddedMovies();

		$dataProvider = new LibraryDataProvider($movies);
		$dataProvider->makeSortable('dateadded DESC');
		
		$this->render('recentlyAdded', array(
			'dataProvider'=> $dataProvider
		));
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
			'director',
			'imdbnumber',
			'runtime',
			'streamdetails',
			'votes',
			'thumbnail',
			'file'
		));
		
		// Create a data provider for the actors. We only show one row (first 
		// credited only), hence the 6
		$actorDataProvider = new CArrayDataProvider(
				$movieDetails->cast, array(
			'keyField'=>'name',
			'pagination'=>array('pageSize'=>6)
		));
		
		$this->render('details', array(
			'details'=>$movieDetails,
			'actorDataProvider'=>$actorDataProvider,
		));
	}
	
	/**
	 * Serves a playlist containing the specified movie's files to the browser
	 * @param int $id the movie ID
	 */
	public function actionGetMoviePlaylist($id, $playlistFormat)
	{
		$movieDetails = VideoLibrary::getMovieDetails($id, array(
			'file',
			'runtime',
			'year',
			'thumbnail',
		));
		
		$this->log('"%s" streamed "%s"', Yii::app()->user->name, $movieDetails->getDisplayName());
		$this->servePlaylist($movieDetails, $playlistFormat);
	}
	
}
