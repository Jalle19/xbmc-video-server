<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Manage users';

?>

<h2>Manage users</h2>

<?php echo FormHelper::helpBlock('This is where you configure users. Every user 
	has a role. Administrators can do anything while a normal user can only 
	switch backends (if more than one has been configured).'); ?>

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