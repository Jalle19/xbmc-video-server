<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this.
 * 
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Controller extends CController
{

	/**
	 * @var string the page title. It is accessed through its setter and getter.
	 */
	private $_pageTitle;
	
	/**
	 * Initializes the controller. The application name is set here.
	 */
	public function init()
	{
		Yii::app()->name = Setting::getValue('applicationName');

		parent::init();
	}

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
			'requireLogin',
			'checkConfiguration',
		);
	}

	/**
	 * Checks that someone is logged in and if not redirects to the login page
	 * @param CFilterChain $filterChain
	 */
	public function filterRequireLogin($filterChain)
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(array('site/login'));

		$filterChain->run();
	}

	/**
	 * Checks that the application has been configured, and if not redirects 
	 * to the "create backend" page
	 * @param CFilterChain $filterChain
	 */
	public function filterCheckConfiguration($filterChain)
	{
		if (Yii::app()->backendManager->getCurrent() === null)
		{
			Yii::app()->user->setFlash('error', 'You must configure a backend before you can use the application');

			$this->redirect(array('backend/create'));
		}

		$filterChain->run();
	}
	
	/**
	 * Logs the message using Yii:log(). Before the message is logged it is 
	 * run through sprintf(), which means this method takes an unlimited 
	 * amount of parameters, e.g. $this->log('This is %s', 'magic');
	 * @param string $message the message
	 */
	public function log($message)
	{
		$message = call_user_func_array('sprintf', func_get_args());

		Yii::log($message, CLogger::LEVEL_INFO, get_class($this));
	}
	
	/**
	 * Register scripts needed on all pages (this method should be called from 
	 * the main layout file)
	 */
	public function registerScripts()
	{
		$cs = Yii::app()->clientScript;
		
		// Register Bootstrap scripts
		$bootstrapBaseUrl = Yii::app()->baseUrl.'/js/bootstrap';

		foreach (array('dropdown', 'alert', 'collapse') as $script)
		{
			$cs->registerScriptFile($bootstrapBaseUrl.'/bootstrap.'.$script.
					'.min.js', CClientScript::POS_END);
		}

		// Register the lazy loader
		$script = YII_DEBUG ? 'jquery.unveil.js' : 'jquery.unveil.min.js';
		$cs->registerScriptFile(Yii::app()->baseUrl
				.'/js/jquery-unveil/'.$script, CClientScript::POS_END);

		$cs->registerScript(__CLASS__.'_unveil', '
			$(".lazy").unveil(50);
		', CClientScript::POS_READY);
		
		// Register the JavaScript logger
		$cs->registerScriptFile(Yii::app()->baseUrl.'/js/js-logger.js', 
				CClientScript::POS_END);
	}
	
	/**
	 * Redirects to the user's URL referrer, or to the specified fallback URL 
	 * if no referrer is available. $fallback can be anything that can be 
	 * passed to CController::redirect().
	 * @see CController::redirect()
	 * @param mixed $fallback the fallback URL
	 */
	public function redirectToPrevious($fallback)
	{
		$returnTo = Yii::app()->request->urlReferrer;

		if ($returnTo)
			$this->redirect($returnTo);
		else
			$this->redirect($fallback);
	}

}