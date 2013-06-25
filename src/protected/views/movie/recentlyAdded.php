<?php

/* @var $dataProvider LibraryDataProvider */
$this->pageTitle = 'Recently Added Movies';

?>

<h2>Recently Added Movies</h2>

<?php

$this->widget('ResultGrid', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_movieGridItem',
	'itemsTagName'=>'ul',
	'itemsCssClass'=>'thumbnails movie-grid',
));