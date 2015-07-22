<?php

/**
 * Audio source flag
 *
 * @author Stefan Spühler <git@tuxli.ch>
 * @copyright Copyright &copy; Stefan Spühler 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */

class MediaFlagAudioSource extends MediaFlag
{

	protected function getIcon()
	{
		if (preg_match('/\.MD\.|\.mic\.dubbed\./i', $this->file))
			return 'MD';
		else if (preg_match('/\.LD\.|\.line\.dubbed\./i', $this->file))
			return 'LD';
		else
			return '';
	}

}
