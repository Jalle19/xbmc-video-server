<?php

/* @var $cs CClientScript */
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo Yii::app()->name ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php $cs->registerCssFile($baseUrl.'/css/styles.css'); ?>
	</head>
	
	<body>
		<div class="container">
			
			<div class="row">
				<div class="span12">
					<h1><?php echo Yii::app()->name; ?></h1>
					<p class="lead">
						Free your library
					</p>
				</div>
			</div>
			
			<?php $this->widget('bootstrap.widgets.TbNavbar', array(
				'brandLabel'=>false,
				'display'=>TbHtml::NAVBAR_DISPLAY_NONE,
				'collapse'=>true,
				'items'=>array(
					array(
						'class'=>'bootstrap.widgets.TbNav',
						'items'=>array(
							array('label'=>'Movies', 'url'=>'#'),
							array('label'=>'TV Shows', 'url'=>'#'),
							array('label'=>'Settings', 'url'=>'#'),
						),
					),
				),
			)); ?>
			
			<?php echo $content; ?>
		</div>
	</body>
	
</html>