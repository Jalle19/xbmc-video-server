<?php

/**
 * Helper for browser detection
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Browser
{

	/**
	 * @return boolean whether the user browser is a mobile browser (tablets 
	 * not including)
	 */
	public static function isMobile()
	{
		$detector = new Detection\MobileDetect();
		return $detector->isMobile() && !$detector->isTablet();
	}

}
