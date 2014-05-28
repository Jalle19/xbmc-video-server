<?php

/* @var $this Seasons */

// Use a different view depending on the display mode
switch ($this->displayMode)
{
	case DisplayMode::MODE_LIST:
		$this->render('_seasonList');
		break;
	case DisplayMode::MODE_GRID:
		$this->render('_seasonGrid');
		break;
}
