<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this.
 */
class Controller extends CController
{

	/**
	 * @var string the default layout for the controller view
	 */
	public $layout = '//layouts/main';

	/**
	 * @var array context menu items. This property will be assigned to 
	 * {@link CMenu::items}.
	 */
	public $menu = array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this 
	 * property will be assigned to {@link CBreadcrumbs::links}. Please refer 
	 * to {@link CBreadcrumbs::links} for more details on how to specify this 
	 * property.
	 */
	public $breadcrumbs = array();

	/**
	 * @var string the page title. It is accessed through its setter and getter.
	 */
	private $_pageTitle;

	/**
	 * Getter for _pageTitle
	 * @return string
	 */
	public function getPageTitle()
	{
		return !$this->_pageTitle ? Yii::app()->name : $this->_pageTitle;
	}

	/**
	 * Setter for _pageTitle
	 * @param string $pageTitle
	 */
	public function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle.' - '.Yii::app()->name;
	}

}