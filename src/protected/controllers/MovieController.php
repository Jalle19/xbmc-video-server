<?php

/**
 * Handles movie-related actions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MovieController extends VideoLibraryController
{

	/**
	 * Lists all movies in the library, optionally filtered
	 */
	public function actionIndex()
	{
		// Start building the request parameters
		$requestParameters = array(
			'properties'=>array('thumbnail'));

		// Get filter properties
		$movieFilterForm = new MovieFilterForm();
		$nativeFilters = array();

		if (isset($_GET['MovieFilterForm']))
		{
			$movieFilterForm->attributes = $_GET['MovieFilterForm'];

			if ($movieFilterForm->validate())
			{
				$nativeFilters['title'] = $movieFilterForm->name;
				$nativeFilters['genre'] = $movieFilterForm->genre;
				$nativeFilters['year'] = $movieFilterForm->year;

				$nativeFilters = array_filter($nativeFilters);
			}
		}

		// Add filter request parameter. If no filter is defined the parameter 
		// must be omitted.
		foreach ($nativeFilters as $field=> $value)
		{
			$filter = new stdClass();
			$filter->field = $field;
			$filter->operator = 'is';
			$filter->value = $value;

			if (!isset($requestParameters['filter']))
				$requestParameters['filter'] = new stdClass();

			$requestParameters['filter']->and[] = $filter;
		}
		
		$movies = VideoLibrary::getMovies($requestParameters);

		// If there is only one item in the result we redirect directly to the 
		// details page
		if (count($movies) == 1)
			$this->redirect(array('details', 'id'=>$movies[0]->movieid));

		$this->registerScripts();
		
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($movies, 'movieid'),
			'movieFilterForm'=>$movieFilterForm));
	}
	
	/**
	 * Shows details and download links for the specified movie
	 * @param int $id the movie ID
	 */
	public function actionDetails($id)
	{
		$movieDetails = VideoLibrary::getMovieDetails((int)$id, array(
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
			throw new CHttpException(404, 'Not found');
		
		// Create a data provider for the actors. We only show one row (first 
		// credited only), hence the 6
		$actorDataProvider = new CArrayDataProvider(
				$movieDetails->cast, array(
			'keyField'=>'name',
			'pagination'=>array('pageSize'=>6)
		));

		$movieLinks = $this->getMovieLinks($movieDetails);
		
		$this->registerScripts();

		$this->render('details', array(
			'details'=>$movieDetails,
			'actorDataProvider'=>$actorDataProvider,
			'movieLinks'=>$movieLinks,
		));
	}
	
	/**
	 * Serves a playlist containing the specified movie's files to the browser
	 * @param int $movieId the movie ID
	 */
	public function actionGetMoviePlaylist($movieId)
	{
		$movieDetails = VideoLibrary::getMovieDetails((int)$movieId, array(
			'file',
			'runtime',
			'title',
			'year'
		));
		
		if ($movieDetails === null)
			throw new CHttpException(404, 'Not found');

		$links = $this->getMovieLinks($movieDetails);
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

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.$name.'.m3u"');

		echo $playlist;
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
			$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', array(
				'path'=>$rawFile));

			$files[] = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);
		}

		return $files;
	}
	
	/**
	 * Returns an array containing all movie names
	 * @return array the names
	 */
	public function getMovieNames()
	{
		$names = array();
		
		foreach (VideoLibrary::getMovies() as $movie)
			$names[] = $movie->label;
		
		return $names;
	}
	
}