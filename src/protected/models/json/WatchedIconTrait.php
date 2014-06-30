<?php

/**
 * Trait for API items that contain a "playcount" property. If provides a 
 * method for rendering an icon which represents that the item is marked as 
 * watched in the library. 
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
trait WatchedIconTrait
{

	/**
	 * @var int
	 */
	public $playcount;

	/**
	 * @return string the HTML for a watched icon. An empty string is returned 
	 * if the item has not been watched
	 */
	public function getWatchedIcon()
	{
		if ($this->playcount > 0)
			return CHtml::tag('i', array('class'=>'watched-icon fa fa-check'), '');

		return '';
	}

}
