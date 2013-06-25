<div class="pull-left item-rating">

	<p>
		Rating: <?php echo round($rating, 1); ?>
		
		<?php if (isset($votes))
			echo '('.$votes.' votes)'; ?>
	</p>

	<?php $this->renderPartial('/videoLibrary/_ratingStars', 
			array('rating'=>(int)$rating)); ?>

</div>