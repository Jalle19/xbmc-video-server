<?php

/**
 * Contains functions common to both movies and TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class VideoLibraryController extends Controller
{
	
	const SORT_ORDER_ASCENDING = 'ascending';

	/**
	 * Redirects to the URL returned by getThumbnailUrl(). This wrapper is used 
	 * so we don't have to generate the thumbnail URLs for all images in a grid 
	 * immediately on page load (instead the URL is determined when the image is 
	 * loaded through this action). This is useless unless lazy-loading of 
	 * images is used
	 * @param string $thumbnailPath the VFS path to a thumbnail
	 */
	public function actionGetThumbnail($thumbnailPath)
	{
		$this->redirect($this->getThumbnailUrl($thumbnailPath));
	}

	/**
	 * Transforms the VFS path for a thumbnail to a publicly accessible URL and 
	 * returns it.
	 * @param string $thumbnailPath the VFS path to a thumbnail
	 */
	protected function getThumbnailUrl($thumbnailPath)
	{
		// TODO: Use better place holder image
		if (empty($thumbnailPath))
			return Yii::app()->baseUrl.'/images/blank.png';

		$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', array(
			'path'=>$thumbnailPath));

		return Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);
	}

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
		
		// Twitter Typeahead
		// TODO: Use bower.js
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl
				.'/js/typeahead.js', CClientScript::POS_END);
	}

}