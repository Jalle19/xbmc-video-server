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
	
				echo TbHtml::buttonDropdown('Display mode', array(
					array('label'=>Yii::t('Display mode', 'Grid view'), 'url'=>array(
						'setDisplayMode', 'mode'=>MediaController::DISPLAY_MODE_GRID)),
					array('label'=>Yii::t('Display mode', 'List view'), 'url'=>array(
						'setDisplayMode', 'mode'=>MediaController::DISPLAY_MODE_LIST)),
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
		return round($rating, 1);
	}

	/**
	 * Formats a runtime
	 * @param int $runtime runtime in minutes
	 * @return string the runtime string
	 */
	public static function formatRuntime($runtime)
	{
		return (int)($runtime / 60).' min';
	}

}
