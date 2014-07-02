<?php

// TODO: Position this element in a more clever way
echo CHtml::tag('h3', array('style'=>'margin-bottom: -50px;'), Yii::t('TVShows', 'Seasons'));

$dataProvider = new LibraryDataProvider($this->seasons);

Yii::app()->controller->widget('ResultGrid', array(
	'displayModeContext'=>DisplayMode::CONTEXT_SEASONS,
	'dataProvider'=>$dataProvider,
	'itemView'=>'//tvShow/_seasonGridItem',
));