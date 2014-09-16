<?php

// Only use a cached version if there is just one backend configured, otherwise 
// the cache invalidation gets too complicated
if (count(Backend::model()->findAll()) === 1)
{
	$cacheDependency = new CFileCacheDependency(realpath(__DIR__.'/_navbar.php'));
	$cacheDuration = 60 * 60 * 24 * 365;

	if ($this->beginCache('MainMenu', array(
		'dependency'=>$cacheDependency,
		'duration'=>$cacheDuration,
		'varyByExpression'=>function() { 
			return implode('_', array(
				Yii::app()->user->role,
				intval(Setting::getBoolean('cacheApiCalls')),
				intval(Yii::app()->powerOffManager->powerOffAllowed()),
				intval(Yii::app()->backendManager->getCurrent() === null),
				Yii::app()->language,
				Yii::app()->baseUrl,
			));
		}
	)))
	{
		$this->renderPartial('//layouts/_navbar');
		$this->endCache();
	}
}
else
	$this->renderPartial('//layouts/_navbar');