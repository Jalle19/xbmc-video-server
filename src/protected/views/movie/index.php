<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Movies';

?>

<h2>Movies</h2>

<?php

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_movieGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails movie-grid',
));