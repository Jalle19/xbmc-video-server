<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = Yii::t('Movies', 'Recently added movies');

?>

<h2><?php echo $title; ?></h2>

<?php

switch ($this->getDisplayMode())
{
	case MediaController::DISPLAY_MODE_GRID:
		$this->widget('ResultGrid', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'//videoLibrary/_mediaGridItem',
		));
		break;
	case MediaController::DISPLAY_MODE_LIST:
		$this->widget('ResultListMovies', array(
			'dataProvider'=>$dataProvider,
		));
		break;
}