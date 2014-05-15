<?php

if (count($seasons) > 0)
{
	// Register JavaScript for asynchronously loading episode lists
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.
			'/js/episode-list-loader.js', CClientScript::POS_END);
	
	$items = array();

	// Use lazy-loading for the accordion contents
	foreach ($seasons as $season)
	{
		$items[] = array(
			'label'=>$season->label,
			'content'=>CHtml::image(Yii::app()->baseUrl.'/images/loader.gif', 'Loader'),
			// Pass along the URL where the content can be found at
			'contentUrl'=>$this->createUrl('tvShow/renderEpisodeList', 
					array('tvshowid'=>$details->tvshowid, 'season'=>$season->season)),
		);
	}
	
	// Automatically populate "all" seasons if we only have one
	if (count($seasons) === 1)
	{
		Yii::app()->clientScript->registerScript('PopulateSingleSeason', 
				'populateAll();', CClientScript::POS_END);
	}

	$this->widget('SeasonAccordion', array(
		'items'=>$items,
		'htmlOptions'=>array(
			'class'=>'season-list',
		),
	));
}
else
	echo CHtml::tag('div', array('class'=>'alert alert-block alert-error'), Yii::t('TVShows', 'There are no episodes for this show'));