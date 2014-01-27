<div class="pull-left item-rating">

	<p>
		Rating: <?php echo ResultHelper::formatRating($rating); ?>
		
		<?php if (isset($votes))
			echo '('.$votes.' votes)'; ?>
	</p>

	<?php $this->renderPartial('/videoLibrary/_ratingStars', 
			array('rating'=>(int)$rating)); ?>

</div>