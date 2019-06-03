<?php

/**
 * Video source flag
 *
 * @author Stefan Spühler <git@tuxli.ch>
 * @copyright Copyright &copy; Stefan Spühler 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */

class MediaFlagVideoSource extends MediaFlag
{

	protected function getIcon()
	{
		if (preg_match('/\.TS\.|\.TELESYNC\./i', $this->file))
			return 'TS';
		else if (preg_match('/\.CAM\./i', $this->file))
			return 'CAM';
		else if (preg_match('/\.DVDSCR\.|\.SCREENER\./i', $this->file))
			return 'TS';
		else if (preg_match('/\.WEBRip\./i', $this->file))
			return 'WEBRIP';

		return false;
	}

}
