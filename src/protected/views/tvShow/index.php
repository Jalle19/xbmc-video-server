<?php

/* @var $filterForm MovieFilterForm */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'TV Shows';

?>
<h2>TV Shows</h2>

<?php 

$this->widget('TVShowFilter', array(
	'model'=>$filterForm));

switch ($this->getDisplayMode())
{
	case MediaController::DISPLAY_MODE_GRID:
		$this->widget('ResultGrid', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_tvshowGridItem',
		));
		break;
	case MediaController::DISPLAY_MODE_LIST:
		$this->widget('ResultListTVShows', array(
			'dataProvider'=>$dataProvider,
		));
		break;
}