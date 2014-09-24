<?php

/* @var MediaController $this */
/* @var stdClass[] $items */

$this->layout = 'browserPlayer';

?>
<video controls autoplay>
	<?php
	
	foreach ($items as $item)
	{
		$attributes = array('src'=>$item->url);
		
		// Only include MIME type if it is set
		if ($item->mimeType)
			$attributes['type'] = $item->mimeType;
		
		echo CHtml::tag('source', $attributes, false, false);
	}
	
	?>
</video>