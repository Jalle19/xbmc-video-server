<?php

/* @var $model Log */

$this->pageTitle = $title = Yii::t('Log', 'Application log');

?>
<h2><?php echo $title; ?></h2>

<?php echo FormHelper::helpBlock(Yii::t('Log', 'This is the system log. You can sort and 
	filter the table freely to find the information you need. To see the full 
	error message (in case it has been clipped), click the view icon on the 
	right. You can also flush the log by pressing the <i>Flush logs</i> 
	button.')); ?>

<?php echo TbHtml::linkButton(Yii::t('Log', 'Flush logs'), array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	'url'=>array('flush'),
	'confirm'=>Yii::t('Misc', 'Are you sure?'),
)); ?>

<hr />

<div class="log-grid">
	<?php $this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>array(TbHtml::GRID_TYPE_STRIPED),
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>array(
			'level',
			'category',
			'logtime',
			'source_address',
			array(
				'name'=>'message',
				'cssClassExpression'=>function() {
					return 'message-column';
				}
			),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				'template'=>'{view}',
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
	)); ?>
</div>