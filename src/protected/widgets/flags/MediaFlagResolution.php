<?php

/**
 * Video resolution flag
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaFlagResolution extends MediaFlagStreamDetails
{

	protected function getIcon()
	{
		$width = $this->video->width;

		if ($width == 0)
			return false;
		elseif ($width < 961)
			return '50px-480';
		elseif ($width < 1281)
			return '50px-720';
		else if ($width < 3500) // highly unscientific
			return '50px-1080_n';
		else
			return '4K';
	}

}