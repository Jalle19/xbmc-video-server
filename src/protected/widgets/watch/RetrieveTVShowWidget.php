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

	protected function getLogMessage()
	{
		/* @var $episode Episode */
		$episode = $this->details;
		
		return '"'.Yii::app()->user->name.'" downloaded "'.$episode->showtitle.' - '.$episode->getDisplayName().'"';
	}

	protected function getPlayListAction()
	{
		return array('getEpisodePlaylist');
	}

}