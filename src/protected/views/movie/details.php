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

		// The check is also done in RetrieveMediaWidget but we don't want 
		// to show this piece of text at all if the user can't do what it 
		// says
		if (Yii::app()->user->role !== User::ROLE_SPECTATOR)
		{
			?>
			<div class="hidden-phone">
				<h3><?php echo Yii::t('Movies', 'Watch / Download'); ?></h3>

				<p>
					<?php echo Yii::t('Movies', 'Click the Watch button to start streaming the video (open 
					the file in your favorite media player), or download the 
					individual files for later viewing using the links below it.'); ?>
				</p>
			</div>

			<?php $this->widget('RetrieveMovieWidget', array(
				'links'=>$movieLinks,
				'details'=>$details,
			)); ?>
			<?php
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

		<div class="item-info clearfix">

			<?php

			if ($details->hasRating())
				$this->renderPartial('/videoLibrary/_rating', array(
					'item'=>$details));

			?>

			<div class="pull-left">

				<div class="item-metadata clearfix">

					<p><?php echo $details->getGenreString(); ?></p>
					<?php

					// Runtime and MPAA rating are not always available
					$runtime = $details->getRuntimeString();

					if ($runtime)
						echo CHtml::tag('p', array(), $runtime);

					$this->widget('MPAARating', array(
						'rating'=>$details->mpaa));

					?>
				</div>

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
