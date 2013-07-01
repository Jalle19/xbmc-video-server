<?php

/* @var $this TvShowController */
/* @var $details stdClass */

$pageTitle = $details->title;
if (!empty($details->year))
	$pageTitle .= ' ('.$details->year.')';

$this->pageTitle = $pageTitle;

?>
<div class="item-details">
	<div class="row">
		<div class="span3">
			<?php echo CHtml::image(new ThumbnailVideo($details->thumbnail,
					Thumbnail::SIZE_LARGE), '', array(
				'class'=>'item-thumbnail hidden-phone',
			)); ?>
		</div>
		
		<div class="span9 item-description">
			<div class="item-top row-fluid">
				<div class="item-title span6">
					<h2>
						<a href="http://www.thetvdb.com/?tab=series&id=<?php echo $details->imdbnumber; ?>">
							<?php echo $details->title; ?>
						</a>
					</h2>

					<?php if(!empty($details->year))
						echo '<p>('.$details->year.')</p>'; ?>
				</div>
			</div>
			
			<div class="item-info clearfix">
				
				<?php
				
				$rating = (int)$details->rating;
				
				if ($rating > 0)
					$this->renderPartial('/videoLibrary/_rating', array('rating'=>$rating));
				
				?>
				
				<div class="pull-left">

					<div class="item-metadata clearfix">

						<p><?php echo implode(' / ', $details->genre); ?></p>

						<?php

						// MPAA rating is not always available
						if ($details->mpaa)
							echo '<p>MPAA rating: '.$details->mpaa.'</p>';
						
						?>
					</div>

				</div>
			</div>
			
			<h3>Plot</h3>
			
			<div class="item-plot">
				<p><?php echo $details->plot; ?></p>
			</div>
			
			<h3>Cast</h3>

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
	
	<div class="row">
		<div class="span12">
			<h3>Seasons</h3>
			
			<?php echo $this->renderPartial('_seasons', array(
				'details'=>$details,
				'seasons'=>$seasons)); ?>
		</div>
	</div>
</div>
