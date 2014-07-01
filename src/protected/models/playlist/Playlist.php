<?php

use Behat\Transliterator\Transliterator;

/**
 * Represents a generic playlist
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class Playlist
{

	const TYPE_M3U = 'm3u';

	/**
	 * @var PlaylistItem[] the playlist items
	 */
	protected $_items = array();

	/**
	 * @var string the playlist filename
	 */
	private $_fileName;

	/**
	 * @return string the MIME type of the playlist
	 */
	abstract public function getMIMEType();

	/**
	 * @return string the actual playlist data
	 */
	abstract public function __toString();

	/**
	 * Class constructor
	 * @param string the playlist filename
	 */
	public function __construct($fileName)
	{
		$this->_fileName = $fileName;
	}

	/**
	 * Adds an item to the playlist
	 * @param PlaylistItem $item the item
	 */
	public function addItem($item)
	{
		$this->_items[] = $item;
	}

	/**
	 * Returns the filename sanitized to minimize issues with various platforms
	 * @return string the sanitized filename
	 */
	public function getSanitizedFileName()
	{
		return Transliterator::transliterate($this->_fileName);
	}

}
