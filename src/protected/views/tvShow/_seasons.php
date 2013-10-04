<?php

if (count($seasons) > 0)
{
	// Register JavaScript for asynchronously loading episode lists
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.
			'/js/episode-list-loader.js', CClientScript::POS_END);
	
	$items = array();

	// Use lazy-loading if there is more than one season
	if (count($seasons) > 1)
	{
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
	}
	else
	{
		$season = $seasons[0];
		
		$items[] = array(
			'label'=>$season->label,
			// Render the content directly
			'content'=>$this->renderPartial('_episodes', array(
				'tvshowId'=>$details->tvshowid, 'season'=>$season->season), true),
		);
	}

	$this->widget('SeasonAccordion', array(
		'items'=>$items,
		'htmlOptions'=>array(
			'class'=>'season-list',
		),
	));
}
else
	echo CHtml::tag('div', array('class'=>'alert alert-block alert-error'), 'There are no episodes for this show');