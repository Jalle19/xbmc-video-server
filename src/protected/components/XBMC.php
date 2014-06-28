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
	 * Initializes the component
	 */
	public function init()
	{
		// Connect to the current backend
		$this->_backend = Yii::app()->backendManager->getCurrent();
		
		$endpoint = 'http://'.$this->_backend->hostname.':'.$this->_backend->port.'/jsonrpc';

		$flags = JsonRPCClient::FLAG_ATTEMPT_UTF8_RECOVERY;
		$this->_client = new JsonRPCClient($endpoint, 
				$this->_backend->username, $this->_backend->password, $flags);

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
				$result = $this->performRequestInternal($method, $params, $id);
				
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
			return $this->performRequestInternal($method, $params, $id);
	}
	
	/**
	 * Wrapper for \SimpleJsonRpcClient\Request
	 * @param string $method
	 * @param mixed $params
	 * @param mixed $id
	 * @return \SimpleJsonRpcClient\Response
	 * @throws CHttpException if the request fails completely
	 */
	private function performRequestInternal($method, $params = null, $id = 0)
	{
		try
		{
			$request = new SimpleJsonRpcClient\Request\Request($method, $params, $id);
			
			return $this->_client->sendRequest($request);
		}
		catch (SimpleJsonRpcClient\Exception\BaseException $e)
		{
			// Rethrow as CHttpException so we get to the error page
			$message = $e->getMessage().' ('.$e->getCode().')';
			
			throw new CHttpException(500, $message);
		}
	}
	
	/**
	 * Sends a notification
	 * @param string $method
	 * @param mixed $params
	 * @throws CHttpException if the request fails
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
			// Rethrow as CHttpException so we get to the error page
			$message = $e->getMessage().' ('.$e->getCode().')';

			throw new CHttpException(500, $message);
		}
	}

	/**
	 * Returns the absolute URL to the specified API path
	 * @param string $path a path returned from an API call
	 * @param boolean $omitCredentials whether to omit the XBMC credentials in 
	 * the generated URLs
	 * @return string
	 */
	public function getAbsoluteVfsUrl($path, $omitCredentials = false)
	{
		$backend = Yii::app()->backendManager->getCurrent();
		
		// Use reverse proxy for vfs:// paths (if specified)
		$proxyLocation = $backend->proxyLocation;

		if (!empty($proxyLocation) && substr($path, 0, 3) === 'vfs')
		{
			// Only use HTTPS if user has explicitly enabled it
			$scheme = 'http://';
			if (Setting::getBoolean('useHttpsForVfsUrls') && Yii::app()->request->isSecureConnection)
				$scheme = 'https://';
			
			// Remove the beginning "vfs/" from the path
			$path = substr($path, 4);
			
			return $scheme.$_SERVER['HTTP_HOST'].$proxyLocation.'/'.$path;
		}
		else
		{
			$url = 'http://{credentials}'.$backend->hostname.':'.$backend->port.'/'.$path;
			
			if ($omitCredentials)
				$url = str_replace('{credentials}', '', $url);
			else
				$url = str_replace('{credentials}', $backend->username.':'.$backend->password.'@', $url);

			return $url;
		}
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
