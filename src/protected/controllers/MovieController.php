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

		$this->renderIndex($movies, $filterForm);
	}
	
	/**
	 * Renders a list of recently added movies
	 */
	public function actionRecentlyAdded()
	{
		$movies = VideoLibrary::getRecentlyAddedMovies();
		
		$this->render('recentlyAdded', array(
			'dataProvider'=>new LibraryDataProvider($movies)));
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
	 * @param int $movieId the movie ID
	 */
	public function actionGetMoviePlaylist($movieId)
	{
		$movieDetails = VideoLibrary::getMovieDetails($movieId, array(
			'file',
			'runtime',
			'year',
			'thumbnail',
		));
		
		$this->log('"%s" streamed "%s"', Yii::app()->user->name, $movieDetails->getDisplayName());
		$this->servePlaylist($movieDetails);
	}
	
}