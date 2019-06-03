<?php

// define path aliases
Yii::setPathOfAlias('bootstrap', realpath(__DIR__.'/../../../vendor/crisu83/yiistrap'));
Yii::setPathOfAlias('composer', realpath(__DIR__.'/../../../vendor'));

return array(
	'application.models.*',
	'application.models.forms.*',
	'application.models.json.*',
	'application.models.playlist.*',
	'application.models.thumbnail.*',
	'application.components.*',
	'application.components.core.*',
	'application.exceptions.*',
	'application.helpers.*',
	'application.controllers.*',
	'application.controllers.base.*',
	'application.widgets.*',
	'application.widgets.filter.*',
	'application.widgets.results.*',
	'application.widgets.season.*',
	'application.widgets.watch.*',
	'bootstrap.helpers.*',
);
