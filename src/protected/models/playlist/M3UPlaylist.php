<?php

/**
 * Represents an M3U playlist
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @author Víctor Zabalza <vzabalza@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class M3UPlaylist extends Playlist
{
	
	public function getMIMEType()
	{
		return 'audio/x-mpegurl';
	}
	
	public function __toString()
	{
		ob_start();

		echo '#EXTM3U'.PHP_EOL;

		foreach ($this->_items as $item)
		{
			echo "#EXTINF:".$item->runtime.','.$item->title.PHP_EOL;
			echo $item->location.PHP_EOL;
		}

		return ob_get_clean();
	}

}