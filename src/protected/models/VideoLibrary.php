<?php

/**
 * Helper class for fetching library-related data
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class VideoLibrary
{

	const SORT_ORDER_ASCENDING = 'ascending';

	/**
	 * Returns a list of movies. If no sort mechanism is specified in 
	 * @params the result will be sorted alphabetically by title.
	 * @param array $params request parameters
	 * @return stdClass[] the movies
	 */
	public static function getMovies($params = array())
	{
		self::addDefaultSort($params);
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovies', $params);

		if (isset($response->result->movies))
			$movies = $response->result->movies;
		else
			$movies = array();

		return $movies;
	}

	/**
	 * Returns details about the specified movie. The properties array 
	 * specifies which movie attributes to return.
	 * @param int $id the movie ID
	 * @param array $properties the properties to include in the result
	 * @return mixed the movie details or null if the movie was not found
	 */
	public static function getMovieDetails($id, $properties)
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetMovieDetails', array(
			'movieid'=>(int)$id,
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

}