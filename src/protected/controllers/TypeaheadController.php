<?php

use \yiilazyimage\components\LazyImage as LazyImage;

/**
 * Handles AJAX requests for typeahead related data
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TypeaheadController extends Controller
{

	/**
	 * Returns the typeahead data for the actor fields.
	 * @param string $mediaType filter by movies or TV shows
	 */
	public function actionGetActorNames($mediaType)
	{
		$cacheId = 'MovieFilterActorNameTypeahead_'.$mediaType;

		$this->renderJson($this->getTypeaheadSource($cacheId, function() use($mediaType)
		{
			return $this->getTypeaheadData(VideoLibrary::getActors($mediaType));
		}));
	}
	
	/**
	 * Returns the typeahead data for the movie name field
	 */
	public function actionGetMovieNames()
	{
		$cacheId = 'MovieFilterMovieNameTypeahead';

		$this->renderJson($this->getTypeaheadSource($cacheId, function()
		{
			$movies = VideoLibrary::getMovies(array('properties'=>array(
				'year', 'genre', 'thumbnail'
			)));
			
			// Modify some of the raw data so it's ready for rendering
			foreach ($movies as $movie)
			{
				$thumbnail = ThumbnailFactory::create($movie->thumbnail, Thumbnail::SIZE_VERY_SMALL);
				
				$movie->thumbnail = LazyImage::image($thumbnail->__toString());
				$movie->genre = $movie->getGenreString();
			}
			
			return $movies;
		}));
	}
	
	/**
	 * Returns the typeahead data for the TV Show name field
	 */
	public function actionGetTVShowNames()
	{
		$cacheId = 'MovieFilterTVShowNameTypeahead';

		$this->renderJson($this->getTypeaheadSource($cacheId, function()
		{
			return $this->getTypeaheadData(VideoLibrary::getTVShows(array('properties'=>array())));
		}));
	}
	
	/**
	 * Returns the typeahead data for the director field
	 */
	public function actionGetDirectorNames()
	{
		$this->renderJson(json_encode(VideoLibrary::getDirectors()));
	}

	/**
	 * Encodes the return value of the callable as JavaScript and returns that. 
	 * If cacheApiCalls is enabled, the result will be fetched from cache 
	 * whenever possible.
	 * @param string $cacheId the cache ID
	 * @param callable $callable a closure that returns the typeahead source
	 * @return string JavaScript encoded string representing the data
	 */
	private function getTypeaheadSource($cacheId, callable $callable)
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
			$typeaheadData = json_encode($callable());

		return $typeaheadData;
	}

	/**
	 * Converts the specified array of objects to an array of data that can 
	 * be serialized to JSON
	 * @param ITypeaheadData[] $sourceData the source data
	 * @return array the typeahead data
	 */
	private function getTypeaheadData($sourceData)
	{
		$typeaheadData = array();

		foreach ($sourceData as $media)
			$typeaheadData[] = $media->getName();

		return $typeaheadData;
	}

	/**
	 * Serves the specified JSON data
	 * @param string $json
	 */
	private function renderJson($json)
	{
		$this->layout = false;

		header('Content-Type: application/json');
		echo $json;
	}

}
