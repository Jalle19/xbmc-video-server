<?php

/**
 * Helper for dealing with VFS paths returned from the API
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class VFSHelper
{

	/**
	 * @var Backend the current backend
	 */
	private $_backend;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->_backend = Yii::app()->backendManager->getCurrent();
	}

	/**
	 * Returns the absolute URL to the specified API path
	 * @param string $path a path returned from an API call
	 * @param boolean $omitCredentials whether to omit credentials from the URL
	 * @return string
	 */
	public function getUrl($path, $omitCredentials = false)
	{
		if (!empty($this->_backend->proxyLocation))
			return $this->getProxiedUrl($path);
		else
			return $this->getNonProxiedUrl($path, $omitCredentials);
	}

	/**
	 * Returns the absolute URL to the specified path using the proxy location 
	 * specified
	 * @param string $path a path returned from an API call
	 * @return string the URL
	 */
	private function getProxiedUrl($path)
	{
		// Only use HTTPS if user has explicitly enabled it
		$scheme = 'http://';
		if (Setting::getBoolean('useHttpsForVfsUrls') && Yii::app()->request->isSecureConnection)
			$scheme = 'https://';

		// Remove the beginning "vfs/" from the path
		$path = substr($path, 4);

		return $scheme.$_SERVER['HTTP_HOST'].$this->_backend->proxyLocation.'/'.$path;
	}

	/**
	 * Returns the absolute URL to the specified path
	 * @param string $path a path returned from an API call
	 * @param boolean $omitCredentials whether to omit credentials from the URL
	 * @return string the URL
	 */
	private function getNonProxiedUrl($path, $omitCredentials)
	{
		$hostname = Backend::normalizeAddress($this->_backend->hostname);
		$port = $this->_backend->port;
		$url = 'http://{credentials}'.$hostname.':'.$port.'/'.$path;

		if ($omitCredentials)
			$url = str_replace('{credentials}', '', $url);
		else
		{
			$url = str_replace('{credentials}', $this->_backend->username.':'.
					$this->_backend->password.'@', $url);
		}

		return $url;
	}

}
