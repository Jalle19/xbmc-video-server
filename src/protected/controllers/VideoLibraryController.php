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
		$cs = Yii::app()->clientScript;
		
		// Twitter Typeahead
		$cs->registerScriptFile(Yii::app()->baseUrl
				.'/js/typeahead.js', CClientScript::POS_END);
	}

}