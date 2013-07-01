<?php

/**
 * Represents a video thumbnail
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ThumbnailVideo extends Thumbnail
{

	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/placeholder-video.jpg';
	}

}