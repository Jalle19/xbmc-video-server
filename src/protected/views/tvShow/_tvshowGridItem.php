<?php

/* @var $this TvShowController */

$showUrl = $this->createUrl('showDetails', array('id'=>$data->tvshowid));

// Determine which artwork to display
if (isset($data->art->poster))
	$thumbnailPath = $data->art->poster;
else
	$thumbnailPath = $data->thumbnail;

$thumbnailUrl = $this->createUrl('thumbnail/get', 
		array('path'=>$thumbnailPath, 'size'=>Thumbnail::THUMBNAIL_SIZE_MEDIUM, 'type'=>Thumbnail::TYPE_TVSHOW));

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$data->label,
	'itemUrl'=>$showUrl,
	'thumbnailUrl'=>$thumbnailUrl,
));