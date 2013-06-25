<?php

/**
 * Application component for reading and writing to the main settings file
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class Config extends CApplicationComponent
{

	const CONFIG_FILE_NAME = 'config.json';

	/**
	 * @var string the path to the configuration folder
	 */
	private $_configPath;

	/**
	 * @var string the path to the configuration file
	 */
	private $_configFile;

	/**
	 * @var stdClass the parsed configuration
	 */
	private $_config;

	/**
	 * Initializes the component. Settings are loaded here
	 */
	public function init()
	{
		// Set paths
		$this->_configPath = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'
				.DIRECTORY_SEPARATOR.'config');
		$this->_configFile = $this->_configPath.DIRECTORY_SEPARATOR
				.self::CONFIG_FILE_NAME;

		// Create a default configuration file if one doesn't exist
		if (!file_exists($this->_configFile))
			$this->createDefaultConfig();

		// Load the configuration
		$this->_config = (object)CJSON::decode(file_get_contents($this->_configFile));

		parent::init();
	}

	/**
	 * Checks if the application can be considered configured. It does this by 
	 * checking for a configuration variable that is only present in the 
	 * default auto-generated config file
	 * @return boolean
	 */
	public function isConfigured()
	{
		return !isset($this->_config->incompleteConfiguration);
	}

	/**
	 * Returns a configuration value, or null if it is not set
	 * @param string $value
	 * @return mixed
	 */
	public function get($value)
	{
		if (isset($this->_config->{$value}))
			return $this->_config->{$value};
		else
			return null;
	}

	/**
	 * Saves the specified configuration
	 * @param mixed $config
	 */
	public function save($config)
	{
		file_put_contents($this->_configFile, CJSON::encode($config));
	}

	/**
	 * Creates a default configuration file
	 */
	private function createDefaultConfig()
	{
		$config = new stdClass();

		$config->hostname = 'localhost';
		$config->port = '8080';
		$config->username = 'xbmc';
		$config->password = 'xbmc';

		$config->superuser = new StdClass();
		$config->superuser->username = 'admin';
		$config->superuser->password = 'admin';

		$config->users = array();

		// This setting will not be written when the user saves the settings, 
		// thus we can use it to force the user to visit the settings page at 
		// least once
		$config->incompleteConfiguration = true;

		$this->save($config);
	}

}