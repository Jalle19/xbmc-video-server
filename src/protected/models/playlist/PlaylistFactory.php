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
	 * @param string $format the desired playlist format. Defaults to null, 
	 * meaning the configured default format will be used
	 * @return Playlist the playlist object
	 * @throws InvalidRequestException if the playlist format is not supported
	 */
	public static function create($fileName, $format = null)
	{
		if ($format === null)
			$format = Setting::getString('playlistFormat');

		switch ($format)
		{
			case Playlist::TYPE_M3U:
				return new M3UPlaylist($fileName);
			case Playlist::TYPE_XSPF:
				return new XSPFPlaylist($fileName);
			case Playlist::TYPE_PLS:
				return new PLSPlaylist($fileName);
			default:
				throw new InvalidRequestException();
		}
	}

	/**
	 * @return array the valid playlist formats
	 */
	public static function getTypes()
	{
		return array(
			Playlist::TYPE_M3U=>'M3U',
			Playlist::TYPE_PLS=>'PLS',
			Playlist::TYPE_XSPF=>'XSPF',
		);
	}

}
