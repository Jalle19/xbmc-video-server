<?php

/**
 * Base class for thumbnails
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class AbstractThumbnail
{

	/**
	 * @var string the thumbnail path
	 */
	protected $_path;

	/**
	 * @return string the URL to the thumbnail
	 */
	abstract public function getUrl();

	/**
	 * Class constructor
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->_path = $path;
	}

	/**
	 * @return string the thumbnail URL
	 */
	public function __toString()
	{
		return $this->getUrl();
	}

}
