<?php

/**
 * Represents a display mode. A display mode determines how something is 
 * rendered on a page. The style determines how it is displayed and the context 
 * determines where the style applies.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class DisplayMode extends CModel
{

	const MODE_GRID = 'grid';
	const MODE_LIST = 'list';
	const CONTEXT_RESULTS = 'results';
	const CONTEXT_SEASONS = 'seasons';

	public $mode;
	public $context;
	
	/**
	 * Class constructor
	 * @param string $mode the display mode
	 * @param string $context the display mode context
	 */
	public function __construct($mode, $context)
	{
		$this->mode = $mode;
		$this->context = $context;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('mode, context', 'required'),
			array('mode', 'in', 'range'=>array(self::MODE_GRID, self::MODE_LIST)),
			array('context', 'in', 'range'=>array(self::CONTEXT_RESULTS, self::CONTEXT_SEASONS)),
		);
	}

	/**
	 * @return array the attribute names of this model
	 */
	public function attributeNames()
	{
		return array(
			'mode',
			'context',
		);
	}

}
