<?php

/* @var $item Media */

?>
<div class="pull-left item-rating">

	<p>
		<?php echo Yii::t('Media', 'Rating'); ?>: 
		<?php echo $item->getRating(); ?>
		
		<?php if (isset($item->votes))
			echo Yii::t('Media', '({numVotes} votes)', array('{numVotes}'=>$item->votes)); ?>
	</p>

	<?php $this->renderPartial('/videoLibrary/_ratingStars', 
			array('rating'=>(int)$item->rating)); ?>

</div>