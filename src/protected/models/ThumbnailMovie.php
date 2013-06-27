<?php

/**
 * Represents a movie thumbnail
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailMovie extends Thumbnail
{

	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/placeholder-video.jpg';
	}

}