<?php

/**
 * Provides access to the applications main configuration
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Settings extends CApplicationComponent
{

	/**
	 * @var array the parsed configuration
	 */
	private $_config;

	/**
	 * Initializes the component. Settings are loaded here
	 */
	public function init()
	{
		$properties = Config::model()->findAll();

		foreach ($properties as $property)
			$this->_config[$property->property] = $property->value;

		parent::init();
	}

	/**
	 * Checks if the application can be considered configured. It does this by 
	 * checking for a configuration variable that is only present in the 
	 * default configuration
	 * @return boolean
	 */
	public function isConfigured()
	{
		return (boolean)$this->_config['isConfigured'];
	}

	/**
	 * Returns a configuration value, or null if it is not set
	 * @param string $value
	 * @return mixed
	 */
	public function get($value)
	{
		if (isset($this->_config[$value]))
			return $this->_config[$value];
		else
			return null;
	}

}