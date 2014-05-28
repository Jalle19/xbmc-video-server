<?php

/* @var $this MediaController */
/* @var $dataProvider LibraryDataProvider */

switch ($this->getDisplayMode(DisplayMode::CONTEXT_RESULTS))
{
	case DisplayMode::MODE_GRID:
		$this->widget('ResultGrid', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'//videoLibrary/_mediaGridItem',
		));
		break;
	case DisplayMode::MODE_LIST:
		$this->widget($widgetList, array(
			'dataProvider'=>$dataProvider,
		));
		break;
}
