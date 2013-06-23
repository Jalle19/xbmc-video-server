<?php

/* @var $this MovieController */
/* @var $actorDataProvider LibraryDataProvider */
/* @var $details stdClass */

// TODO: Show stream details somehow

?>
<div class="movie-details">
	<div class="row">
		<div class="span3">
			<?php echo CHtml::image($this->getThumbnailUrl($details->thumbnail), '', array(
				'class'=>'movie-thumbnail',
			)); ?>
			
			<div class="movie-links">
				<h3>Download / Stream</h3>
				
				<p>
					Click the link to play in your browser (requires the VLC 
					plugin or right-click and copy the link to play in your 
					favorite media player.
				</p>
				
				<?php $this->renderPartial('/videoLibrary/_downloadButtons', array(
					'movieFiles'=>$movieFiles,
				));?>
			</div>
		</div>
		
		<div class="span9 movie-description">
			<div class="movie-title">
				<h2>
					<a href="http://www.imdb.com/title/<?php echo $details->imdbnumber; ?>">
						<?php echo $details->label; ?>
					</a>
				</h2>
				
				<p>(<?php echo $details->year; ?>)</p>
				
				<p class="tagline">
					<?php echo $details->tagline; ?>
				</p>
			</div>
			
			<div class="movie-metadata clearfix">
				
				<p><?php echo implode(' / ', $details->genre); ?></p>
				<p><?php echo (int)($details->runtime / 60); ?> min</p>
				
				<p>
					Rating: <?php echo round($details->rating, 1); ?> 
					(<?php echo $details->votes; ?> votes)
				</p>
				
				<p>MPAA rating: <?php echo $details->mpaa; ?></p>
				
			</div>
			
			<h3>Plot</h3>
			
			<div class="movie-plot">
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
</div>
