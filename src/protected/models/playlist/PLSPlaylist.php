<?php

/**
 * Represents an PLS playlist
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PLSPlaylist extends Playlist
{

	public function __toString()
	{
		ob_start();

		// Header
		echo '[playlist]'.PHP_EOL;

		// Items
		$i = 1;
		foreach ($this->_items as $item)
		{
			echo "File$i=".$item->location.PHP_EOL;
			echo "Title$i=".$item->title.PHP_EOL;
			echo "Length$i=".$item->runtime.PHP_EOL;

			++$i;
		}

		// Footer
		echo "NumberOfEntries=$i".PHP_EOL;
		echo 'Version=2'.PHP_EOL;

		return ob_get_clean();
	}

	public function getExtension()
	{
		return 'pls';
	}

	public function getMIMEType()
	{
		return 'audio/x-scpls';
	}

}
