<?php

/* @var $cs CClientScript */
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $this->pageTitle; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php $cs->registerCssFile($baseUrl.'/css/styles.css'); ?>
		<?php Yii::app()->bootstrap->registerCoreScripts(); ?>
	</head>
	
	<body>
		<div class="container">
			
			<div class="row">
				<div class="span12">
					<h1>
						<a id="home-url" href="<?php echo Yii::app()->homeUrl; ?>">
							<?php echo Yii::app()->name; ?>
						</a>
					</h1>
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
							array('label'=>'Movies', 'url'=>array('movie/index'), 
								'active'=>in_array(Yii::app()->controller->route, array('movie/index', 'movie/details'))),
							array('label'=>'TV Shows', 'url'=>array('tvShow/index')),
							array('label'=>'Settings', 'url'=>'#'),
						),
					),
				),
			)); ?>
			
			<div class="content">
				<?php echo $content; ?>
			</div>
			
		</div>
	</body>
	
</html>