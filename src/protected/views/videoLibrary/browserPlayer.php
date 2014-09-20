<?php

/* @var MediaController $this */
/* @var stdClass[] $items */

$this->layout = 'browserPlayer';

?>
<video controls autoplay>
	<?php
	
	foreach ($items as $item)
	{
		?>
		<source src="<?php echo $item->url; ?>" type="<?php echo $item->mimeType; ?>" />
		Your browser does not support the <code>video</code> element.
		<?php
	}
	
	?>
</video>