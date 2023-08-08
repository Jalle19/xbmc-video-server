<?php

/**
 * Utility for triggering library updates through a WebSocket connection. A 
 * user can attach callbacks that are run when the scan is started and 
 * finished.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class LibraryUpdateListener
{

	const METHOD_ON_SCAN_STARTED = 'VideoLibrary.OnScanStarted';
	const METHOD_ON_SCAN_FINISHED = 'VideoLibrary.OnScanFinished';

	/**
	 * @var Closure event handler for METHOD_ON_SCAN_STARTED
	 */
	public $onScanStarted;

	/**
	 * @var Closure event handler for METHOD_ON_SCAN_FINISHED
	 */
	public $onScanFinished;

	/**
	 * @var Hoa\Websocket\Client the Websocket client
	 */
	private $_client;

	/**
	 * Class constructor
	 * @param Backend $backend the backend to trigger the update on
	 */
	public function __construct($backend)
	{
		$hostname = $backend->hostname;
		$port = $backend->tcp_port;

		// Create the Websocket client
		$this->_client = new Hoa\Websocket\Client(
				new Hoa\Socket\Client('tcp://'.$hostname.':'.$port));
		$this->_client->setHost(gethostname());
	}

	/**
	 * Listens for new events until the specified event is received
	 * @param string the event to wait for
	 */
	public function blockUntil($event)
	{
		// Trigger a library update when the socket has been opened
		$this->_client->on('open', function()
		{
			Yii::app()->xbmc->sendNotification('VideoLibrary.Scan');
		});

		$this->_client->on('message', function(Hoa\Event\Bucket $bucket) use ($event)
		{
			$response = $this->parseResponse($bucket);

			if ($response === null)
				return;

			// Handle events
			switch ($response->method)
			{
				case self::METHOD_ON_SCAN_STARTED:
					$this->onScanStarted->__invoke();
					break;
				case self::METHOD_ON_SCAN_FINISHED:
					$this->onScanFinished->__invoke();
					break;
			}

			if ($response->method !== $event)
				$this->_client->receive();
		});

		// Start listening (wait for one message)
		$this->_client->connect();
		$this->_client->receive();
	}

	/**
	 * @param Hoa\Event\Bucket $bucket
	 * @return stdClass the response object, or null if the response is invalid
	 */
	private function parseResponse(Hoa\Event\Bucket $bucket)
	{
		$data = $bucket->getData();
		$response = json_decode($data['message']);

		return isset($response->method) ? $response : null;
	}

}
