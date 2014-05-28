<?php

/* @var $this TvShowController */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = Yii::t('TVShows', 'Recently added episodes');

?>
<h2><?php echo $title; ?></h2>

<?php 

echo FormHelper::helpBlock(Yii::t('TVShows', 'You can click the show name to jump directly to the show details page'));

$this->widget('RecentlyAddedEpisodeList', array(
	'dataProvider'=>$dataProvider,
)); 
	