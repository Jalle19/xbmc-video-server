<?php

/**
 * Application component for accessing XBMC's JSON-RPC API and related functions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class XBMC extends CApplicationComponent
{
	
	/**
	 * @var SimpleJsonRpcClient\Client the JSON-RPC client
	 */
	private $_client;
	
	/**
	 * @var array configuration parameters
	 * TODO: Proper configuration system
	 */
	private $_params;
	
	/**
	 * Initializes the component
	 */
	public function init()
	{
		$this->_params = Yii::app()->params['xbmc'];
		
		// Set up the JSON-RPC client
		$endpoint = 'http://'.$this->_params['hostname'].':'.$this->_params['port']
				.'/jsonrpc';

		$this->_client = new SimpleJsonRpcClient\Client($endpoint, 
				$this->_params['username'], $this->_params['password']);

		parent::init();
	}
	
	/**
	 * Wrapper for \SimpleJsonRpcClient\Request
	 * @param string $method
	 * @param mixed $params
	 * @param mixed $id
	 * @return \SimpleJsonRpcClient\Response
	 */
	public function performRequest($method, $params = null, $id = 0)
	{
		$request = new SimpleJsonRpcClient\Request($method, $params, $id);
		return $this->_client->performRequest($request);
	}

	/**
	 * Converts a VFS path to its absolute URL equivalent
	 * @param string $path a VFS path returned from the API
	 * @return string the URL to it
	 */
	public function getAbsoluteVfsUrl($path)
	{
		return 'http://'.$this->_params['username'].':'.$this->_params['password'].'@'
				.$this->_params['hostname'].':'.$this->_params['port'].'/'
				.$path;
	}

}