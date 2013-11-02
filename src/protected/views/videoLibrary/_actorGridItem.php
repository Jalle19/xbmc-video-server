<?php

/* @var $this MediaController */

// Unlike movies and TV shows, actors don't always have the thumbnail property
$thumbnailPath = isset($data->thumbnail) ? $data->thumbnail : '';
$thumbnail = new ThumbnailActor($thumbnailPath, Thumbnail::SIZE_SMALL);

// Make the label a link to the IMDB search page for the actor
$label = $data->name.' as <em>'.$data->role.'</em>';
$labelUrl = 'http://www.imdb.com/find?q='.urlencode($data->name).'&s=nm';

if(!isset($itemUrl))
	$itemUrl = false;

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$label,
	'labelUrl'=>$labelUrl,
	'itemUrl'=>$itemUrl,
	'thumbnail'=>$thumbnail,
));