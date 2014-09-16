<?php

// Cache the contents
$cacheDependency = new CFileCacheDependency(Yii::app()->basePath.'/messages');
$cacheDuration = 60 * 60 * 24 * 365;

if ($this->beginCache('ChangeLanguageModal', array(
	'dependency'=>$cacheDependency,
	'duration'=>$cacheDuration,
	'varyByExpression'=>function() { 
		return implode('_', array(
			Yii::app()->user->role,
			intval(Yii::app()->powerOffManager->shutdownAllowed()),
			intval(Yii::app()->powerOffManager->suspendAllowed()),
			intval(Yii::app()->powerOffManager->hibernateAllowed()),
			intval(Yii::app()->powerOffManager->rebootAllowed()),
		));
	}
)))
{
	$actions = array();

	if (Yii::app()->powerOffManager->shutdownAllowed())
	{
		$actions[] = array(
			'label'=>Yii::t('PowerOff', 'Shutdown'), 
			'url'=>array('backend/shutdown')
		);
	}

	if (Yii::app()->powerOffManager->suspendAllowed())
	{
		$actions[] = array(
			'label'=>Yii::t('PowerOff', 'Suspend'), 
			'url'=>array('backend/suspend')
		);
	}

	if (Yii::app()->powerOffManager->hibernateAllowed())
	{
		$actions[] = array(
			'label'=>Yii::t('PowerOff', 'Hibernate'), 
			'url'=>array('backend/hibernate')
		);
	}

	if (Yii::app()->powerOffManager->rebootAllowed())
	{
		$actions[] = array(
			'label'=>Yii::t('PowerOff', 'Reboot'), 
			'url'=>array('backend/reboot')
		);
	}

	echo TbHtml::alert(
		TbHtml::ALERT_COLOR_WARNING,
		Yii::t('PowerOff', 'Turning off the backend will break the connection for all users currently using the backend'),
		array('closeText'=>false));

	echo TbHtml::stackedTabs($actions);

	$this->endCache();
}
