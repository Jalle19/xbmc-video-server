<?php

if (count($seasons) > 0)
{
	$items = array();

	foreach ($seasons as $season)
	{
		$items[] = array(
			'label'=>$season->label,
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