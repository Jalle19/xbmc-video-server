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

$this->widget('ResultGrid', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_movieGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails item-grid',
));