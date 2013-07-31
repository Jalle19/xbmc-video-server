<?php

/**
 * Implements the abstract methods from RetrieveMediaWidget for movie items
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class RetrieveMovieWidget extends RetrieveMediaWidget
{

	/**
	 * @return mixed the movie playlist URL
	 */
	protected function getPlayListUrl()
	{
		return array('getMoviePlaylist', 'movieId'=>$this->details->movieid);
	}

	/**
	 * @return array the options for the watch button
	 */
	protected function getWatchButtonOptions()
	{
		return array(
			'url'=>$this->getStreamUrl(),
			'class'=>'fontastic-icon-play',
			'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
			'size'=>TbHtml::BUTTON_SIZE_LARGE,
		);
	}

}