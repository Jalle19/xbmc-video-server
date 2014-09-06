<?php

/**
 * Watch modal for episodes
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * TODO: Rename to WatchEpisodeModal (and the inner widget accordingly)
 */
class WatchTVShowModal extends WatchMediaModal
{

	protected function getInnerWidgetClass()
	{
		return 'RetrieveTVShowWidget';
	}

	protected function getWatchButtonOptions()
	{
		return array(
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
			'size'=>TbHtml::BUTTON_SIZE_SMALL,
		);
	}

}
