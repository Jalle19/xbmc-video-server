<?php

/**
 * Helper class for determining media information, such as what codecs it uses 
 * etc.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaInfoHelper
{

	/**
	 * Checks whether the stream details contain the necessary media info
	 * @return boolean
	 */
	public static function hasMediaInfo($streamDetails)
	{
		return count($streamDetails->audio) !== 0 &&
			   count($streamDetails->video) !== 0;
	}

}
