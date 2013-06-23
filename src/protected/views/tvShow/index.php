<h2><h2>TV Shows</h2></h2>

<?php

/* @var $dataProvider LibraryDataProvider */

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_tvshowGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails movie-grid',
));