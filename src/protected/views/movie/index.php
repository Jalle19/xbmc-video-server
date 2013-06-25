<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Movies';

?>

<h2>Movies</h2>

<?php

$this->renderPartial('_filter', array(
	'model'=>$movieFilterForm));

$this->widget('ResultGrid', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_movieGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails item-grid',
));