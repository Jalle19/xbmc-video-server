<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = $title = Yii::t('User', 'Manage users');

?>

<h2><?php echo $title; ?></h2>

<?php echo FormHelper::helpBlock(Yii::t('User', 'This is where you configure users. Every user has a role. Administrators can do anything while a normal user can only switch backends (if more than one has been configured).')); ?>

<?php echo TbHtml::linkButton(Yii::t('User', 'Create new user'), array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	'url'=>array('create'))); ?>

<hr />

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>TbHtml::GRID_TYPE_STRIPED,
	'dataProvider'=>$model->getDataProvider(),
	'enableSorting'=>false,
	'template'=>'{items}',
	'columns'=>array(
		'username',
		array(
			'name'=>'role',
			'value'=>'$data->getRoleName()',
		),
		array(
			'name'=>'language',
			'value'=>function($data) {
				$languages = LanguageManager::getAvailableLanguages();
				
				if (!empty($data->language))
					return $languages[$data->language];
				else
					return $languages[Setting::getString('language')];
			},
		),
		array(
			'name'=>'start_page',
			'value'=>'$data->getStartPage()',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));
