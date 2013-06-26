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
		<?php $cs->registerCssFile($baseUrl.'/css/login.css'); ?>
		<?php Yii::app()->bootstrap->registerCoreScripts(); ?>
	</head>
	
	<body>
		<table class="login-centerer">
			<tr>
				<td>
					<div class="login-container">
						<?php 

						$this->widget('bootstrap.widgets.TbAlert');
						echo $content; 

						?>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>