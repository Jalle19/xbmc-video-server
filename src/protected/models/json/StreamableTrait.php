<?php

/**
 * Provides functionality for retrieving a set of URLs to a specific media item
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
trait StreamableTrait
{
	
	/**
	 * @var string
	 */
	public $file;

	/**
	 * Returns the streamable media items that this item represents, e.g. a 
	 * season would return its episodes, a TV show would return all the 
	 * episodes of that season
	 * @return Media[] the media items
	 */
	abstract public function getStreamableItems();

	/**
	 * Returns a set of links to the media items
	 * @return ItemLink[] the item links
	 */
	public function getItemLinks()
	{
		$items = array();

		// An item may consist of multiple items, a file may consist of multiple 
		// links
		foreach ($this->getStreamableItems() as $mediaItem)
		{
			$name = $mediaItem->getDisplayName();

			// Get the links to the media. We have to omit credentials from the 
			// URLs if the user is using Internet Explorer since it won't 
			// follow such links
			$links = VideoLibrary::getVideoLinks($mediaItem->file, Browser::isInternetExplorer());
			$linkCount = count($links);

			foreach ($links as $k=> $link)
			{
				$label = $linkCount > 1 ? $name.' (#'.++$k.')' : $name;
				$items[] = new ItemLink($label, $link, $mediaItem);
			}
		}

		return $items;
	}

}
