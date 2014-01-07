<?php

/**
 * Implements the abstract methods from RetrieveMediaWidget for movie items
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class playerwidget extends PlayerIt
{
	
	protected function getLogCategory()
	{
		return 'MovieController';
	}

	protected function getLogMessage()
	{
		return '"'.Yii::app()->user->name.'" downloaded "'.$this->details->title.'"';
	}

	protected function getPlayListUrl()
	{
		return array('getMoviePlaylist', 'movieId'=>$this->details->movieid);
	}

	protected function getWatchButtonOptions()
	{
		return array(
			'url'=>$this->getStreamUrl(),
		
		);
	}

}