<?php

/* @var $this TvShowController */

$showUrl = $this->createUrl('details', array('id'=>$data->tvshowid));

// Determine which artwork to display
if (isset($data->art->poster))
	$thumbnailPath = $data->art->poster;
else
	$thumbnailPath = $data->thumbnail;

$thumbnail = new ThumbnailVideo($thumbnailPath, Thumbnail::SIZE_MEDIUM);

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>CHtml::link($data->label, $showUrl),
	'itemUrl'=>$showUrl,
	'thumbnail'=>$thumbnail,
));