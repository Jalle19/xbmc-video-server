<?php 

$leftItems = array(
	array('label'=>Yii::t('Menu', 'Movies'), 
		'items'=>array(
			array(
				'label'=>Yii::t('Menu', 'Browse'), 'url'=>array('movie/index'),
				'active'=>in_array($this->route, array('movie/index', 'movie/details')),
			),
			array(
				'label'=>Yii::t('Menu', 'Recently added'), 'url'=>array('movie/recentlyAdded'),
			),
		), 'linkOptions'=>array('class'=>'fa fa-video-camera'),
	),
	array('label'=>Yii::t('Menu', 'TV shows'), 
		'items'=>array(
			array(
				'label'=>Yii::t('Menu', 'Browse'), 'url'=>array('tvShow/index'),
				'active'=>in_array($this->route, array('tvShow/index', 'tvShow/details', 'tvShow/season')),
			),
			array(
				'label'=>Yii::t('Menu', 'Recently added'), 'url'=>array('tvShow/recentlyAdded'),
			),
		), 'linkOptions'=>array('class'=>'fa fa-desktop'),
	),
);

$rightItems = array();

// Add a "Change backend" menu when there's more than one configured backend
$backends = Backend::model()->findAll();

if (count($backends) > 1)
{
	$backendItems = array();
	$currentBackend = Yii::app()->backendManager->getCurrent();

	foreach ($backends as $backend)
	{
		$label = $backend->name;

		if ($currentBackend !== null && $currentBackend->id == $backend->id)
			$label = TbHtml::icon(TbHtml::ICON_OK).' '.$label;

		$backendItems[] = array(
			'label'=>$label,
			'url'=>array('backend/change', 'id'=>$backend->id),
		);
	}

	$rightItems[] = array(
		'label'=>Yii::t('Menu', 'Change backend'),
		'items'=>$backendItems,
		'linkOptions'=>array('class'=>'fa fa-cloud')
	);
}

// Add the "Settings" menu for administrators
if (Yii::app()->user->role == User::ROLE_ADMIN)
{
	$rightItems[] = array('label'=>Yii::t('Menu', 'Settings'), 'items'=>array(
		array('label'=>Yii::t('Menu', 'Settings')),
		array('label'=>Yii::t('Menu', 'Manage'), 'url'=>array('setting/admin')),
		array('label'=>Yii::t('Menu', 'Backends')),
		array('label'=>Yii::t('Menu', 'Manage'), 'url'=>array('backend/admin')),
		array('label'=>Yii::t('Menu', 'Create new'), 'url'=>array('backend/create')),
		array('label'=>Yii::t('Menu', 'Users')),
		array('label'=>Yii::t('Menu', 'Manage'), 'url'=>array('user/admin')),
		array('label'=>Yii::t('Menu', 'Create new'), 'url'=>array('user/create')),
		array('label'=>Yii::t('Menu', 'System log')),
		array('label'=>Yii::t('Menu', 'Browse'), 'url'=>array('log/')),
	), 'linkOptions'=>array('class'=>'fa fa-cogs'));
}

// Add the "Actions" menu
$changeLanguageItem = array('label'=>Yii::t('Menu', 'Change language'), 'url'=>'#',
	'linkOptions'=>array(
		'data-toggle'=>'modal', 'data-target'=>'#change-language-modal'));

$actions = array(
	// interface-related actions
	array('label'=>Yii::t('Menu', 'Interface')),
	$changeLanguageItem,
	// system-related
	array('label'=>Yii::t('Menu', 'System')),
);

// Only show "Flush cache" if cacheApiCalls is enabled
if (Setting::getBoolean('cacheApiCalls'))
{
	$actions[] = array(
		'label'=>Yii::t('Menu', 'Flush cache'),
		'url'=>array('site/flushCache'),
		'linkOptions'=>array('confirm'=>Yii::t('Misc', 'Are you sure you want to flush the cache?')),
	);
}

$actions[] = array(
	'label'=>Yii::t('Menu', 'Update library'), 
	'url'=>array('backend/updateLibrary'), 
	'linkOptions'=>array('confirm'=>Yii::t('Misc', "Are you sure you want to update the backend's library?"))
);

if (Yii::app()->powerOffManager->getAllowedActions())
{
	$actions[] = array(
		'label'=>Yii::t('Menu', 'Power off'), 'url'=>'#', 
		'linkOptions'=>array(
			'data-toggle'=>'modal', 'data-target'=>'#power-off-modal')
	);
}

// user-related actions
$actions = array_merge($actions, array(
	array('label'=>Yii::t('Menu', 'User')),
	array('label'=>Yii::t('Menu', 'Change password'), 'url'=>array('user/changePassword')),
	array('label'=>Yii::t('Menu', 'Log out'), 'url'=>array('site/logout')),
));

$rightItems[] = array('label'=>Yii::t('Menu', 'Actions'), 'items'=>$actions, 
	'linkOptions'=>array('class'=>'fa fa-tasks'));

// Completely override the items when the application hasn't been configured yet
if (Yii::app()->backendManager->getCurrent() === null)
{
	$leftItems = array();
	
	$rightItems = array(
		array('label'=>Yii::t('Menu', 'Settings'), 'items'=>array(
			$changeLanguageItem,
			array('label'=>Yii::t('Menu', 'Backends')),
			array('label'=>Yii::t('Menu', 'Create new'), 'url'=>array('backend/create')),
			array('label'=>Yii::t('Menu', 'System log')),
			array('label'=>Yii::t('Menu', 'Browse'), 'url'=>array('log/')),
			array('label'=>Yii::t('Menu', 'User')),
			array('label'=>Yii::t('Menu', 'Log out'), 'url'=>array('site/logout')),
		), 'linkOptions'=>array('class'=>'fa fa-cogs')),
	);
}

// Construct the menu
$navbarItems = array(
	array(
		'class'=>'bootstrap.widgets.TbNav',
		'activateParents'=>true,
		'items'=>$leftItems,
	),
	array(
		'class'=>'bootstrap.widgets.TbNav',
		'activateParents'=>true,
		'encodeLabel'=>false, // because of icons in the Change backend menu
		'items'=>$rightItems,
		'htmlOptions'=>array('class'=>'pull-right'),
	)
);

// Render the navbar
$this->widget('bootstrap.widgets.TbNavbar', array(
	'brandLabel'=>false,
	'display'=>TbHtml::NAVBAR_DISPLAY_NONE,
	'collapse'=>true,
	'items'=>$navbarItems));
