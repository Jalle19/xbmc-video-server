<?php

/**
 * Handles TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class TvShowController extends Controller
{

	/**
	 * Lists all TV shows in the library
	 */
	public function actionIndex()
	{
		$properties = array('thumbnail', 'fanart', 'art');
		$tvshows = VideoLibrary::getTVShows(array(
				'properties'=>$properties));
		
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($tvshows, 'tvshowid')));
	}
	
	/**
	 * Displays information about the specified show
	 * @param int $id the show ID
	 * @throws CHttpException if the show could not be found
	 */
	public function actionDetails($id)
	{
		$showDetails = VideoLibrary::getTVShowDetails((int)$id, array(
			'title',
			'genre',
			'year',
			'rating',
			'plot',
			'mpaa',
			'episode',
			'imdbnumber',
			'votes',
			'thumbnail',
			'file',
			'episodeguide',
			'season',
			'art'
		));

		if ($showDetails === null)
			throw new CHttpException(404, 'Not found');

		$this->render('details', array(
			'details'=>$showDetails));
	}

}