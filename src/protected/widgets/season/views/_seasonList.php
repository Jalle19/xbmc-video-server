<div class="row">
	
	<div class="span6">
		<h3><?php echo Yii::t('TVShows', 'Seasons'); ?></h3>
	</div>
	
	<div class="span6">
		<?php echo ResultHelper::renderDisplayModeToggle(null, DisplayMode::CONTEXT_SEASONS); ?>
	</div>
	
</div>

<div class="row">
	
	<div class="span12">
		<?php

		$items = array();

		// Use lazy-loading for the accordion contents
		foreach ($this->seasons as $season)
		{
			/* @var $season Season */
			$items[] = array(
				'season'=>$season,
				'content'=>CHtml::image(Yii::app()->baseUrl.'/images/loader.gif', 'Loader'),
				// Pass along the URL where the content can be found at
				'contentUrl'=>Yii::app()->createUrl('tvShow/renderEpisodeList', 
						array('tvshowid'=>$this->tvshow->getId(), 'season'=>$season->season)),
			);
		}
		
		$this->widget('SeasonAccordion', array(
			'items'=>$items,
			'htmlOptions'=>array(
				'class'=>'season-list',
			),
		));
		
		?>
	</div>
</div>