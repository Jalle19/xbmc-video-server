<?php

/**
 * Handles movie-related actions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MovieController extends VideoLibraryController
{

	/**
	 * Lists all movies in the library
	 */
	public function actionIndex()
	{
		// Get the list of movies along with their thumbnails
		$response = $this->performRequest('VideoLibrary.GetMovies', array(
			'properties'=>array('thumbnail')));

		// Sort the results
		$movies = $response->result->movies;
		$this->sortResults($movies);

		$this->registerScripts();
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($movies, 'movieid')));
	}

}