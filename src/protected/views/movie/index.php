<?php

/* @var $this MovieController */
/* @var $filterForm MovieFilterForm */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Movies';

?>

<h2>Movies</h2>

<?php

$this->widget('MovieFilter', array(
	'model'=>$filterForm));

switch ($this->getDisplayMode())
{
	case MediaController::DISPLAY_MODE_GRID:
		$this->widget('ResultGrid', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_movieGridItem',
		));
		break;
	case MediaController::DISPLAY_MODE_LIST:
		$this->widget('ResultList', array(
			'dataProvider'=>$dataProvider,
		));
		break;
}
