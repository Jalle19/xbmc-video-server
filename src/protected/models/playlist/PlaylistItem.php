<?php

/**
 * Represents a playlist item
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PlaylistItem
{

	/**
	 * @var string the item title
	 */
	public $title;

	/**
	 * @var string the item location (the URL)
	 */
	public $location;

	/**
	 * @var int the item runtime (in seconds)
	 */
	public $runtime;

	/**
	 * @var string the URL to the item image
	 */
	public $image;
	
	/**
	 * Class constructor. It takes the media item it represents as a parameter 
	 * so it can eventually deduce extra information from it.
	 * @param Media $media
	 */
	public function __construct($media)
	{
		// Set runtime
		$this->runtime = $media->runtime;
		
		// Generate a link to the artwork
		$thumbnail = new Thumbnail($media->getArtwork(), Thumbnail::SIZE_LARGE);
		$this->image = Yii::app()->request->hostInfo.$thumbnail;
	}

}
