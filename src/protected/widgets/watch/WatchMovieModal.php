<?php

/**
 * Watch modal for movies
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class WatchMovieModal extends WatchMediaModal
{

	protected function getInnerWidgetClass()
	{
		return 'RetrieveMovieWidget';
	}

	protected function getWatchButtonOptions()
	{
		return array(
			'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
			'size'=>TbHtml::BUTTON_SIZE_LARGE,
		);
	}

}
