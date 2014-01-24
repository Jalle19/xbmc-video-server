<?php

/* @var $this BackendController */
/* @var $model Backend */

$this->pageTitle = 'Manage backends';

?>

<h2>Manage backends</h2>

<?php echo FormHelper::helpBlock('This is where you configure your backends. A 
	backend is an instance of XBMC that the application connects to and serves 
	library contents from. If you specify more than one backend, a new item 
	will appear in the main menu, allowing you to easily switch backends.'); ?>

<?php echo TbHtml::linkButton('Create new backend', array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	'url'=>array('create'))); ?>

<hr />

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>TbHtml::GRID_TYPE_STRIPED,
	'dataProvider'=>Backend::model()->dataProvider,
	'enableSorting'=>false,
	'template'=>'{items}',
	'columns'=>array(
		array(
			'name'=>'name',
			'header'=>'Name',
		),
		'hostname',
		'port',
		array(
			'name'=>'default',
			'header'=>'Default',
			'type'=>'raw',
			'value'=>function($data) {
				return $data->default ? TbHtml::icon(TbHtml::ICON_OK) : '';
			}
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));