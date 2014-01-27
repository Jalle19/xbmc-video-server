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

		// Borrowed from https://github.com/xbmc/xbmc/blob/master/xbmc/utils/StreamDetails.cpp
		if ($aspect < 1.3499)
			return '50px-1.33';
		else if ($aspect < 1.5080)
			return '50px-1.37';
		else if ($aspect < 1.8147)
			return '50px-1.66';
		else if ($aspect < 2.0174)
			return '50px-1.85';
		else if ($aspect < 2.2738)
			return '50px-2.20';
		else if ($aspect < 2.3749)
			return '50px-2.35';
		else if ($aspect < 2.4739)
			return '50px-2.40';
		else if ($aspect < 2.6529)
			return '50px-2.55';

		return '50px-2.76';
	}

}