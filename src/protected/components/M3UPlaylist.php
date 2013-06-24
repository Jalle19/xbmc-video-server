<?php

/**
 * Represents an M3U playlist. Once populated using addItem() it can be used as 
 * a string
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class M3UPlaylist
{

	/**
	 * @var array the playlist items
	 */
	private $_items = array();

	/**
	 * Adds an item to the playlist
	 * @param array $item the item
	 */
	public function addItem($item)
	{
		$this->_items[] = $item;
	}

	/**
	 * Generates the playlist data
	 * @return stromg
	 */
	public function __toString()
	{
		ob_start();

		echo '#EXTM3U'.PHP_EOL;

		foreach ($this->_items as $item)
		{
			echo "#EXTINF:".$item['runtime'].','.$item['label'].PHP_EOL;
			echo $item['url'].PHP_EOL;
		}

		return ob_get_clean();
	}

}