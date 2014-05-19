<?php

/* @var $episode Episode */

?>
<p>
	<?php echo CHtml::encode($episode->getPlot()); ?>
</p>

<div class="episode-streamdetails">
	<?php $this->widget('MediaFlags', array(
		'streamDetails'=>$episode->streamdetails,
		'file'=>$episode->file)); ?>
</div>
