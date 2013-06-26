<?php

/**
 * Contains functions common to both movies and TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class VideoLibraryController extends Controller
{
	
	/**
	 * Registers Javascript
	 */
	protected function registerScripts()
	{
		// Image lazy-loader
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl
				.'/js/lazy-load-images.js', CClientScript::POS_END);
		
		// Twitter Typeahead
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl
				.'/js/typeahead.js', CClientScript::POS_END);
	}

}