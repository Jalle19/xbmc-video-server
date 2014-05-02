<div class="pull-left item-rating">

	<p>
		Rating: <?php echo ResultHelper::formatRating($rating); ?>
		
		<?php if (isset($votes))
			echo Yii::t('Media', '({numVotes} votes)', array('{numVotes}'=>$votes)); ?>
	</p>

	<?php $this->renderPartial('/videoLibrary/_ratingStars', 
			array('rating'=>(int)$rating)); ?>

</div>