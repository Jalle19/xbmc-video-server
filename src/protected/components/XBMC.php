<?php

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
	 * @var SimpleJsonRpcClient\Client the JSON-RPC client
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

		$this->_client = new SimpleJsonRpcClient\Client($endpoint, 
				$this->_backend->username, $this->_backend->password);

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
		if (Setting::getValue('cacheApiCalls'))
		{
			// Calculate a unique cache ID for this API call to this backend
			$cacheId = md5(serialize($this->_backend->attributes).
					serialize($method).
					serialize($params).
					serialize($id));

			$result = Yii::app()->cache->get($cacheId);

			// Not found in cache
			if ($result === false)
			{
				$result = $this->performRequestInternal($method, $params, $id);
				Yii::app()->cache->set($cacheId, $result);
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
	 * Returns the absolute URL to the specified API path
	 * @param string $path a path returned from an API call
	 * @return string
	 */
	public function getAbsoluteVfsUrl($path)
	{
		$backend = Yii::app()->backendManager->getCurrent();
		
		// Use reverse proxy for vfs:// paths (if specified)
		$proxyLocation = $backend->proxyLocation;

		if (!empty($proxyLocation) && substr($path, 0, 3) === 'vfs')
			return 'http://'.$_SERVER['HTTP_HOST'].$proxyLocation.'/'.$path;
		else
		{
			return 'http://'.$backend->username.':'.$backend->password.'@'
					.$backend->hostname.':'.$backend->port.'/'.$path;
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
