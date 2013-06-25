<?php

/**
 * Handles TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class TvShowController extends VideoLibraryController
{

	/**
	 * Lists all TV shows in the library
	 */
	public function actionIndex()
	{
		// Get the list of movies along with their thumbnails
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetTVShows', array(
			'properties'=>array('thumbnail')));

		$tvshows = $response->result->tvshows;
		$this->sortResults($tvshows);
		
		$this->registerScripts();
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($tvshows, 'tvshowid')));
	}

}