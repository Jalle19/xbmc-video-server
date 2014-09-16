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
	public static function renderDisplayModeToggle($summary, $context)
	{
		?>
		<div class="row-fluid">
			<div class="span12 pull-right display-mode-toggle">
				<?php 
				
				echo $summary;
	
				/* @var $ctrl MediaController */
				$ctrl = Yii::app()->controller;
				
				// Get the current display mode so we can show an icon next to it
				$currentMode = $ctrl->getDisplayMode($context);
				
				echo TbHtml::buttonDropdown(Yii::t('DisplayMode', 'Display mode'), array(
					array('label'=>Yii::t('DisplayMode', 'Grid view'), 'url'=>array(
						'setDisplayMode', 'mode'=>DisplayMode::MODE_GRID, 'context'=>$context), 
						'icon'=>$currentMode ===  DisplayMode::MODE_GRID ? TbHtml::ICON_OK : ''),
					array('label'=>Yii::t('DisplayMode', 'List view'), 'url'=>array(
						'setDisplayMode', 'mode'=>DisplayMode::MODE_LIST, 'context'=>$context), 
						'icon'=>$currentMode ===  DisplayMode::MODE_LIST? TbHtml::ICON_OK : ''),
				), array(
					'color'=>TbHtml::BUTTON_COLOR_INFO,
					'class'=>'fa fa-bars'
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
