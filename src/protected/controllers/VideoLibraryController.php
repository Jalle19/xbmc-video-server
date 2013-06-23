<?php

/**
 * Contains functions common to both movies and TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class VideoLibraryController extends Controller
{

	/**
	 * Sorts the specified result in place alphabetically. This can be used to 
	 * fix XBMC's invalid sort position of certain movies.
	 * @param array $results result set (array of objects)
	 */
	protected function sortResults(&$results)
	{
		usort($results, function($a, $b) {
			return strcmp($a->label, $b->label);
		});
	}

	/**
	 * Registers Javascript
	 */
	protected function registerScripts()
	{
		// Image lazy-loader
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl
				.'/js/lazy-load-images.js', CClientScript::POS_END);
	}

}