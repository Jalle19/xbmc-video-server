<?php

/* @var $this TvShowController */
$dataProvider = $this->getEpisodeDataProvider($tvshowId, $season);

if (Yii::app()->user->role !== User::ROLE_SPECTATOR)
{
	?>
	<div class="season-download">
		<?php echo TbHtml::linkButton('Watch the whole season', array(
			'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
			'size'=>TbHtml::BUTTON_SIZE_LARGE,
			'url'=>$this->createUrl('tvShow/getSeasonPlaylist', 
					array('tvshowId'=>$tvshowId, 'season'=>$season)),
			'class'=>'fontastic-icon-play',
		)); ?> or choose individual episodes from the list below
	</div>
	<?php
}

$this->widget('EpisodeList', array(
	'dataProvider'=>$dataProvider,
));
