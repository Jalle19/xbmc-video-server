<?php

/* @var $cs CClientScript */
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;

// Set the application name
Yii::app()->name = Setting::getValue('applicationName');

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo strip_tags($this->pageTitle); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php 
		
		// Add a cache buster to the CSS URL
		$cssPath = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
				.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'css'
				.DIRECTORY_SEPARATOR.'styles.css');

		$cs->registerCssFile($baseUrl.'/css/styles.css?'.filemtime($cssPath));
		
		$this->registerScripts();
		
		?>
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
			
			<?php $this->renderPartial('//layouts/_cachedNavbar'); ?>
			
			<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
			
			<div class="content">
				<?php echo $content; ?>
			</div>
			
		</div>
	</body>
	
</html>