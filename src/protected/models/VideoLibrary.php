<?php

/**
 * Helper class for fetching library-related data
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class VideoLibrary
{

	const GENRE_TYPE_MOVIE = 'movie';
	const GENRE_TYPE_TVSHOW = 'tvshow';
	const SORT_ORDER_ASCENDING = 'ascending';
	
	/**
	 * @var string[] default properties for movies
	 */
	private static $_defaultMovieProperties = array(
		'year', 'genre', 'thumbnail', 'rating', 'runtime', 'playcount'
	);

	/**
	 * @var string[] default properties for TV shows
	 */
	private static $_defaultTVShowProperties = array(
		'year', 'genre', 'thumbnail', 'art', 'playcount',
	);

	/**
	 * @var string[] default properties for episodes
	 */
	private static $_defaultEpisodeProperties = array(
		'plot', 'runtime', 'season', 'episode', 'streamdetails', 'thumbnail',
		'file', 'title', 'tvshowid', 'showtitle', 'playcount',
	);
	
	/**
	 * Returns all genres available for the specified media type (default to 
	 * movie genres).
	 * @param string $type the media type (see class constants)
	 * @return array the list of genres
	 */
	public static function getGenres($type = self::GENRE_TYPE_MOVIE)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetGenres', array(
			'type'=>$type, 'sort'=>array(
				'order'=>self::SORT_ORDER_ASCENDING, 'method'=>'label')));

		return self::normalizeResponse($response, 'genres', array());
	}
	
	/**
	 * Returns a list of all actors. 
	 * @param string $mediaType the media type to fetch actors for (movies or 
	 * TV shows)
	 * @return Actor[] the actors
	 */
	public static function getActors($mediaType)
	{
		// Fetch the list of all works
		$works = array();
		
		if ($mediaType === Actor::MEDIA_TYPE_MOVIE)
			$works = VideoLibrary::getMovies(array('properties'=>array('cast')));
		elseif ($mediaType === Actor::MEDIA_TYPE_TVSHOW)
			$works = VideoLibrary::getTVShows(array('properties'=>array('cast')));

		// Build a list of all unique actors
		$actors = array();

		foreach ($works as $work)
			$actors = array_merge($actors, $work->cast);

		// array_unique compares by string
		return array_unique($actors);
	}
	
	/**
	 * @return array list of all movie directors
	 */
	public static function getDirectors()
	{
		// Fetch the list of all movies
		$movies = VideoLibrary::getMovies(array('properties'=>array('director')));
		$directors = array();

		foreach ($movies as $movie)
			$directors = array_merge($directors, $movie->director);

		// We want this to be an array with just values, the keys don't matter
		return array_values(array_unique($directors));
	}

	/**
	 * Returns a list of movies
	 * @param array $params request parameters
	 * @return Movie[] the movies
	 */
	public static function getMovies($params = array())
	{
		self::addDefaultSort($params, 'sorttitle');
		self::ensureProperties($params, self::$_defaultMovieProperties);
		
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovies', $params);

		return self::normalizeResponse($response, 'movies', array(), new Movie());
	}
	
	/**
	 * Returns the recently added movies
	 * @return Movie[] the movies
	 */
	public static function getRecentlyAddedMovies($params = array())
	{
		self::ensureProperties($params, self::$_defaultMovieProperties);

		// The grid shows six items per row, we don't want the 25th item to be 
		// lonely
		$params['limits'] = new stdClass();
		$params['limits']->end = 24;

		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetRecentlyAddedMovies', $params);

		return self::normalizeResponse($response, 'movies', array(), new Movie());
	}

	/**
	 * Returns details about the specified movie. The properties array 
	 * specifies which movie attributes to return.
	 * @param int $movieId the movie ID
	 * @param string[] $properties the properties to include in the result
	 * @return Movie the movie details
	 */
	public static function getMovieDetails($movieId, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovieDetails', array(
			'movieid'=>(int)$movieId,
			'properties'=>$properties));

		return self::normalizeResponse($response, 'moviedetails', null, new Movie());
	}
	
	/**
	 * Returns a list of TV shows. If no sort mechanism is specified in 
	 * @params the result will be sorted alphabetically by title.
	 * @param array $params request parameters
	 * @return TVShow[] the TV shows
	 */
	public static function getTVShows($params = array())
	{
		self::addDefaultSort($params);
		self::ensureProperties($params, self::$_defaultTVShowProperties);
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetTVShows', $params);

		return self::normalizeResponse($response, 'tvshows', array(), new TVShow());
	}
	
	/**
	 * Returns the recently added episodes
	 * @param array $params (optional) request parameters
	 * @return Episode[] the recently added episodes
	 */
	public static function getRecentlyAddedEpisodes($params = array())
	{
		self::ensureProperties($params, self::$_defaultEpisodeProperties);
		
		$response = Yii::app()->xbmc->performRequest(
				'VideoLibrary.GetRecentlyAddedEpisodes', $params);

		return self::normalizeResponse($response, 'episodes', array(), new Episode());
	}
	
	/**
	 * Returns details about the specified TV show. The properties array 
	 * specifies which attributes to return.
	 * @param int $tvshowId the show ID
	 * @param string[] $properties the properties to include in the result
	 * @return TVShow the show details
	 * @throws CHttpException if the show was not found
	 */
	public static function getTVShowDetails($tvshowId, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetTVShowDetails', array(
			'tvshowid'=>(int)$tvshowId,
			'properties'=>$properties));

		return self::normalizeResponse($response, 'tvshowdetails', null, new TVShow());
	}
	
	/**
	 * Returns the season for the specified TV show, sorted alphabetically
	 * @param int $tvshowId the TV show ID
	 * @return Season[] the seasons
	 */
	public static function getSeasons($tvshowId)
	{
		$params = array('tvshowid'=>(int)$tvshowId, 
			'properties'=>array('season', 'art', 'episode', 'showtitle', 'tvshowid', 'playcount'));
		
		self::addDefaultSort($params);
		
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetSeasons', 
				$params);
		
		return self::normalizeResponse($response, 'seasons', array(), new Season());
	}
	
	/**
	 * Returns information about a single season
	 * @param int $tvshowId the tv show ID
	 * @param int $season the season number
	 * @return Season the season details
	 * @throws PageNotFoundException if the season is not found
	 */
	public static function getSeasonDetails($tvshowId, $season)
	{
		$seasons = self::getSeasons($tvshowId);

		foreach ($seasons as $seasonObj)
			if ($seasonObj->season == $season)
				return $seasonObj;

		throw new PageNotFoundException();
	}

	/**
	 * Returns the episodes for the specified show and season
	 * @param int $tvshowId the TV show ID
	 * @param int $season the season number
	 * @param array $properties properties to include in the results. Defaults 
	 * to null, meaning the default properties for episodes will be included
	 * @return Episode[] the episodes
	 */
	public static function getEpisodes($tvshowId, $season, $properties = null)
	{
		$params = array(
			'tvshowid'=>(int)$tvshowId, 
			'season'=>(int)$season);
		
		self::addDefaultSort($params);
		
		if ($properties === null)
			self::ensureProperties($params, self::$_defaultEpisodeProperties);
		else
			$params['properties'] = $properties;
		
		$response = Yii::app()->xbmc->performRequest(
				'VideoLibrary.GetEpisodes', $params);

		return self::normalizeResponse($response, 'episodes', array(), new Episode());
	}
	
	/**
	 * Returns details about the specified TV show episode
	 * @param int $episodeId the episode ID
	 * @param string[] $properties the properties to include in the result
	 * @return Episode the episode details
	 */
	public static function getEpisodeDetails($episodeId, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetEpisodeDetails', array(
			'episodeid'=>(int)$episodeId,
			'properties'=>$properties));

		return self::normalizeResponse($response, 'episodedetails', null, new Episode());
	}
	
	/**
	 * Returns an array with the download links for a video. It takes a file
	 * string from e.g. VideoLibrary.GetVideoDetails as parameter.
	 * @param string $file a file string returned from the API
	 * @param boolean $omitCredentials whether URL credentials should be 
	 * omitted
	 * @return array the download links
	 * @throws CHttpException if the files have been deleted from disk while
	 * the item is still in the library
	 */
	public static function getVideoLinks($file, $omitCredentials = false)
	{
		$rawFiles = array();
		$files = array();

		// Check for stack files
		if (strpos($file, 'stack://') !== false)
			$rawFiles = preg_split('/ , /i', $file);
		else
			$rawFiles[] = $file;

		foreach ($rawFiles as $rawFile)
		{
			// Remove stack://
			if (substr($rawFile, 0, 8) === 'stack://')
				$rawFile = substr($rawFile, 8);

			// Create the URL to the file. If the file has been deleted from
			// disc but the movie still exists in the library the API call 
			// throws an exception. We just skip this file if that's the case.
			try
			{
				$response = Yii::app()->xbmc->performRequest(
						'Files.PrepareDownload', array('path'=>$rawFile));
				
				$files[] = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path, $omitCredentials);
			}
			catch(CHttpException $e)
			{
				$files[] = false;
			}
		}

		return $files;
	}
	
	/**
	 * Adds the specified sort method to the request parameters. The sort 
	 * method defaults to "label":
	 * @param array $params the parameters
	 * @param string $sortMethod the sort method to use
	 */
	private static function addDefaultSort(&$params, $sortMethod = 'label')
	{
		if (!isset($params['sort']))
		{
			$params['sort'] = array(
				'order'=>self::SORT_ORDER_ASCENDING,
				'ignorearticle'=>Setting::getBoolean('ignoreArticle'),
				'method'=>$sortMethod);
		}
	}
	
	/**
	 * Ensures that the specified properties are specified in 
	 * params['properties']. If properties have already been defined they 
	 * remain untouched
	 * @param array $params the parameters
	 * @param string[] $properties the properties
	 */
	private static function ensureProperties(&$params, $properties)
	{
		if (!isset($params['properties']))
			$params['properties'] = $properties;
	}

	/**
	 * Returns the $resultSet from the $response object, or $defaultValue if 
	 * the result set is not available
	 * @param stdClass $response an API response object
	 * @param string $resultSet the name of the result set
	 * @param mixed $defaultValue the value to return if the result set is not 
	 * available
	 * @param mixed an instance of the class the results should be mapped to, or 
	 * null to use stdClass
	 * @return mixed the normalized response
	 */
	private static function normalizeResponse($response, $resultSet, $defaultValue, $targetObject = null)
	{
		if (isset($response->result->{$resultSet}))
		{
			$result = $response->result->{$resultSet};

			if ($targetObject !== null)
			{
				$mapper = new JsonMapper();
				
				if (is_array($result))
					return $mapper->mapArray($result, new ArrayObject(), $targetObject)->getArrayCopy();
				elseif (is_object($result))
					return $mapper->map($result, $targetObject);
			}

			return $response->result->{$resultSet};
		}
		else
			return $defaultValue;
	}

}