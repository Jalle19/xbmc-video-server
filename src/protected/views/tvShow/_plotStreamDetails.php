<p>
	<?php echo $episode->plot; ?>
</p>

<div class="episode-streamdetails">
	<?php $this->widget('MediaFlags', array(
		'streamDetails'=>$episode->streamdetails)); ?>
</div>