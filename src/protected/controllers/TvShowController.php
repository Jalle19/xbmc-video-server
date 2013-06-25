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
		$properties = array('thumbnail', 'fanart', 'art');
		$tvshows = VideoLibrary::getTVShows(array(
				'properties'=>$properties));
		
		$this->registerScripts();
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($tvshows, 'tvshowid')));
	}

}