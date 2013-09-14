<?php

/* @var $model Log */

$this->pageTitle = 'Application log';

echo CHtml::openTag('div', array('class'=>'log-grid'));

$this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>array(TbHtml::GRID_TYPE_STRIPED),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'level',
		'category',
		'logtime',
		array(
			'name'=>'message',
			'cssClassExpression'=>function($data) {
				return 'message-column';
			}
		),
	),
	'rowCssClassExpression'=>function($row, $data) {
		if($data->level === CLogger::LEVEL_ERROR)
			return 'error-row';
	},
	'template'=>"{items}\n{pager}",
	'pagerCssClass'=>'pager', // yiistrap's default is incorrect
	'pager'=>array(
		'class'=>'bootstrap.widgets.TbPager',
		'maxButtonCount'=>10,
		'htmlOptions'=>array(
			'align'=>TbHtml::PAGINATION_ALIGN_RIGHT,
		),
	),
));

echo CHtml::closeTag('div');
