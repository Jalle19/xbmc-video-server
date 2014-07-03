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
	 * Disable all filters (including authentication) since the generate action 
	 * may be called from e.g. a media player which naturally isn't 
	 * authenticated
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array();
	}

	/**
	 * Generates a thumbnail for the specified image path and size, then serves 
	 * it to the browser. The next time the same thumbnail is rendered its URL 
	 * will point to the generated image instead of this action.
	 * @see Thumbnail
	 * @param string $path the thumbnail path
	 * @param int $size the thumbnail size
	 */
	public function actionGenerate($path, $size)
	{
		$thumbnail = new Thumbnail($path, $size);
		$thumbnail->generate();
		
		$path = $thumbnail->getPath();
		header('Content-Type: '.CFileHelper::getMimeType($path));
		readfile($path);
		exit;
	}

}