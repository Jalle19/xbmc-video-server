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
		<?php $cs->registerCssFile($baseUrl.'/css/login-min.css'); ?>
		<?php $this->registerCustomCss(); ?>
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