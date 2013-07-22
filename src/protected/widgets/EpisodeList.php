<?php

/**
 * Custom grid view for the episode list. Same as in ResultGrid we don't need 
 * keys or scripts so we override the respective methods.
 *
 * @see ResultGrid
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
Yii::import('bootstrap.widgets.TbGridView');

class EpisodeList extends TbGridView
{

	/**
	 * Override parent implementation and do nothing
	 */
	public function renderKeys()
	{
		
	}

	/**
	 * Override parent implementation and do nothing
	 */
	public function registerClientScript()
	{
		
	}

}