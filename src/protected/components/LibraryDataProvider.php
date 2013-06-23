<?php

/**
 * Data provider for library list views. It is mainly a wrapper for 
 * CArrayDataProvider
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class LibraryDataProvider extends CArrayDataProvider
{

	/**
	 * Class constructor
	 * @param array $rawData the data
	 * @param string $keyField the data field that should be used as key
	 */
	public function __construct($rawData, $keyField)
	{
		parent::__construct($rawData, array(
			'keyField'=>$keyField,
			'pagination'=>false,
		));
	}

}