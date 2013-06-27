<?php 

$leftItems = array(
	array('label'=>'Movies', 
		'items'=>array(
			array(
				'label'=>'Browse', 'url'=>array('movie/index'),
				'active'=>in_array($this->route, array('movie/index', 'movie/details')),
			),
			array(
				'label'=>'Recently added', 'url'=>array('movie/recentlyAdded'),
			),
		), 'linkOptions'=>array('class'=>'fontastic-icon-video'),
	), 
	array('label'=>'TV Shows', 'url'=>array('tvShow/index'), 
		'linkOptions'=>array('class'=>'fontastic-icon-tv')),
);

// Normal users only see a log out link
$rightItems = array(
	array('label'=>'Log out', 'url'=>array('site/logout'), 
		'linkOptions'=>array('class'=>'fontastic-icon-close'))
);

// Administrators see the Settings and Users menus
if (Yii::app()->user->role == User::ROLE_ADMIN)
{
	$rightItems = array_merge(array(
		array('label'=>'Settings', 'url'=>array('settings/index'),
			'linkOptions'=>array('class'=>'fontastic-icon-settings')),
		array('label'=>'Users', 'items'=>array(
				array(
					'label'=>'Manage',
					'url'=>array('user/admin'),
				),
				array(
					'label'=>'Create new',
					'url'=>array('user/create'),
				)
			), 'linkOptions'=>array('class'=>'fontastic-icon-user')),
	), $rightItems);
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