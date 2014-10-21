<?php

/**
 * Represents a media item that can be tied to an actual file
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * Methods from StreamableTrait:
 * @method ItemLink[] getItemLinks() the item links
 * 
 */
abstract class File extends Media implements IStreamable
{

	use StreamableTrait;

	/**
	 * @var string the VFS path
	 */
	public $file;
	
	public function getStreamableItems()
	{
		return array($this);
	}

}
