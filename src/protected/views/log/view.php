<?php

/* @var $model Log */
$pageTitle = 'Details for log item #'.$model->id;
$this->pageTitle = $pageTitle;

?>
<h2><?php echo $pageTitle; ?></h2>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'type'=>TbHtml::DETAIL_TYPE_STRIPED,
	'data'=>$model,
	'attributes'=>array(
		'level',
		'category',
		'logtime',
		array(
			'name'=>'message',
			'type'=>'html',
			'value'=>function($data) {
				return nl2br($data->message);
			}
		)
	)
));
