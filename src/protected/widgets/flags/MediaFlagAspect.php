<?php

/**
 * Aspect ratio flag
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaFlagAspect extends MediaFlagStreamDetails
{

	protected function getIcon()
	{
		$aspect = $this->video->width / $this->video->height;

		// Map minimum aspect ratio to the corresponding icon.
		// Borrowed from https://github.com/xbmc/xbmc/blob/master/xbmc/utils/StreamDetails.cpp
		$map = array(
			'1.3499'=>'50px-1.33',
			'1.5080'=>'50px-1.37',
			'1.8147'=>'50px-1.66',
			'2.0174'=>'50px-1.85',
			'2.2738'=>'50px-2.20',
			'2.3749'=>'50px-2.35',
			'2.4739'=>'50px-2.40',
			'2.6529'=>'50px-2.55');

		foreach ($map as $ratio=> $icon)
			if ($aspect < $ratio)
				return $icon;

		return '50px-2.76';
	}

}