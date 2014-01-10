<?php

/* @var $this TvShowController */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Recently added episodes';

?>
<h2>Recently added episodes</h2>

<?php echo FormHelper::helpBlock('You can click the show name to jump directly 
	to the show details page'); ?>

<div class="item-details">
	<?php $this->widget('RecentlyAddedEpisodeList', array(
		'dataProvider'=>$dataProvider,
	)); ?>
</div>
