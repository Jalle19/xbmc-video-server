<?php

/**
 * Base class for all items fetched through the API.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class Base extends CComponent
{

	/**
	 * @var string
	 */
	public $label;

	/**
	 * @var string
	 */
	public $thumbnail;

	/**
	 * @return the ID of this item
	 */
	abstract public function getId();

	/**
	 * @return the display name of this item
	 */
	abstract public function getDisplayName();

	/**
	 * @return string the artwork for this item
	 */
	public function getArtwork()
	{
		return $this->thumbnail;
	}

}
