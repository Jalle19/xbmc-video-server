<?php

/**
 * Base class for rendering a filter form on index pages. It provides an 
 * abstract method (renderControls()) which should render the filter controls, 
 * as well as an overridable renderButtons() method in case the default setup 
 * is not enough.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class VideoFilter extends CWidget
{

	/**
	 * @var VideoFilterForm the form model
	 */
	public $model;

	/**
	 * @var FilterActiveForm the form
	 */
	protected $form;

	/**
	 * Renders the filter controls
	 */
	abstract protected function renderControls();

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		$this->form = $this->beginWidget('FilterActiveForm', array(
			'layout'=>TbHtml::FORM_LAYOUT_INLINE,
			'method'=>'get'));
	}

	/**
	 * Renders the widget
	 */
	public function run()
	{
		?>
		<div class="movie-filter-form well">
			<div class="row-fluid">
				<div class="span10">
					<div class="row-fluid">
						<div class="span12">
							<?php $this->renderControls(); ?>
						</div>
					</div>
				</div>

				<div class="span2">
					<div class="buttons">
						<?php $this->renderButtons(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		
		$this->endWidget();
	}

	/**
	 * Renders the form buttons
	 */
	protected function renderButtons()
	{
		echo TbHtml::submitButton(Yii::t('FilterForm', 'Apply filter'), array(
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY));

		$controller = Yii::app()->controller;
		
		// Disable when no filter is defined
		echo TbHtml::linkButton(Yii::t('FilterForm', 'Clear filter'), array(
			'color'=>TbHtml::BUTTON_COLOR_INFO,
			'disabled'=>$this->model->isEmpty(),
			'url'=>$controller->createUrl($controller->route)));
	}
	
	/**
	 * Converts the specified array of objects to an array of data that can 
	 * be serialized to JSON
	 * @param ITypeaheadData[] $sourceData the source data
	 * @return array the typeahead data
	 */
	protected function getTypeaheadData($sourceData)
	{
		$typeaheadData = array();

		foreach ($sourceData as $media)
			$typeaheadData[] = $media->getName();

		return $typeaheadData;
	}
	
	/**
	 * Encodes the return value of the callable as JavaScript and returns that. 
	 * If cacheApiCalls is enabled, the result will be fetched from cache 
	 * whenever possible.
	 * @param string $cacheId the cache ID
	 * @param callable $callable a closure that returns the typeahead source
	 * @return string JavaScript encoded string representing the data
	 */
	protected function getTypeaheadSource($cacheId, callable $callable)
	{
		// Cache the encoded JavaScript if the "cache API calls" setting is enabled
		if (Setting::getBoolean('cacheApiCalls'))
		{
			$typeaheadData = Yii::app()->apiCallCache->get($cacheId);

			if ($typeaheadData === false)
			{
				$typeaheadData = CJavaScript::encode($callable());
				
				Yii::app()->apiCallCache->set($cacheId, $typeaheadData);
			}
		}
		else
			$typeaheadData = CJavaScript::encode($callable());

		return $typeaheadData;
	}
	
	/**
	 * Returns the typeahead data for the actor fields.
	 * @param string $mediaType filter by movies or TV shows
	 * @return string the list of movies encoded as JavaScript
	 */
	protected function getActorNameTypeaheadData($mediaType)
	{
		$cacheId = 'MovieFilterActorNameTypeahead_'.$mediaType;
		
		return $this->getTypeaheadSource($cacheId, function() use($mediaType)
		{
			return $this->getTypeaheadData(VideoLibrary::getActors($mediaType));
		});
	}

}
