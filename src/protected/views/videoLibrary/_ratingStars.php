<div class="stars">
	<?php

	for ($i = 0; $i < 10; $i++)
	{
		$class = $i < $rating ? 'rating-star rating-star-yellow yellow' : 'rating-star';
		echo CHtml::tag('span', array('class'=>$class), '&#9734;');
	}

	?>
</div>