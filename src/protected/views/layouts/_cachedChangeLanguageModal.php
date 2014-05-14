<?php

/* @var $this CController */

$cacheDependency = new CFileCacheDependency(Yii::app()->basePath.'/messages');
$cacheDuration = 60 * 60 * 24 * 365;

if ($this->beginCache('ChangeLanguageModal', array(
	'dependency'=>$cacheDependency,
	'duration'=>$cacheDuration,
	'varyByExpression'=>function() { 
		return implode('_', array(
			Yii::app()->user->role,
			Yii::app()->language,
		));
	}
)))
{
	$this->widget('bootstrap.widgets.TbModal', array(
		'id'=>'change-language-modal',
		'header'=>Yii::t('Language', 'Change language'),
		'closeText'=>false,
		'content'=>$this->renderPartial('//layouts/_changeLanguageModalContent', array(
			'model'=>new ChangeLanguageForm()), true),
	));
	
	$this->endCache();
}
