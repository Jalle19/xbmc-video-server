<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = $title = Yii::t('Movies', 'Recently added movies');

?>

<h2><?php echo $title; ?></h2>

<?php

$this->renderPartial('//videoLibrary/_results', array(
	'dataProvider'=>$dataProvider,
	'widgetList'=>'ResultListMovies'));