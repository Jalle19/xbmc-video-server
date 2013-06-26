<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this.
 */
class Controller extends CController
{

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
	
	/**
	 * @return array the filter definitions for this controller
	 */
	public function filters()
	{
		return array(
			'checkConfiguration',
		);
	}
	
	/**
	 * Checks that the application has been configured, and if not redirects 
	 * to the settings page.
	 * @param CFilterChain $filterChain
	 */
	public function filterCheckConfiguration($filterChain)
	{
		if (!Yii::app()->config->isConfigured())
		{
			Yii::app()->user->setFlash('error', 'You must configure the application before you can use it');

			$this->redirect(array('settings/index'));
		}

		$filterChain->run();
	}

}