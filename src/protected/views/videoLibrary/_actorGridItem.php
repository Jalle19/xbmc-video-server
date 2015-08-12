<?php

/* @var $this MediaController */

// Unlike movies and TV shows, actors don't always have the thumbnail property
$thumbnailPath = isset($data->thumbnail) ? $data->thumbnail : '';
$thumbnail = ThumbnailFactory::create($thumbnailPath,
	Thumbnail::SIZE_MEDIUM, ThumbnailFactory::THUMBNAIL_TYPE_ACTOR);

// Make the label a link to the IMDB search page for the actor
$label = Yii::t('Media', '{actorName} as {role}', array('{actorName}'=>$data->name, '{role}'=>'<em>'.$data->role.'</em>'));
$labelUrl = 'http://www.imdb.com/find?q='.urlencode($data->name).'&s=nm';

$itemUrl = $this->createUrl('movie/index', array(
	'MovieFilterForm[actor]'=>$data->name));

$this->renderPartial('//videoLibrary/_gridItem', array(
	// the label is used for IMDb links so we make it open in a new tab
	'label'=>CHtml::link($label, $labelUrl, array('target'=>'_blank')),
	'itemUrl'=>$itemUrl,
	'thumbnail'=>$thumbnail,
));