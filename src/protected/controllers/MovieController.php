<?php

/**
 * Handles movie-related actions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MovieController extends Controller
{

	/**
	 * Lists all movies in the library, optionally filtered
	 */
	public function actionIndex()
	{
		// Get filter properties
		$movieFilterForm = new MovieFilterForm();
		$nativeFilters = array();

		if (isset($_GET['MovieFilterForm']))
		{
			$movieFilterForm->attributes = $_GET['MovieFilterForm'];

			if ($movieFilterForm->validate())
			{
				// Include partial matches on movie title
				$nativeFilters['title'] = array(
					'operator'=>'contains',
					'value'=>$movieFilterForm->name);
				
				$nativeFilters['genre'] = array(
					'operator'=>'is',
					'value'=>$movieFilterForm->genre);
				
				$nativeFilters['year'] = array(
					'operator'=>'is',
					'value'=>$movieFilterForm->year);
				
				$quality = $movieFilterForm->quality;

				// SD means anything less than 720p
				if ($quality == MovieFilterForm::QUALITY_SD)
				{
					$nativeFilters['videoresolution'] = array(
						'operator'=>'lessthan',
						'value'=>(string)MovieFilterForm::QUALITY_720);
				}
				else
				{
					$nativeFilters['videoresolution'] = array(
						'operator'=>'is',
						'value'=>$quality);
				}
			}
		}

		// Start building the request parameters
		$requestParameters = array();
		
		foreach ($nativeFilters as $field => $options)
		{
			if (empty($options['value']))
				continue;

			$filter = new stdClass();
			$filter->field = $field;
			$filter->operator = $options['operator'];
			$filter->value = $options['value'];

			if (!isset($requestParameters['filter']))
				$requestParameters['filter'] = new stdClass();

			$requestParameters['filter']->and[] = $filter;
		}
		
		$movies = VideoLibrary::getMovies($requestParameters);

		// If there is only one item in the result we redirect directly to the 
		// details page
		if (count($movies) == 1)
			$this->redirect(array('details', 'id'=>$movies[0]->movieid));

		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($movies, 'movieid'),
			'movieFilterForm'=>$movieFilterForm));
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
		$movieDetails = VideoLibrary::getMovieDetails((int)$movieId, array(
			'file',
			'runtime',
			'title',
			'year'
		));
		
		if ($movieDetails === null)
			throw new CHttpException(404, 'Not found');

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

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.$name.'.m3u"');

		echo $playlist;
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