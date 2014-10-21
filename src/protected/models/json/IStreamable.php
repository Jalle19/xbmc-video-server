<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author stenvals
 */
interface IStreamable
{

	/**
	 * Returns the streamable media items that this item represents, e.g. a 
	 * season would return its episodes, a TV show would return all the 
	 * episodes of that season
	 * @return File[] the media items
	 */
	public function getStreamableItems();

	/**
	 * @return string the display name for the item 
	 */
	public function getDisplayName();

	/**
	 * Returns a set of links to the media items
	 * @return ItemLink[] the item links
	 */
	public function getItemLinks();
}
