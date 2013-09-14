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
		// Retrieve the name of the TV show
		$tvshow = VideoLibrary::getTVShowDetails($this->details->tvshowid, array());
		$episodeLabel = $tvshow->label.' - '.$this->details->label;
		
		return '"'.Yii::app()->user->name.'" downloaded "'.$episodeLabel.'"';
	}

	protected function getPlayListUrl()
	{
		return array('getEpisodePlaylist', 'episodeId'=>$this->details->episodeid);
	}

	protected function getWatchButtonOptions()
	{
		return array(
			'url'=>$this->getStreamUrl(),
			'class'=>'fontastic-icon-play',
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
			'size'=>TbHtml::BUTTON_SIZE_SMALL,
		);
	}

}