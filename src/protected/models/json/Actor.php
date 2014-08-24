<?php

/**
 * Represents an actor
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Actor
{

	const MEDIA_TYPE_MOVIE = 'movie';
	const MEDIA_TYPE_TVSHOW = 'tvshow';

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $role;

	/**
	 * @var string
	 */
	public $thumbnail;

	/**
	 * Converts the object to a string. This is necessary in order to filter 
	 * duplicate actors with functions such as array_unique()
	 * @return string the string representation of the actor
	 */
	public function __toString()
	{
		return $this->name;
	}

}
