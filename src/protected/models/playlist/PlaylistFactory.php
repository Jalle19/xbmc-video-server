<?php

/**
 * Playlist factory
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PlaylistFactory
{

	private function __construct()
	{
		
	}

	/**
	 * Factory method for creating playlist objects
	 * @param string $fileName
	 * @return Playlist the playlist object
	 */
	public static function create($fileName)
	{
		$format = Playlist::TYPE_M3U;

		switch ($format)
		{
			case Playlist::TYPE_M3U:
				return new M3UPlaylist($fileName);
		}
	}

	/**
	 * @return array the valid playlist formats
	 */
	public static function getTypes()
	{
		return array(
			Playlist::TYPE_M3U=>'M3U',
		);
	}

}
