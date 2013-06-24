<?php

/**
 * Video resolution flag
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MediaFlagResolution extends MediaFlag
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
		else
			return '50px-1080_n';
	}

}