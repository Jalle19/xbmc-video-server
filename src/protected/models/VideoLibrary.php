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
		'year', 'genre', 'thumbnail', 'rating', 'runtime'
	);

	/**
	 * @var string[] default properties for TV shows
	 */
	private static $_defaultTVShowProperties = array(
		'year', 'genre', 'thumbnail'
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
	 * Returns a list of movies
	 * @param array $params request parameters
	 * @return stdClass[] the movies
	 */
	public static function getMovies($params = array())
	{
		self::addDefaultSort($params);
		self::ensureProperties($params, self::$_defaultMovieProperties);
		
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovies', $params);

		return self::normalizeResponse($response, 'movies', array());
	}
	
	/**
	 * Returns the recently added movies
	 * @return stdClass[] the movies
	 */
	public static function getRecentlyAddedMovies($params = array())
	{
		self::ensureProperties($params, self::$_defaultMovieProperties);

		// The grid shows six items per row, we don't want the 25th item to be 
		// lonely
		$params['limits'] = new stdClass();
		$params['limits']->end = 24;

		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetRecentlyAddedMovies', $params);

		return self::normalizeResponse($response, 'movies', array());
	}

	/**
	 * Returns details about the specified movie. The properties array 
	 * specifies which movie attributes to return.
	 * @param int $movieId the movie ID
	 * @param array $properties the properties to include in the result
	 * @return mixed the movie details or null if the movie was not found
	 */
	public static function getMovieDetails($movieId, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovieDetails', array(
			'movieid'=>(int)$movieId,
			'properties'=>$properties));

		return self::normalizeResponse($response, 'moviedetails', null);
	}
	
	/**
	 * Returns a list of TV shows. If no sort mechanism is specified in 
	 * @params the result will be sorted alphabetically by title.
	 * @param array $params request parameters
	 * @return stdClass[] the TV shows
	 */
	public static function getTVShows($params = array())
	{
		self::addDefaultSort($params);
		self::ensureProperties($params, self::$_defaultTVShowProperties);
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetTVShows', $params);

		return self::normalizeResponse($response, 'tvshows', array());
	}
	
	/**
	 * Returns the recently added episodes
	 * @param array $params (optional) request parameters
	 * @return stdClass[] the recently added episodes
	 */
	public static function getRecentlyAddedEpisodes($params = array())
	{
		$params['properties'] = array(
			'title',
			'plot',
			'runtime',
			'season',
			'episode',
			'streamdetails',
			'thumbnail',
			'file',
			'tvshowid'
		);

		$response = Yii::app()->xbmc->performRequest(
				'VideoLibrary.GetRecentlyAddedEpisodes', $params);

		return self::normalizeResponse($response, 'episodes', array());
	}
	
	/**
	 * Returns details about the specified TV show. The properties array 
	 * specifies which attributes to return.
	 * @param int $tvshowId the show ID
	 * @param array $properties the properties to include in the result
	 * @return mixed the show details or null if the show was not found
	 */
	public static function getTVShowDetails($tvshowId, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetTVShowDetails', array(
			'tvshowid'=>(int)$tvshowId,
			'properties'=>$properties));

		return self::normalizeResponse($response, 'tvshowdetails', null);
	}
	
	/**
	 * Returns the season for the specified TV show, sorted alphabetically
	 * @param int $tvshowId the TV show ID
	 * @return stdClass[] the seasons
	 */
	public static function getSeasons($tvshowId)
	{
		$params = array('tvshowid'=>(int)$tvshowId, 
			'properties'=>array('season'));
		
		self::addDefaultSort($params);
		
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetSeasons', 
				$params);
		
		return self::normalizeResponse($response, 'seasons', array());
	}
	
	/**
	 * Returns the episodes for the specified show and season
	 * @param int $tvshowId the TV show ID
	 * @param int $season the season number
	 * @return stdClass[] the episodes
	 */
	public static function getEpisodes($tvshowId, $season, $properties)
	{
		$params = array(
			'tvshowid'=>(int)$tvshowId, 
			'season'=>(int)$season,
			'properties'=>$properties);
		
		self::addDefaultSort($params);
		
		$response = Yii::app()->xbmc->performRequest(
				'VideoLibrary.GetEpisodes', $params);

		return self::normalizeResponse($response, 'episodes', array());
	}
	
	/**
	 * Returns details about the specified TV show episode
	 * @param int $episodeId the episode ID
	 * @param array $properties the properties to include in the result
	 * @return mixed the episode details or null if the episode was not found
	 */
	public static function getEpisodeDetails($episodeId, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetEpisodeDetails', array(
			'episodeid'=>(int)$episodeId,
			'properties'=>$properties));

		return self::normalizeResponse($response, 'episodedetails', null);
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
	 * Returns a string based on season and episode number, e.g. 1x05.
	 * @param int $season the season
	 * @param int $episode the episode
	 */
	public static function getEpisodeString($season, $episode)
	{
		return $season.'x'.str_pad($episode, 2, '0', STR_PAD_LEFT);
	}
	
	/**
	 * Adds a default sorting method to the specified parameters
	 * @param array $params the parameters
	 */
	private static function addDefaultSort(&$params)
	{
		if (!isset($params['sort']))
		{
			$params['sort'] = array(
				'order'=>self::SORT_ORDER_ASCENDING,
				'ignorearticle'=>(boolean)Setting::getValue('ignoreArticle'),
				'method'=>'label');
		}
	}
	
	/**
	 * Ensures that the specified properties are specified in 
	 * params['properties']
	 * @param array $params the parameters
	 * @param array $properties the properties
	 */
	private static function ensureProperties(&$params, $properties)
	{
		if (!isset($params['properties']))
			$params['properties'] = array();

		$params['properties'] = array_values(array_unique(
				array_merge($params['properties'], $properties)));
	}

	/**
	 * Returns the $resultSet from the $response object, or $defaultValue if 
	 * the result set is not available
	 * @param stdClass $response an API response object
	 * @param string $resultSet the name of the result set
	 * @param mixed $defaultValue the value to return if the result set is not 
	 * available
	 * @return mixed the normalized response
	 */
	private static function normalizeResponse($response, $resultSet, $defaultValue)
	{
		if (isset($response->result->{$resultSet}))
			return $response->result->{$resultSet};
		else
			return $defaultValue;
	}

}