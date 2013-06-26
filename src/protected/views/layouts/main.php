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
		<?php Yii::app()->bootstrap->registerCoreScripts(null, CClientScript::POS_BEGIN); ?>
	</head>
	
	<body>
		<div class="container">
			
			<div class="row">
				<div class="span12">
					<h1 id="home-url">
						<a href="<?php echo Yii::app()->homeUrl; ?>">
							<?php echo Yii::app()->name; ?>
						</a>
					</h1>
					<p class="lead">
						Free your library
					</p>
				</div>
			</div>
			
			<?php $this->renderPartial('//layouts/_navbar'); ?>
			
			<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
			
			<div class="content">
				<?php echo $content; ?>
			</div>
			
		</div>
	</body>
	
</html>