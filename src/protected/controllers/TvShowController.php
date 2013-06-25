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
		$tvshows = VideoLibrary::getTVShows(array(
				'properties'=>array('thumbnail')));

		$this->registerScripts();
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($tvshows, 'tvshowid')));
	}

}