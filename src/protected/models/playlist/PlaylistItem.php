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
	 * Class constructor
	 * @param ItemLink $itemLink
	 */
	public function __construct($itemLink)
	{
		$this->title = $itemLink->name;
		$this->location = $itemLink->url;
		
		$media = $itemLink->media;
		
		// Set runtime
		$this->runtime = $media->runtime;
		
		// Generate a link to the artwork
		$thumbnail = ThumbnailFactory::create($media->getArtwork(), Thumbnail::SIZE_LARGE);
		$this->image = Yii::app()->request->hostInfo.$thumbnail;
	}

}
