<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'TV Shows';

?>
<h2>TV Shows</h2>

<?php 

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_tvshowGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails item-grid',
));