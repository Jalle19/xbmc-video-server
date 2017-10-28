<?php

/**
 * Represents an XSPF playlist
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class XSPFPlaylist extends Playlist
{

	public function __toString()
	{
		$playlist = new \Jalle19\xsphpf\Playlist();

		foreach ($this->_items as $item)
		{
			$track = new \Jalle19\xsphpf\Track($item->location, $item->title, $item->runtime);
			
			if ($item->image)
				$track->setImage($item->image);

			$playlist->addTrack($track);
		}

		return $playlist->__toString();
	}

	public function getExtension()
	{
		return 'xspf';
	}

	public function getMIMEType()
	{
		return 'application/xspf+xml';
	}

}
