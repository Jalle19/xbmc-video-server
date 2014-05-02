<?php

/* @var $this TvShowController */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = 'Recently added episodes';

?>
<h2><?php echo $title; ?></h2>

<?php echo FormHelper::helpBlock(Yii::t('TV Shows', 'You can click the show name to jump directly 
	to the show details page')); ?>

<div class="item-details">
	<?php $this->widget('RecentlyAddedEpisodeList', array(
		'dataProvider'=>$dataProvider,
	)); ?>
</div>
