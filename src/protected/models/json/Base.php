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
	 * Returns the name of the first field that ends with "id". This is needed 
	 * because all items from the XBMC API use different identifiers.
	 * @return string the name of the "id" field
	 */
	public function getIdField()
	{
		foreach (array_keys(get_object_vars($this)) as $property)
			if (substr($property, -2) === 'id')
				return $property;
	}
	
	/**
	 * @return the ID of this item
	 */
	public function getId()
	{
		return $this->{$this->getIdField()};
	}

	/**
	 * @return string display name of this item
	 */
	public function getDisplayName()
	{
		return $this->label;
	}

	/**
	 * @return string the artwork for this item
	 */
	public function getArtwork()
	{
		return $this->thumbnail;
	}

}
