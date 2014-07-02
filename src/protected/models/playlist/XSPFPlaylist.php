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
		$xspf = new mptre\Xspf();

		foreach ($this->_items as $item)
		{
			$trackData = array(
				'title'=>$item->title,
				'location'=>$item->location,
				'duration'=>$item->runtime);

			if ($item->image)
				$trackData['image'] = $item->image;

			$xspf->addTrack($trackData);
		}

		return $xspf->output(true);
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
