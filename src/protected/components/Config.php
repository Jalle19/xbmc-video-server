<?php

/**
 * Description of Config
 *
 * @author Sam
 */
class Config extends CApplicationComponent
{

	private $_configPath;
	private $_configFile;
	private $_config;

	public function init()
	{
		$this->_configPath = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'
				.DIRECTORY_SEPARATOR.'config');
		$this->_configFile = $this->_configPath.DIRECTORY_SEPARATOR
				.'config.ini';

		if ($this->isConfigured())
			$this->_config = (object)parse_ini_file($this->_configFile);

		parent::init();
	}

	public function isConfigured()
	{
		return file_exists($this->_configFile);
	}

	public function save($data)
	{
		$this->writeIniFile($data);
	}

	public function __get($name)
	{
		if (!property_exists(__CLASS__, $name) && isset($this->_config->{$name}))
			return $this->_config->{$name};
		else
			return parent::__get($name);
	}

	private function writeIniFile($data)
	{
		$content = "";

		foreach ($data as $key=> $elem)
		{
			if (is_array($elem))
			{
				for ($i = 0; $i < count($elem); $i++)
				{
					$content .= $key."[] = \"".$elem[$i]."\"\n";
				}
			}
			else if ($elem == "")
				$content .= $key." = \n";
			else
				$content .= $key." = \"".$elem."\"\n";
		}

		if (@file_put_contents($this->_configFile, $content) === false)
			throw new CException('Could not write configuration file (most likely insufficient permissions)');
	}

}