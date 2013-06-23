<?php

/**
 * Handles movie-related actions
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MovieController extends Controller
{

	/**
	 * Lists all movies in the library
	 */
	public function actionIndex()
	{
		// Get the list of movies along with their thumbnails
		$response = $this->performRequest('VideoLibrary.GetMovies', array(
			'properties'=>array('thumbnail')));

		$movies = $response->result->movies;

		// Sort by name (XBMC fails to sort certain movies)
		usort($movies, function($a, $b) {
			return strcmp($a->label, $b->label);
		});

		$dataProvider = new CArrayDataProvider($movies, array(
			'keyField'=>'movieid',
			'pagination'=>false));

		$this->render('index', array(
			'dataProvider'=>$dataProvider));
	}

	/**
	 * Returns the absolute URL to the specified thumbnail (VFS path).
	 * @param string $thumbnailPath the VFS path
	 * @return mixed the URL to the image or false if the movie has no thumbnail
	 */
	public function getMovieThumbnail($thumbnailPath)
	{
		if (empty($thumbnailPath))
			return false;

		$request = new SimpleJsonRpcClient\Request('Files.PrepareDownload', array(
			'path'=>$thumbnailPath));

		$response = $this->jsonRpcClient->performRequest($request);

		return $this->getAbsoluteVfsUrl($response->result->details->path);
	}

}