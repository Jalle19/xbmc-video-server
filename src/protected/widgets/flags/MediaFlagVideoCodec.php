<?php

/**
 * Video codec flag
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaFlagVideoCodec extends MediaFlagStreamDetails
{

	protected function getIcon()
	{
		$codec = $this->video->codec;

		$icons = array(
			'h264'=>'80px-H264',
			'xvid'=>'80px-Xvid',
			'dx50'=>'80px-Divx',
			'avc1'=>'80px-Avc1');

		return array_key_exists($codec, $icons) ? $icons[$codec] : '';
	}

}