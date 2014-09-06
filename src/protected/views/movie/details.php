<?php

/* @var $this MovieController */
/* @var $actorDataProvider LibraryDataProvider */
/* @var $details Movie */

$this->pageTitle = $details->getDisplayName().' - '.Yii::t('Movies', 'Movies');

?>
<div class="row">
	<div class="span3">
		<?php 

		echo CHtml::image(new ThumbnailVideo($details->getArtwork(), 
				Thumbnail::SIZE_LARGE), '', array(
			'class'=>'item-thumbnail hidden-phone',
		));

		// Don't show the modal trigger to spectators
		if (Yii::app()->user->role !== User::ROLE_SPECTATOR)
		{
			$buttonOptions = array('style'=>'margin-top: 20px;');
			
			$this->widget('WatchMovieModal', array(
				'media'=>$details))->renderTriggerButton($buttonOptions);
		}

		?>

	</div>

	<div class="span9 item-description">
		<div class="item-top row-fluid">
			<div class="item-title span6">
				<h2>
					<a href="<?php echo $details->getIMDbUrl(); ?>" target="_blank">
						<?php echo $details->label; ?>
					</a>
				</h2>

				<p>(<?php echo $details->year; ?>)</p>

				<p class="tagline">
					<?php echo $details->tagline; ?>
				</p>

			</div>

			<div class="span6">
				<?php $this->widget('MediaFlags', array(
					'streamDetails'=>$details->streamdetails,
					'file'=>$details->file
				)); ?>
			</div>
		</div>

		<div class="row-fluid item-info">

			<?php

			if ($details->hasRating())
				$this->renderPartial('/videoLibrary/_rating', array(
					'item'=>$details));

			?>
			
			<div class="span8 item-metadata">

				<p><?php echo $details->getGenreString(); ?></p>
				<?php

				// Runtime and MPAA rating are not always available
				$runtime = $details->getRuntimeString();

				if ($runtime)
					echo CHtml::tag('p', array(), $runtime);

				$this->widget('MPAARating', array(
					'rating'=>$details->mpaa));

				$director = $details->getDirector();

				if ($director)
				{
					echo CHtml::tag('p', array(), Yii::t('Movies', 'Director: {director}', array(
						'{director}'=>$director)));
				}

				?>
			</div>

		</div>

		<h3><?php echo Yii::t('Media', 'Plot'); ?></h3>

		<div class="item-plot">
			<p>
				<?php echo $details->getPlot(); ?>
			</p>
		</div>

		<div class="cast">
		
			<h3><?php echo Yii::t('Media', 'Cast'); ?></h3>

			<?php echo FormHelper::helpBlock(Yii::t('Movies', "Click an image to see other movies with that person, or click the name to go to the person's IMDb page")); ?>

			<div class="row-fluid">
				<?php $this->widget('zii.widgets.CListView', array(
					'dataProvider'=>$actorDataProvider,
					'itemView'=>'_actorGridItem',
					'itemsTagName'=>'ul',
					'itemsCssClass'=>'thumbnails actor-grid',
					'enablePagination'=>false,
					'template'=>'{items}'
				)); ?>
			</div>
			
		</div>
	</div>
</div>
