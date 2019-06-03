<?php

/* @var $cs CClientScript */
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo strip_tags($this->pageTitle); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=0.65" />
		<?php 
		
		// Make sure jQuery is registered first
		$cs->registerCoreScript('jquery');
		
		// Add a cache buster to the CSS and script URLs
		$cssPath = $this->getWebrootDirectory('css').DIRECTORY_SEPARATOR.'styles-min.css';
		$scriptPath = $this->getWebrootDirectory('js').DIRECTORY_SEPARATOR.'xbmc-video-server.min.js';
		$cs->registerCssFile($baseUrl.'/css/styles-min.css?'.filemtime($cssPath));
		$cs->registerScriptFile($baseUrl.'/js/xbmc-video-server.min.js?'.filemtime($scriptPath), $cs->coreScriptPosition);
		
		// Register custom stylesheet if one exists
		$this->registerCustomCss('custom-styles.css');
		
		?>
	</head>
	
	<body>
		<div class="container-fluid">
			
			<?php $this->renderPartial('//layouts/_header'); ?>
			
			<?php $this->renderPartial('//layouts/_cachedNavbar'); ?>
			
			<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
			
			<div class="content">
				<?php echo $content; ?>
			</div>
			
		</div>
		
		<?php $this->widget('bootstrap.widgets.TbModal', array(
			'id'=>'power-off-modal',
			'header'=>Yii::t('PowerOff', 'Power off'),
			'closeText'=>false,
			'content'=>$this->renderPartial('//layouts/_cachedPowerOffModalContent', array(), true),
		)); ?>
	</body>
	
</html>
