<?php

/* @var $filterForm MovieFilterForm */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = Yii::t('TVShows', 'TV Shows');

?>
<h2><?php echo $title; ?></h2>

<?php 

$this->widget('TVShowFilter', array(
	'model'=>$filterForm));

switch ($this->getDisplayMode(DisplayMode::CONTEXT_RESULTS))
{
	case DisplayMode::MODE_GRID:
		$this->widget('ResultGrid', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'//videoLibrary/_mediaGridItem',
		));
		break;
	case DisplayMode::MODE_LIST:
		$this->widget('ResultListTVShows', array(
			'dataProvider'=>$dataProvider,
		));
		break;
}