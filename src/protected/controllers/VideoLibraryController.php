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
		
		// Image lazy-loader
		$cs->registerScriptFile(Yii::app()->baseUrl
				.'/js/jquery.unveil.js', CClientScript::POS_END);
		
		// Load images when they are within 50 pixels of the viewport
		$cs->registerScript(__CLASS__.'_unveil', '
			$(".lazy").unveil(50);
		', CClientScript::POS_READY);
		
		// Twitter Typeahead
		$cs->registerScriptFile(Yii::app()->baseUrl
				.'/js/typeahead.js', CClientScript::POS_END);
	}

}