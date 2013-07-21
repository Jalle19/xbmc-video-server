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
	 * Generates a thumbnail for the specified image path and size then does a 
	 * temporary redirect to the generated file.
	 * @see Thumbnail
	 * @param string $path the thumbnail path
	 * @param int $size the thumbnail size
	 */
	public function actionGenerate($path, $size)
	{
		$thumbnail = new Thumbnail($path, $size);
		$thumbnail->generate();

		$this->redirect($thumbnail);
	}

}