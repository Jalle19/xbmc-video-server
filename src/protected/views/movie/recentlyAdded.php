<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = Yii::t('Movies', 'Recently added movies');

?>

<h2><?php echo $title; ?></h2>

<?php

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