<?php

/* @var $filterForm MovieFilterForm */
/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'TV Shows';

?>
<h2>TV Shows</h2>

<?php 

$this->widget('TVShowFilter', array(
	'model'=>$filterForm));

$this->widget('ResultGrid', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_tvshowGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails item-grid',
));