<?php

/* @var $this SettingsController */
/* @var $model Backend */

$this->pageTitle = 'Manage backends';

?>

<h2>Manage backends</h2>

<hr />

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>TbHtml::GRID_TYPE_STRIPED,
	'dataProvider'=>$model->dataProvider,
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