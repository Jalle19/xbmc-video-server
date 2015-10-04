<?php

// Cache the contents until the translations or the modal content changes
$cacheDependency = new CChainedCacheDependency(array(
	new CFileCacheDependency(Yii::app()->basePath.'/messages'),
	new CFileCacheDependency(realpath(__DIR__.'/_powerOffModalContent.php'))));

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
	$this->renderPartial('//layouts/_powerOffModalContent');
	$this->endCache();
}
