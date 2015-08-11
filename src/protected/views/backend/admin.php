<?php

/* @var $this BackendController */
/* @var $model Backend */

$this->pageTitle = $title = Yii::t('Backend', 'Manage backends');

?>

<h2><?php echo $title; ?></h2>

<?php echo FormHelper::helpBlock(Yii::t('Backend', 'This is where you configure your backends. A 
	backend is an instance of XBMC that the application connects to and serves 
	library contents from. If you specify more than one backend, a new item 
	will appear in the main menu, allowing you to easily switch backends.')); ?>

<?php echo TbHtml::linkButton(Yii::t('Backend', 'Create new backend'), array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	'url'=>array('create'))); ?>

<hr />

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>TbHtml::GRID_TYPE_STRIPED,
	'dataProvider'=>Backend::model()->dataProvider,
	'enableSorting'=>false,
	'template'=>'{items}',
	'columns'=>array(
		'name',
		'hostname',
		'port',
		'tcp_port',
		array(
			'name'=>'default',
			'header'=>Yii::t('Backend', 'Default'),
			'type'=>'raw',
			'value'=>function($data) {
				return $data->default ? TbHtml::icon(TbHtml::ICON_OK) : '';
			}
		),
		'macAddress',
		'subnetMask',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));