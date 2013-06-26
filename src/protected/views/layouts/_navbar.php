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
		)		
	), 
	array('label'=>'TV Shows', 'url'=>array('tvShow/index')),
);

// Normal users only see a log out link
$rightItems = array(
	array('label'=>'Log out', 'url'=>array('site/logout'))
);

// Administrators see the Settings and Users menus
if (Yii::app()->user->role == User::ROLE_ADMIN)
{
	$rightItems = array_merge(array(
		array('label'=>'Settings', 'url'=>array('settings/index')),
		array('label'=>'Users', 'items'=>array(
				array(
					'label'=>'Manage',
					'url'=>array('user/admin'),
				),
				array(
					'label'=>'Create new',
					'url'=>array('user/create'),
				)
			)),
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