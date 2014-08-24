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

}
