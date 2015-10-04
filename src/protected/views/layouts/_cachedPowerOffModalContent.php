<?php

// Cache the contents
$cacheDependency = new CFileCacheDependency(Yii::app()->basePath.'/messages');
$cacheDuration = 60 * 60 * 24 * 365;

if ($this->beginCache('PowerOffModal', array(
	'dependency'=>$cacheDependency,
	'duration'=>$cacheDuration,
	'varyByExpression'=>function() { 
		return implode('_', array(
			Yii::app()->user->role,
			Yii::app()->language,
			implode('_', Yii::app()->powerOffManager->getAllowedActions()),
		));
	}
)))
{
	$items = array();
	$actions = array(
		array(PowerOffManager::SHUTDOWN, 'backend/shutdown', Yii::t('PowerOff', 'Shutdown')),
		array(PowerOffManager::SUSPEND, 'backend/suspend', Yii::t('PowerOff', 'Suspend')),
		array(PowerOffManager::HIBERNATE, 'backend/hibernate', Yii::t('PowerOff', 'Hibernate')),
		array(PowerOffManager::REBOOT, 'backend/reboot', Yii::t('PowerOff', 'Reboot')),
	);

	foreach ($actions as $action)
	{
		list($action, $url, $label) = $action;
		if (Yii::app()->powerOffManager->isActionAllowed($action))
		{
			$items[] = array(
				'label'=>$label,
				'url'=>array($url)
			);
		}
	}

	echo TbHtml::alert(
		TbHtml::ALERT_COLOR_WARNING,
		Yii::t('PowerOff', 'Turning off the backend will break the connection for all users currently using the backend'),
		array('closeText'=>false));

	echo TbHtml::stackedTabs($items);

	$this->endCache();
}
