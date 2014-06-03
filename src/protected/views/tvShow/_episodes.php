<?php

use \yiilazyimage\components\LazyImage as LazyImage;

/* @var $season Season */
/* @var $this TvShowController */
$dataProvider = $this->getEpisodeDataProvider($season->tvshowid, $season->season);

?>
<div class="season-episode-list-info row-fluid">
	<div class="season-artwork pull-left">
		<?php echo LazyImage::image(new ThumbnailSeason($season->getArtwork(), Thumbnail::SIZE_MEDIUM)); ?>
	</div>
	
	<h3>
		<?php echo $season->showtitle; ?> - <?php echo $season->getDisplayName(); ?>
	</h3>
	
	<p>
		<?php echo $season->getEpisodesString(); ?>
	</p>
	
	<?php

	if (Yii::app()->user->role !== User::ROLE_SPECTATOR)
	{
		?>
		<div class="season-download">
			<?php echo TbHtml::linkButton(Yii::t('TVShows', 'Watch the whole season'), array(
				'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
				'size'=>TbHtml::BUTTON_SIZE_LARGE,
				'url'=>$this->createUrl('tvShow/getSeasonPlaylist', 
						array('tvshowId'=>$season->tvshowid, 'season'=>$season->season)),
				'class'=>'fontastic-icon-play',
			)).' '.Yii::t('TVShows', 'or choose individual episodes from the list below'); ?>
		</div>
		<?php
	}
	
	?>
</div>

<?php $this->widget('EpisodeList', array(
	'dataProvider'=>$dataProvider,
));
