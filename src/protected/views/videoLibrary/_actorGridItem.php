<?php

/* @var $this MovieController */
if (isset($data->thumbnail))
	$thumbnailUrl = $this->createUrl('videoLibrary/getThumbnail', array(
		'thumbnailPath'=>$data->thumbnail));
else
	$thumbnailUrl = Yii::app()->baseUrl.'/images/blank.png';

$label = $data->name.' as <em>'.$data->role.'</em>';

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$label,
	'itemUrl'=>'#',
	'thumbnailUrl'=>$thumbnailUrl,
));