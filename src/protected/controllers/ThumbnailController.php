<?php

/**
 * Handles thumbnails
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailController extends Controller
{

	/**
	 * Redirects to a thumbnail URL. This wrapper is used so we don't have to 
	 * generate the thumbnail URLs for all images in a grid immediately on page 
	 * load (instead the URL is determined when the image is loaded through 
	 * this action).
	 * @see Thumbnail
	 * @param string $path the thumbnail path
	 * @param int $size the thumbnail size
	 * @param string $type the thumbnail type
	 */
	public function actionGet($path, $size, $type)
	{
		switch ($type)
		{
			case Thumbnail::TYPE_MOVIE:
				$thumbnail = new ThumbnailMovie($path, $size);
				break;
			case Thumbnail::TYPE_TVSHOW:
				$thumbnail = new ThumbnailTVShow($path, $size);
				break;
			case Thumbnail::TYPE_ACTOR:
				$thumbnail = new ThumbnailActor($path, $size);
				break;
			default:
				throw new CHttpException(400, 'Invalid thumbnail type');
		}

		$this->redirect($thumbnail);
	}

}