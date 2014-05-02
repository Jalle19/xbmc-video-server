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
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php 
		
		// Add a cache buster to the CSS URL
		$cssPath = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
				.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'css'
				.DIRECTORY_SEPARATOR.'styles-min.css');
		
		$cs->registerCssFile($baseUrl.'/css/styles-min.css?'.filemtime($cssPath));
		
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
		
		<?php $this->widget('bootstrap.widgets.TbModal', array(
			'id'=>'change-language-modal',
			'header'=>Yii::t('Language', 'Change language'),
			'closeText'=>false,
			'content'=>$this->renderPartial('//site/_changeLanguageModalContent', array(
				'model'=>new ChangeLanguageForm(),
			), true),
		)); ?>
	</body>
	
</html>