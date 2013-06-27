<?php

/* @var $this MovieController */

// Unlike movies and TV shows, actors don't always have the thumbnail property
$thumbnail = isset($data->thumbnail) ? $data->thumbnail : '';

$thumbnailUrl = $this->createUrl('thumbnail/get', array(
	'path'=>$thumbnail, 'size'=>Thumbnail::THUMBNAIL_SIZE_SMALL, 
	'type'=>Thumbnail::TYPE_ACTOR));

$label = $data->name.' as <em>'.$data->role.'</em>';

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$label,
	'itemUrl'=>'#',
	'thumbnailUrl'=>$thumbnailUrl,
));