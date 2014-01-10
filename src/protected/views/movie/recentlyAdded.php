<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Recently added movies';

?>

<h2>Recently added movies</h2>

<?php

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