<?php

/**
 * Data provider for library list views. It is mainly a wrapper for 
 * CArrayDataProvider
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class LibraryDataProvider extends CArrayDataProvider
{

	/**
	 * Class constructor
	 * @param array $rawData the data
	 * @param string $keyField the data field that should be used as key
	 * @param array $config the component configuration (optional)
	 */
	public function __construct($rawData, $keyField, $config = array())
	{
		// Optionally apply pagination, unless it has been explicitly disabled
		$pagesize = Setting::getValue('pagesize');
		if (!isset($config['pagination']))
			$config['pagination'] = $pagesize ? array('pageSize'=>$pagesize) : false;

		$config['keyField'] = $keyField;

		parent::__construct($rawData, $config);
	}

}