<?php

/**
 * Implementation of EpisodeList for the recently added TV shows view. It adds 
 * a column containing a link to the TV show to the beginning of the grid.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class RecentlyAddedEpisodeList extends EpisodeList
{

	protected function getColumns()
	{
		$showColumn = array(
			array(
				'type'=>'raw',
				'name'=>'showtitle',
				'header'=>Yii::t('RecentlyAddedEpisodes', 'TV Show'),
				'value'=>function($data) {
					return CHtml::link($data->showtitle, Yii::app()->createUrl('tvShow/details', array('id'=>$data->tvshowid)));
				}
			)
		);

		return array_merge($showColumn, parent::getColumns());
	}

}
