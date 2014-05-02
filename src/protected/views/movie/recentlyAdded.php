<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = 'Recently added movies';

?>

<h2><?php echo $title; ?></h2>

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
		$this->widget('ResultListMovies', array(
			'dataProvider'=>$dataProvider,
		));
		break;
}