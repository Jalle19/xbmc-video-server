<?php

/**
 * Handles thumbnails
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailController extends Controller
{

	/**
	 * Redirects to the URL returned by Thumbnail::get(). This wrapper is used 
	 * so we don't have to generate the thumbnail URLs for all images in a grid 
	 * immediately on page load (instead the URL is determined when the image is 
	 * loaded through this action).
	 * @param string $path the VFS path to a thumbnail
	 */
	public function actionGet($path, $size)
	{
		$this->redirect(Thumbnail::get($path, $size));
	}

}