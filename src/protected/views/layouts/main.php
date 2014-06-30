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
		<div class="container">
			
			<div class="row">
				<div class="span12">
					<h1 id="home-url">
						<a href="<?php echo Yii::app()->homeUrl; ?>">
							<?php echo Yii::app()->name; ?>
						</a>
					</h1>
					<p class="lead">
						<?php echo Yii::t('Misc', 'Free your library'); ?>
					</p>
				</div>
			</div>
			
			<?php $this->renderPartial('//layouts/_cachedNavbar'); ?>
			
			<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
			
			<div class="content">
				<?php echo $content; ?>
			</div>
			
		</div>
		
		<?php $this->widget('bootstrap.widgets.TbModal', array(
			'id'=>'change-language-modal',
			'header'=>Yii::t('Language', 'Change language'),
			'closeText'=>false,
			'content'=>$this->renderPartial('//layouts/_cachedChangeLanguageModalContent', array(
				'model'=>new ChangeLanguageForm()), true),
		)); ?>
	</body>
	
</html>