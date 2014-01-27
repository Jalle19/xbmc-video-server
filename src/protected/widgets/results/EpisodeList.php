<?php

/**
 * Renders an EpisodeGrid with the column definitions defined by getColumns().
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class EpisodeList extends CWidget
{

	/**
	 * @var CDataProvider the data provider for the grid view
	 */
	public $dataProvider;

	/**
	 * Renders the grid view
	 */
	public function run()
	{
		$this->widget('EpisodeGrid', array(
			'type'=>TbHtml::GRID_TYPE_STRIPED,
			'dataProvider'=>$this->dataProvider,
			'template'=>'{items}',
			'columns'=>$this->getColumns()
		));
	}

	/**
	 * Returns the column definitions for the grid view. Child classes can 
	 * override this to provide additional columns.
	 * @return array the column definitions
	 */
	protected function getColumns()
	{
		return array(
			array(
				'type'=>'raw',
				'header'=>'Episode',
				'value'=>function($data) {
					// $this not available in PHP < 5.4
					Yii::app()->controller->renderPartial('_getEpisode', array('episode'=>$data));
				}
			),
			array(
				'type'=>'raw',
				'header'=>'',
				'value'=>function($data) {
					$thumbnail = new ThumbnailVideo($data->thumbnail, Thumbnail::SIZE_SMALL);
					return Thumbnail::lazyImage($thumbnail, array('class'=>'item-thumbnail episode-thumbnail'));
				}
			),
			array(
				'header'=>'Title',
				'name'=>'title',
			),
			array(
				'type'=>'raw',
				'header'=>'Plot',
				'value'=>function($data) {
					// $this not available in PHP < 5.4
					Yii::app()->controller->renderPartial('_plotStreamDetails', array('episode'=>$data));
				}
			),
			array(
				'header'=>'Runtime',
				'type'=>'html',
				'value'=>function($data) {
					Yii::app()->controller->renderPartial('//videoLibrary/_runtime', array('runtime'=>$data->runtime));
				}
			),
		);
	}

}
