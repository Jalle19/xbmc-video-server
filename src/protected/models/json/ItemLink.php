<?php

/**
 * Represents a link to a media item
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ItemLink
{

	/**
	 * @var string the item name
	 */
	public $name;

	/**
	 * @var string the item URL
	 */
	public $url;

	/**
	 * @var Media the media item it represents
	 */
	public $media;

	/**
	 * Class constructor
	 * @param string $name
	 * @param string $url
	 * @param Media $media
	 */
	public function __construct($name, $url, $media)
	{
		$this->name = $name;
		$this->url = $url;
		$this->media = $media;
	}

}
