<?php

/* @var $this TvShowController */
/* @var $details TVShow */

$this->pageTitle = $details->getDisplayName();

?>
<div class="row">
	<div class="span3">
		<?php echo CHtml::image(new ThumbnailVideo($details->getArtwork(),
				Thumbnail::SIZE_LARGE), '', array(
			'class'=>'item-thumbnail hidden-phone',
		)); ?>
		
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

		<div class="item-info clearfix">

			<?php

			if ($details->hasRating())
				$this->renderPartial('/videoLibrary/_rating', array('item'=>$details));

			?>

			<div class="pull-left">

				<div class="item-metadata clearfix">

					<p><?php echo $details->getGenreString(); ?></p>

					<?php $this->widget('MPAARating', array(
						'rating'=>$details->mpaa)); ?>
				</div>

			</div>
		</div>

		<h3><?php echo Yii::t('Media', 'Plot'); ?></h3>

		<div class="item-plot">
			<p><?php echo $details->getPlot(); ?></p>
		</div>

		<div class="cast">
			
			<h3><?php echo Yii::t('Media', 'Cast'); ?></h3>

			<?php echo FormHelper::helpBlock(Yii::t('TVShows', "Click the name to go to the person's IMDb page")); ?>

			<div class="row-fluid">
				<?php $this->widget('zii.widgets.CListView', array(
					'dataProvider'=>$actorDataProvider,
					'itemView'=>'/videoLibrary/_actorGridItem',
					'itemsTagName'=>'ul',
					'itemsCssClass'=>'thumbnails actor-grid',
					'enablePagination'=>false,
					'template'=>'{items}'
				)); ?>
			</div>
			
		</div>

	</div>
</div>

<?php $this->widget('Seasons', array(
	'tvshow'=>$details,
	'seasons'=>$seasons,
));
	