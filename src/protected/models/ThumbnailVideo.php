<?php

/**
 * Represents a video thumbnail
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailVideo extends Thumbnail
{

	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/placeholder-video.jpg';
	}

}