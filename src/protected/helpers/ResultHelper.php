<?php

/**
 * Description of ResultHelper
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ResultHelper
{

	/**
	 * Returns the configuration for the pager to be used in result views.
	 * @return array
	 */
	public static function getDefaultPagerConfiguration()
	{
		return array(
			'class'=>'bootstrap.widgets.TbPager',
			'maxButtonCount'=>10,
			'htmlOptions'=>array('align'=>TbHtml::PAGINATION_ALIGN_RIGHT),
		);
	}
	
	/**
	 * Renders the specified summary along with a display mode toggle
	 * @param string $summary the HTML for the original summary
	 */
	public static function renderDisplayModeToggle($summary)
	{
		?>
		<div class="row">
			<div class="span6 pull-right display-mode-toggle">
				<?php 
				
				echo $summary;
	
				// Get the current display mode so we can show an icon next to it
				$currentMode = Yii::app()->controller->getDisplayMode();
				
				echo TbHtml::buttonDropdown(Yii::t('DisplayMode', 'Display mode'), array(
					array('label'=>Yii::t('DisplayMode', 'Grid view'), 'url'=>array(
						'setDisplayMode', 'mode'=>MediaController::DISPLAY_MODE_GRID), 
						'icon'=>$currentMode ===  MediaController::DISPLAY_MODE_GRID ? TbHtml::ICON_OK : ''),
					array('label'=>Yii::t('DisplayMode', 'List view'), 'url'=>array(
						'setDisplayMode', 'mode'=>MediaController::DISPLAY_MODE_LIST), 
						'icon'=>$currentMode ===  MediaController::DISPLAY_MODE_LIST ? TbHtml::ICON_OK : ''),
				), array(
					'color'=>TbHtml::BUTTON_COLOR_INFO,
					'icon'=>'reorder',
				));
				
				?>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Formats a rating
	 * @param float $rating the rating
	 * @return string the formatted rating
	 */
	public static function formatRating($rating)
	{
		return number_format($rating, 1);
	}

}
