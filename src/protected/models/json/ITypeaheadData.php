<?php

/**
 * Interface for items that should be renderable in a typeahead from field
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
interface ITypeaheadData
{

	/**
	 * @return string the name of the item
	 */
	public function getName();
	
}
