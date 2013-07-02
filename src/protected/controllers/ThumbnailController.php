<?php

/**
 * Handles thumbnails
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
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
			case Thumbnail::TYPE_VIDEO:
				$thumbnail = new ThumbnailVideo($path, $size);
				break;
			case Thumbnail::TYPE_ACTOR:
				$thumbnail = new ThumbnailActor($path, $size);
				break;
			default:
				throw new CHttpException(400, 'Invalid thumbnail type');
		}

		// Do a permanent redirect
		$this->redirect($thumbnail, true, 301);
	}

}