<?php

/**
 * Implements the abstract methods from RetrieveMediaWidget for TV show items
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class RetrieveTVShowWidget extends RetrieveMediaWidget
{

	protected function getLogCategory()
	{
		return 'TvShowController';
	}

	protected function getLogMessage()
	{
		return '"'.Yii::app()->user->name.'" downloaded "'.$this->details->showtitle.' - '.$this->details->getDisplayName().'"';
	}

	protected function getPlayListUrl()
	{
		return array('getEpisodePlaylist', 'episodeId'=>$this->details->getId());
	}

	protected function getWatchButtonOptions()
	{
		return array(
			'url'=>$this->getStreamUrl(),
			'class'=>'fa fa-play',
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
			'size'=>TbHtml::BUTTON_SIZE_SMALL,
		);
	}

}