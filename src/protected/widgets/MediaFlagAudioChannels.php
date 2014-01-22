<?php

/**
 * Audio channels count flag
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaFlagAudioChannels extends MediaFlagStreamDetails
{

	protected function getIcon()
	{
		$channels = $this->audio->channels;

		if ($channels == 10)
			return '50px-10';
		elseif ($channels == 8)
			return '50px-8';
		if ($channels == 6)
			return '50px-6';
		if ($channels == 2)
			return '50px-2';
		if ($channels == 1)
			return '50px-1';
		else
			return false;
	}

}