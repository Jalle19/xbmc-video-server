<?php

/* @var $this MovieController */
/* @var $filterForm MovieFilterForm */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = Yii::t('Movies', 'Movies');

?>

<h2><?php echo $title; ?></h2>

<?php

$this->widget('MovieFilter', array(
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
		$this->widget('ResultListMovies', array(
			'dataProvider'=>$dataProvider,
		));
		break;
}
