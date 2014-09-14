<?php
			
$name = Setting::getString('applicationName');
$subtitle = Setting::getString('applicationSubtitle');

?>
<div class="row-fluid">
	<div class="span12 header">
		<?php

		if ($name)
		{
			?>
			<h1 id="home-url">
				<a href="<?php echo Yii::app()->homeUrl; ?>">
					<?php echo $name; ?>
				</a>
			</h1>
			<?php
		}

		if ($subtitle)
			echo CHtml::tag('p', array('class'=>'lead'), $subtitle);

		?>
	</div>
</div>