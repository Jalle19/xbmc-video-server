<?php

/* @var $this TvShowController */
/* @var $details TVShow */

$this->pageTitle = $details->getDisplayName();

?>
<div class="row-fluid">
	<div class="span3">
		<?php 
		
		echo CHtml::image(new ThumbnailVideo($details->getArtwork(),
				Thumbnail::SIZE_LARGE), '', array('class'=>'item-thumbnail hidden-phone'));
		
		if (Yii::app()->user->role !== User::ROLE_SPECTATOR)
		{
			?>
			<div class="item-links">
				<?php echo TbHtml::linkButton(Yii::t('TVShows', 'Watch the whole show'), array(
					'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
					'size'=>TbHtml::BUTTON_SIZE_LARGE,
					'url'=>$this->createUrl('tvShow/getTVShowPlaylist', 
							array('tvshowId'=>$details->getId())),
					'class'=>'fa fa-play',
				)); ?>

				<p style="margin-top: 20px;">
					<?php echo Yii::t('TVShows', 'or choose individual episodes from the list below'); ?>
				</p>
			</div>
			<?php
		}
		
		?>
	</div>

	<div class="span9 item-description">
		<div class="item-top">
			<div class="item-title">
				<h2>
					<a href="<?php echo $details->getTVDBUrl(); ?>" target="_blank">
						<?php echo $details->label; ?>
					</a>
				</h2>

				<?php if(!empty($details->year))
					echo '<p>('.$details->year.')</p>'; ?>
			</div>
		</div>

		<div class="row-fluid item-info">

			<?php

			if ($details->hasRating())
				$this->renderPartial('/videoLibrary/_rating', array('item'=>$details));

			?>

			<div class="span8 item-metadata">

				<p><?php echo $details->getGenreString(); ?></p>

				<?php $this->widget('MPAARating', array(
					'rating'=>$details->mpaa)); ?>
			</div>

		</div>

		<h3><?php echo Yii::t('Media', 'Plot'); ?></h3>

		<div class="item-plot">
			<p><?php echo $details->getPlot(); ?></p>
		</div>

		<?php
		
		// Don't render unless there's a cast to display
		if ($actorDataProvider->itemCount > 0)
		{
			$this->renderPartial('//videoLibrary/_cast', array(
				'actorDataProvider'=>$actorDataProvider));
		}
		
		?>
	</div>
</div>

<?php $this->widget('Seasons', array(
	'tvshow'=>$details,
	'seasons'=>$seasons,
));
	