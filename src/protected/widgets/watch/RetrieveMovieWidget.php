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
	
	protected function getLogMessage()
	{
		return '"'.Yii::app()->user->name.'" streamed/downloaded "'.$this->details->getDisplayName().'"';
	}

	protected function getPlayListAction()
	{
		return array('getMoviePlaylist');
	}

}