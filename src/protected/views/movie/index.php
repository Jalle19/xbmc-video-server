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

$this->renderPartial('//videoLibrary/_results', array(
	'dataProvider'=>$dataProvider,
	'widgetList'=>'ResultListMovies'));
