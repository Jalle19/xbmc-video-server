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
	 */
	private $_params;
	
	/**
	 * Initializes the component
	 */
	public function init()
	{
		// Set up the JSON-RPC client
		$endpoint = 'http://'.Yii::app()->config->hostname.':'
				.Yii::app()->config->port.'/jsonrpc';

		$this->_client = new SimpleJsonRpcClient\Client($endpoint, 
				Yii::app()->config->username, Yii::app()->config->password);

		parent::init();
	}
	
	/**
	 * Wrapper for \SimpleJsonRpcClient\Request
	 * @param string $method
	 * @param mixed $params
	 * @param mixed $id
	 * @return \SimpleJsonRpcClient\Response
	 * @throws CHttpException if the request fails completely
	 */
	public function performRequest($method, $params = null, $id = 0)
	{
		try
		{
			$request = new SimpleJsonRpcClient\Request($method, $params, $id);
			
			return $this->_client->performRequest($request);
		}
		catch (SimpleJsonRpcClient\Exception $e)
		{
			// Rethrow as CHttpException so we get to the error page
			throw new CHttpException(500, $e->getMessage());
		}
	}

	/**
	 * Converts a VFS path to its absolute URL equivalent
	 * @param string $path a VFS path returned from the API
	 * @return string the URL to it
	 */
	public function getAbsoluteVfsUrl($path)
	{
		return 'http://'.Yii::app()->config->username.':'.Yii::app()->config->password.'@'
				.Yii::app()->config->hostname.':'.Yii::app()->config->port.'/'
				.$path;
	}

}