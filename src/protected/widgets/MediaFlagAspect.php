<?php

/**
 * Aspect ratio flag
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MediaFlagAspect extends MediaFlag
{

	protected function getIcon()
	{
		$aspect = $this->video->width / $this->video->height;

		if ($aspect > 2.3)
			return '50px-2.35';
		elseif ($aspect > 2)
			return '50px-2.20';
		elseif ($aspect > 1.5)
			return '50px-1.66';
		elseif ($aspect > 1)
			return '50px-1.33';
		else
			return false;
	}

}