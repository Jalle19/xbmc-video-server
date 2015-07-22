<?php

use SimpleJsonRpcClient\Client\HttpPostClient as JsonRPCClient;

/**
 * Application component for accessing XBMC's JSON-RPC API and related functions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class XBMC extends CApplicationComponent
{
	
	/**
	 * @var Backend the current backend
	 */
	private $_backend;
	
	/**
	 * @var JsonRPCClient the JSON-RPC client
	 */
	private $_client;
	
	/**
	 * @var VFSHelper the VFS helper
	 */
	private $_vfsHelper;
	
	/**
	 * Initializes the component
	 */
	public function init()
	{
		// Connect to the current backend
		$this->_backend = Yii::app()->backendManager->getCurrent();
		
		$hostname = Backend::normalizeAddress($this->_backend->hostname);
		$endpoint = 'http://'.$hostname.':'.$this->_backend->port.'/jsonrpc';

		$clientFlags = JsonRPCClient::FLAG_ATTEMPT_UTF8_RECOVERY;
		$clientOptions = array('timeout'=>Setting::getInteger('requestTimeout'));
		
		$this->_client = new JsonRPCClient($endpoint, 
				$this->_backend->username, $this->_backend->password, 
				$clientFlags, $clientOptions);
		
		// Initialize the VFS helper
		$this->_vfsHelper = new VFSHelper();

		parent::init();
	}
	
	/**
	 * Checks whether the current backend meets the minimum version requirements
	 * @return boolean
	 */
	public function meetsMinimumRequirements()
	{
		return $this->getVersion() >= Yii::app()->params['minimumBackendVersion'];
	}
	
	/**
	 * Wrapper for performRequestInternal(). It caches the results indefinitely 
	 * if the "cacheApiCalls" setting is enabled.
	 * @param string $method
	 * @param mixed $params
	 * @param integer $id
	 */
	public function performRequest($method, $params = null, $id = 0)
	{
		if (Setting::getBoolean('cacheApiCalls'))
		{
			// Calculate a unique cache ID for this API call to this backend
			$cacheId = md5(serialize($this->_backend->attributes).
					serialize($method).
					serialize($params).
					serialize($id).
					1); // increment to invalidate all previous caches

			$result = Yii::app()->apiCallCache->get($cacheId);

			// Not found in cache
			if ($result === false)
			{
				$result = $this->performRequestUncached($method, $params, $id);
				
				// Store the raw response instead of the object
				Yii::app()->apiCallCache->set($cacheId, $result->getRawResponse());
			}
			else
			{
				// Recreate the full object based on the stored JSON
				$result = new SimpleJsonRpcClient\Response\Response($result);
			}
			
			return $result;
		}
		else
			return $this->performRequestUncached($method, $params, $id);
	}
	
	/**
	 * Wrapper for \SimpleJsonRpcClient\Request
	 * @param string $method
	 * @param mixed $params
	 * @param integer $id
	 * @return SimpleJsonRpcClient\Response\Response
	 */
	public function performRequestUncached($method, $params = null, $id = 0)
	{
		try
		{
			$request = new SimpleJsonRpcClient\Request\Request($method, $params, $id);
			
			return $this->_client->sendRequest($request);
		}
		catch (SimpleJsonRpcClient\Exception\BaseException $e)
		{
			$this->handleRequestException($e, $request);
		}
	}
	
	/**
	 * Sends a notification
	 * @param string $method
	 * @param mixed $params
	 */
	public function sendNotification($method, $params = null)
	{
		try
		{
			$notification = new SimpleJsonRpcClient\Request\Notification($method, $params);
			
			$this->_client->sendNotification($notification);
		}
		catch (SimpleJsonRpcClient\Exception\BaseException $e)
		{
			$this->handleRequestException($e, $notification);
		}
	}
	
	/**
	 * Handles exceptions from the JSON-RPC client. The exceptions are re-
	 * thrown as CHttpException so we can provide a less confusing error 
	 * message. The exact error and the request that caused it will be logged 
	 * separately in the system log when available.
	 * @param SimpleJsonRpcClient\Exception\BaseException $exception
	 * @param SimpleJsonRpcClient\Request\BaseRequest $request the request that 
	 * triggered the exception
	 * @throws CHttpException always
	 */
	private function handleRequestException($exception, $request)
	{
		/**
		 * Formats the object into pretty-printed JSON
		 * @param $object the data object
		 */
		$formatData = function($object)
		{
			return CHtml::tag('pre', array(), json_encode($object, JSON_PRETTY_PRINT));
		};
		
		// Log the detailed error data for easier debugging
		if ($exception instanceof SimpleJsonRpcClient\Exception\ResponseErrorException)
		{
			$data = $formatData($exception->getData());
			$message = 'Response error data from the upcoming exception: '.$data;
			
			// Include the original request body in the message. We'll need 
			// to reincode it in order to get it prettyprinted
			$requestBody = $formatData(json_decode($request->__toString()));
			$message .= PHP_EOL.'The request that triggered the exception was:'.PHP_EOL.$requestBody;

			Yii::log($message, CLogger::LEVEL_ERROR, 'jsonrpc');
		}

		$message = 'Exception caught while calling XBMC API: '.$exception->getMessage().' ('.$exception->getCode().')';

		throw new CHttpException(500, $message);
	}
	
	/**
	 * @return VFSHelper the VFS helper
	 */
	public function getVFSHelper()
	{
		return $this->_vfsHelper;
	}

	/**
	 * Returns the major version number of the currently used backend.
	 * @return int the version number
	 */
	private function getVersion()
	{
		$version = $this->performRequest('Application.GetProperties', array(
			'properties'=>array('version')));

		return $version->result->version->major;
	}
	
}
