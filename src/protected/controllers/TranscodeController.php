<?php

use Symfony\Component\Process\Process;

/**
 * Handles the actual transcoding process. When a request comes in through the 
 * transcode action the FFMPEG transcoder is spawned in the background, it's 
 * output being piped back to the caller (us) which is then served to the 
 * browser as raw video data.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * TODO: Investigate why the transcoder process sometimes won't die
 */
class TranscodeController extends Controller
{

	/**
	 * @var Process the transcoder process
	 */
	private $_transcoder;

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		// We will perform the connection handling ourselves
		set_time_limit(0);
		ignore_user_abort(true);

		parent::init();
	}

	/**
	 * Serves the transcoded video data based on the specified source and 
	 * transcoder preset
	 * @param string $source the video source URL
	 * @param int $presetId the preset ID to use
	 */
	public function actionTranscode($source, $presetId)
	{
		// Load the preset and the helper
		$preset = $this->loadPreset($presetId);
		$ffmpegHelper = new FFMPEGHelper($preset);

		// Fix the source URL so it works with ffmpeg
		$source = $ffmpegHelper->escapeSource($source);

		// Create the command
		$command = 'exec avconv -i '.$source.' '.$ffmpegHelper->getTranscoderArguments().' pipe:1';

		// Start the process
		$this->log('Starting transcode process for "%s"', $source);

		header('Content-Type: '.$preset->getMIMEType());

		$this->_transcoder = new Process($command);
		$this->_transcoder->setTimeout(3600 * 24);
		$this->_transcoder->start();

		$this->_transcoder->wait(function($type, $buffer) {
			$this->handleOutput($type, $buffer);
		});

		$this->handleConnectionAborted();
	}

	/**
	 * Callback which is triggered when the transcoder has something to output. 
	 * We output the data to the browser without any buffering in between
	 * @param mixed $type the type of output
	 * @param mixed $buffer the buffer contents
	 */
	private function handleOutput($type, $buffer)
	{
		if ($type !== Process::ERR)
		{
			echo $buffer;

			// Flush output
			if (ob_get_length())
			{
				@ob_flush();
				@flush();
				@ob_end_flush();
			}
			@ob_start();

			$this->handleConnectionAborted();
		}
	}

	/**
	 * Handles connection aborts. This happens when the user leaves the 
	 * details page for the item being transcoded. We terminate the background 
	 * process here.
	 */
	private function handleConnectionAborted()
	{
		if (connection_aborted() && $this->_transcoder->isRunning())
			$this->_transcoder->stop(0);
	}

	/**
	 * Loads and returns the specified transcoder preset
	 * @param int $id the preset iD
	 * @return TranscoderPreset
	 * @throws CHttpException if the preset is not found
	 */
	private function loadPreset($id)
	{
		$preset = TranscoderPreset::model()->findByPk($id);

		if (!$preset)
			throw new InvalidRequestException();

		return $preset;
	}

}
