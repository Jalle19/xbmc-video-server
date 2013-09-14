<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Recently added movies';

?>

<h2>Recently added movies</h2>

<?php

$this->widget('ResultGrid', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_movieGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails item-grid',
));