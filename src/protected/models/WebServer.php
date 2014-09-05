<?php

/**
 * Represents a web server. It provides methods for testing that the server 
 * behaves as expected (e.g. requires authentication).
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class WebServer
{
	
	/**
	 * @var string the server hostname
	 */
	private $_hostname;
	
	/**
	 * @var int the server port
	 */
	private $_port;
	
	/**
	 * @var \Zend\Http\Client() the HTTP client used
	 */
	private $_httpClient;
	
	/**
	 * Class constructor. It instantiates the HTTP client.
	 * @param string $hostname the server hostname
	 * @param int $port the server port
	 */
	public function __construct($hostname, $port)
	{
		$this->_hostname = $hostname;
		$this->_port = $port;
		$this->_httpClient = new Zend\Http\Client();
	}
	
	/**
	 * @return boolean whether the web server requires authentication
	 */
	public function requiresAuthentication()
	{
		return $this->getResponseStatusCode($this->createBasicRequest()) === 401;
	}
	
	/**
	 * @return string|false the authentication realm of the server, or false 
	 * if no authentication is requested
	 */
	public function getAuthenticationRealm()
	{
		$response = $this->silentDispatch($this->createBasicRequest());

		if ($response)
			foreach ($response->getHeaders() as $header)
				if ($header instanceof Zend\Http\Header\WWWAuthenticate)
					return $this->parseRealm($header);

		return false;
	}
	
	/**
	 * Checks that the specified credentials are valid
	 * @param string $username the username
	 * @param string $password the password
	 * @return boolean whether authentication succeeded
	 */
	public function checkCredentials($username, $password)
	{
		$request = $this->createBasicRequest();
		$this->_httpClient->setAuth($username, $password);

		$statusCode = $this->getResponseStatusCode($request);
		$this->_httpClient->clearAuth();

		return $statusCode !== 401;
	}
	
	/**
	 * @return string the hostname:port combination
	 */
	public function getHostInfo()
	{
		return Backend::normalizeAddress($this->_hostname).':'.$this->_port;
	}

	/**
	 * @return \Zend\Http\Request a base request object
	 */
	private function createBasicRequest()
	{
		$request = new \Zend\Http\Request();
		return $request->setUri('http://'.$this->getHostInfo().'/');
	}
	
	/**
	 * Dispatches the specified request and returns the response. If any 
	 * exceptions occur, null is returned
	 * @param \Zend\Http\Request $request the request
	 * @return \Zend\Http\Response the response, or null if the request failed
	 */
	private function silentDispatch($request)
	{
		try
		{
			return $this->_httpClient->dispatch($request);
		}
		catch (Exception $e)
		{
			unset($e);
			return null;
		}
	}
	
	/**
	 * Dispatches the specified request and returns the response status code
	 * @param \Zend\Http\Request $request the request
	 * @return int|false the response code, or false if the request failed
	 */
	private function getResponseStatusCode($request)
	{
		$response = $this->silentDispatch($request);

		if ($response)
			return $response->getStatusCode();
		else
			return false;
	}
	
	/**
	 * Parses the actual realm from the value of the WWW-Authenticate header
	 * @param \Zend\Http\Header\WWWAuthenticate $authenticateHeader the header
	 * @return string the authentication realm (an empty string if it was 
	 * malformed)
	 */
	private function parseRealm($authenticateHeader)
	{
		$parts = explode('=', $authenticateHeader->value);

		// Sanity check, otherwise the rest of the method won't work
		if (count($parts) !== 2)
			return '';

		$realm = $parts[1];

		// Remove eventual citation marks
		if (strpos($realm, '"') !== false)
			return substr($realm, 1, strlen($realm) - 2);
		else
			return $realm;
	}

}
