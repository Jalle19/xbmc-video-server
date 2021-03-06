<?php

/**
 * Renders the seasons for a TV show
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Seasons extends CWidget
{

	/**
	 * @var TVShow the TV show 
	 */
	public $tvshow;

	/**
	 * @var Season[] the seasons
	 */
	public $seasons;
	
	/**
	 * @var string the current display mode
	 */
	public $displayMode;

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		// Determine the display mode
		
		/* @var $ctrl MediaController */
		$ctrl = Yii::app()->controller;
		$this->displayMode = $ctrl->getDisplayMode(DisplayMode::CONTEXT_SEASONS);

		// Register some required scripts
		if ($this->hasSeasons() && $this->displayMode === DisplayMode::MODE_LIST)
		{
			// If we only have one season we want the "drawer" to open 
			// automatically on page load
			if (count($this->seasons) === 1)
			{
				Yii::app()->clientScript->registerScript('PopulateSingleSeason', 
						'populateAll();', CClientScript::POS_END);
			}
		}
	}

	/**
	 * Renders the widget
	 */
	public function run()
	{
		if ($this->hasSeasons())
			$this->render('_seasons');
		else
			echo CHtml::tag('div', array('class'=>'alert alert-block alert-error'), Yii::t('TVShows', 'There are no episodes for this show'));
	}

	/**
	 * @return boolean whether we have any seasons to render
	 */
	private function hasSeasons()
	{
		return count($this->seasons) > 0;
	}

}
