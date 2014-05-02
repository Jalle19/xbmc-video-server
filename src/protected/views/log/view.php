<?php

/* @var $model Log */
$pageTitle = 'Details for log item #'.$model->id;
$this->pageTitle = $pageTitle;

?>
<h2><?php echo $pageTitle; ?></h2>

<div class="log-details">
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
		'type'=>TbHtml::DETAIL_TYPE_STRIPED,
		'data'=>$model,
		'attributes'=>array(
			'level',
			'category',
			'logtime',
			'source_address',
			array(
				'name'=>'message',
				'type'=>'html',
				'value'=>function($data) {
					$message = nl2br($data->message);

					// Format exceptions and core errors differently
					if (strpos($data->category, 'exception') === 0 ||
						strpos($data->category, 'php') === 0)
					{
						$output = CHtml::openTag('div', array('class'=>'exception-message'));
						$output .= $message;
						$output .= CHtml::closeTag('div');

						return $output;
					}
					else
						return $message;
				}
			)
		)
	)); ?>
</div>
