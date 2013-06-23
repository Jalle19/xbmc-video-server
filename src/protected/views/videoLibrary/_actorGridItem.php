<?php

/* @var $this MovieController */
$thumbnailUrl = $this->createUrl('videoLibrary/getThumbnail', 
		array('thumbnailPath'=>$data->thumbnail));

$label = $data->name.' as <em>'.$data->role.'</em>';

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$label,
	'itemUrl'=>'#',
	'thumbnailUrl'=>$thumbnailUrl,
));