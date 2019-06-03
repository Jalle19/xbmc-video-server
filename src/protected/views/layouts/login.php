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
		<?php

		// Add a cache buster to the CSS and script URLs
		$cssPath = $this->getWebrootDirectory('css').DIRECTORY_SEPARATOR.'login-min.css';
		$cs->registerCssFile($baseUrl.'/css/login-min.css?'.filemtime($cssPath));
	
		?>
	</head>
	
	<body>
		<table class="login-centerer">
			<tr>
				<td>
					<div class="login-container">
						<?php 
						
						$this->widget('bootstrap.widgets.TbAlert', array(
							'closeText'=>false));
						
						echo $content; 
						
						?>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>
