<?php

/**
 * Form model for the change language form
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ChangeLanguageForm extends CFormModel
{

	/**
	 * @var string the language
	 */
	public $language;

	/**
	 * Initializes the model
	 */
	public function init()
	{
		// Preselect the current language
		$this->language = Yii::app()->language;

		parent::init();
	}

	/**
	 * @return array the validation rules for this controller
	 */
	public function rules()
	{
		return array(
			array('language', 'required'),
			array('language', 'in', 'range'=>array_keys(Yii::app()->languageManager->getAvailableLanguages())),
		);
	}

	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'language'=>Yii::t('ChangeLanguageForm', 'Language'),
			'setDefault'=>Yii::t('ChangeLanguageForm', 'Set as default for the current user'),
		);
	}

}
