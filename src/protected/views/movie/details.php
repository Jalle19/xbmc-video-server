<?php

/* @var $this MovieController */
/* @var $actorDataProvider LibraryDataProvider */
/* @var $details stdClass */

$this->pageTitle = $details->label.' ('.$details->year.')';
// TODO: Show stream details somehow

?>
<div class="movie-details">
	<div class="row">
		<div class="span3">
			<?php echo CHtml::image(Thumbnail::get($details->thumbnail, 
					Thumbnail::THUMBNAIL_SIZE_LARGE), '', array(
				'class'=>'movie-thumbnail hidden-phone',
			)); ?>
			
			<div class="movie-links">
				<h3>Watch / Download</h3>
				
				<p>
					Click the Watch button to start streaming the video (open 
					the file in your favorite media player), or download the 
					individual files for later viewing using the links below it.
				</p>
				
				<?php echo TbHtml::linkButton('Watch', array(
					'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
					'size'=>TbHtml::BUTTON_SIZE_LARGE,
					'url'=>array('getMoviePlaylist', 'movieId'=>$details->movieid),
				)); ?>
				
				<div class="movie-links">
					<?php

					$numLinks = count($movieLinks);

					foreach ($movieLinks as $k=> $link)
					{
						if ($numLinks == 1)
							$label = 'Download';
						else
							$label = 'Download (part #'.(++$k).')';

						echo CHtml::tag('p', array(), CHtml::link($label, $link));
					}

					?>
				</div>
			</div>
		</div>
		
		<div class="span9 movie-description">
			<div class="movie-top row-fluid">
				<div class="movie-title span6">
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
				
				<div class="span6 hidden-phone">
					<?php $this->widget('MediaFlags', array(
							'streamDetails'=>$details->streamdetails)); ?>
				</div>
			</div>
			
			<div class="movie-info clearfix">
				
				<div class="pull-left movie-rating">

					<p>
						Rating: <?php echo round($details->rating, 1); ?> 
						(<?php echo $details->votes; ?> votes)
					</p>
					
					<?php $this->renderPartial('/videoLibrary/_ratingStars', 
							array('rating'=>(int)$details->rating)); ?>

				</div>

				<div class="pull-left">

					<div class="movie-metadata clearfix">

						<p><?php echo implode(' / ', $details->genre); ?></p>
						<p><?php echo (int)($details->runtime / 60); ?> min</p>

						<?php

						// MPAA rating is not always available
						if ($details->mpaa)
							echo '<p>MPAA rating: '.$details->mpaa.'</p>';
						
						?>
					</div>

				</div>
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
