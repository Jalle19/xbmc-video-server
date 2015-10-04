<?php

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