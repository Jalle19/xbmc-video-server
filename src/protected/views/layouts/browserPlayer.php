<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo strip_tags($this->pageTitle); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=0.65" />
		<style type="text/css">
			body, html {
				height: 100%;
				max-height: 100%;
				margin: 0;
				padding: 0;
				background: #000;
			}
			
			video {
				width: 100%;
				height: 100%;
			}
		</style>
	</head>
	
	<body>
		<?php echo $content; ?>
	</body>
	
</html>