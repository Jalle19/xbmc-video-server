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
	
	/**
	 * Shows details and download links for the specified movie
	 * @param int $id the movie ID
	 */
	public function actionDetails($id)
	{
		$response = $this->performRequest('VideoLibrary.GetMovieDetails', array(
			'movieid'=>(int)$id,
			'properties'=>array(
				'title',
				'genre',
				'year',
				'rating',
//				'director',
//				'trailer',
				'tagline',
				'plot',
//				'plotoutline',
//				'originaltitle',
//				'lastplayed',
//				'playcount',
//				'writer',
//				'studio',
				'mpaa',
				'cast',
//				'country',
				'imdbnumber',
				'runtime',
//				'set',
//				'showlink',
				'streamdetails',
//				'top250',
				'votes',
//				'fanart',
				'thumbnail',
				'file',
//				'sorttitle',
//				'resume',
//				'setid',
//				'dateadded',
//				'tag',
//				'art',
			)
		));

		$this->registerScripts();

		$movieDetails = $response->result->moviedetails;

		// Create a data provider for the actors. We only show one row (first 
		// credited only), hence the 6
		$actorDataProvider = new CArrayDataProvider(
				$movieDetails->cast, array(
			'keyField'=>'name',
			'pagination'=>array('pageSize'=>6)
		));

		$movieFiles = $this->getMovieLinks($movieDetails);

		$this->render('details', array(
			'details'=>$movieDetails,
			'actorDataProvider'=>$actorDataProvider,
			'movieFiles'=>$movieFiles,
		));
	}
	
	/**
	 * Returns an array with the download links for a movie. It takes the a 
	 * result from GetMovieDetails as parameter.
	 * @param stdClass $movieDetails
	 * @return array the download links
	 */
	private function getMovieLinks($movieDetails)
	{
		$rawFiles = array();
		$files = array();

		// Check for multiple files
		// TODO: Maybe just check for stack://?
		if (strpos($movieDetails->file, ' , ') !== false)
			$rawFiles = preg_split('/ , /i', $movieDetails->file);
		else
			$rawFiles[] = $movieDetails->file;

		foreach ($rawFiles as $rawFile)
		{
			// Detect and remove stack://
			if (substr($rawFile, 0, 8) === 'stack://')
				$rawFile = substr($rawFile, 8);

			// Create the URL to the movie
			$response = $this->performRequest('Files.PrepareDownload', array(
				'path'=>$rawFile));

			$files[] = $this->getAbsoluteVfsUrl($response->result->details->path);
		}

		return $files;
	}

}