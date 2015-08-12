<?php

/**
 * Factory for creating thumbnail instances
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ThumbnailFactory
{

	const THUMBNAIL_TYPE_ACTOR = 'actor';
	const THUMBNAIL_TYPE_SEASON = 'season';
	const THUMBNAIL_TYPE_VIDEO = 'video';

	/**
	 * @var string[] list of placeholder images per thumbnail type
	 */
	private static $placeholderPaths = array(
		self::THUMBNAIL_TYPE_ACTOR => 'images/placeholder-actor.jpg',
		self::THUMBNAIL_TYPE_SEASON => 'images/placeholder-folder.jpg',
		self::THUMBNAIL_TYPE_VIDEO => 'images/placeholder-video.jpg'
	);

	/**
	 * Creates and returns a thumbnail instance
	 * @param string $imagePath the path to the image
	 * @param int $size the desired thumbnail size
	 * @param string $type the thumbnail type
	 * @return AbstractThumbnail
	 */
	public static function create($imagePath, $size, $type = self::THUMBNAIL_TYPE_VIDEO)
	{
		if (self::isPlaceHolderPath($imagePath))
		{
			$placeholderPath = self::$placeholderPaths[$type];
			return new PlaceholderThumbnail($placeholderPath);
		}
		else
			return new Thumbnail($imagePath, $size);
	}

	/**
	 * Returns whether the specified path points to a placeholder image.
	 * A placeholder path can either be empty or begin with image://placeholder
	 * @param $path the path to check
	 * @return bool
	 */
	private static function isPlaceHolderPath($path)
	{
		return empty($path) ||
				substr($path, 0, strlen('image://placeholder')) === 'image://placeholder';
	}

	/**
	 * Prevent construction
	 */
	private function __construct()
	{
		
	}

}
