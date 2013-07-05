<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Manage users';

?>

<h2>Manage users</h2>

<hr />

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>TbHtml::GRID_TYPE_STRIPED,
	'dataProvider'=>$model->dataProvider,
	'enableSorting'=>false,
	'template'=>'{items}',
	'columns'=>array(
		'username',
		'roleName',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));