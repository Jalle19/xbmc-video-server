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

	const SORT_ORDER_ASCENDING = 'ascending';

	/**
	 * Returns a list of movies
	 * @param array $params request parameters
	 * @return stdClass[] the movies
	 */
	public static function getMovies($params = array())
	{
		self::addDefaultSort($params);
		self::addDefaultProperties($params);
		
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovies', $params);

		if (isset($response->result->movies))
			$movies = $response->result->movies;
		else
			$movies = array();

		return $movies;
	}
	
	/**
	 * Returns the recently added movies
	 * @return stdClass[] the movies
	 */
	public static function getRecentlyAddedMovies($params = array())
	{
		self::addDefaultProperties($params);

		// The grid shows six items per row, we don't want the 25th item to be 
		// lonely
		$params['limits'] = new stdClass();
		$params['limits']->end = 24;

		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetRecentlyAddedMovies', $params);

		if (isset($response->result->movies))
			$movies = $response->result->movies;
		else
			$movies = array();

		return $movies;
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

		if (isset($response->result->moviedetails))
			return $response->result->moviedetails;
		else
			return null;
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
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetTVShows', $params);

		if (isset($response->result->tvshows))
			$tvshows = $response->result->tvshows;
		else
			$tvshows = array();

		return $tvshows;
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

		if (isset($response->result->tvshowdetails))
			return $response->result->tvshowdetails;
		else
			return null;
	}
	
	/**
	 * Returns the season for the specified TV show
	 * @param int $tvshowId the TV show ID
	 * @return stdClass[] the seasons
	 */
	public static function getSeasons($tvshowId)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetSeasons', array(
			'tvshowid'=>(int)$tvshowId, 'properties'=>array('season')));
		
		if (isset($response->result->seasons))
			return $response->result->seasons;
		else
			return array();
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

		return $response->result->episodes;
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

		if (isset($response->result->episodedetails))
			return $response->result->episodedetails;
		else
			return null;
	}
	
	/**
	 * Returns an array with the download links for a video. It takes a file
	 * string from e.g. VideoLibrary.GetVideoDetails as parameter.
	 * @param string $file a file string returned from the API
	 * @return array the download links
	 * @throws CHttpException if the files have been deleted from disk while
	 * the item is still in the library
	 */
	public static function getVideoLinks($file)
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
			// disc but the movie still exists in the library this call will
			// fail. We rethrow an exception with a more descriptive error
			try
			{
				$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', array(
				'path'=>$rawFile));
			}
			catch(CHttpException $e)
			{
				unset($e); // silence IDE warnings
				throw new CHttpException(404, 'This file has been deleted');
			}

			$files[] = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);
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
				'method'=>'label');
		}
	}
	
	/**
	 * Adds a default set of properties for movie/show requests
	 * @param array $params
	 */
	private static function addDefaultProperties(&$params)
	{
		if (!isset($params['properties']))
			$params['properties'] = array('thumbnail');
	}

}