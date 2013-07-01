<?php

/**
 * Handles TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TvShowController extends Controller
{

	/**
	 * Lists all TV shows in the library
	 */
	public function actionIndex()
	{
		$properties = array('thumbnail', 'fanart', 'art');
		$tvshows = VideoLibrary::getTVShows(array(
				'properties'=>$properties));
		
		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($tvshows, 'tvshowid')));
	}
	
	/**
	 * Displays information about the specified show
	 * @param int $id the show ID
	 * @throws CHttpException if the show could not be found
	 */
	public function actionDetails($id)
	{
		$showDetails = VideoLibrary::getTVShowDetails($id, array(
			'title',
			'genre',
			'year',
			'rating',
			'plot',
			'mpaa',
			'imdbnumber',
			'thumbnail',
			'cast',
		));

		if ($showDetails === null)
			throw new CHttpException(404, 'Not found');
		
		$actorDataProvider = new CArrayDataProvider(
				$showDetails->cast, array(
			'keyField'=>'name',
			'pagination'=>array('pageSize'=>6)
		));
		
		$this->render('details', array(
			'details'=>$showDetails,
			'seasons'=>VideoLibrary::getSeasons($id),
			'actorDataProvider'=>$actorDataProvider));
	}
	
	/**
	 * Serves a playlist containing all episodes from the specified TV show 
	 * season.
	 * @param int $tvshowId the TV show ID
	 * @param int $season the season
	 */
	public function actionGetSeasonPlaylist($tvshowId, $season)
	{
		$tvshowDetails = VideoLibrary::getTVShowDetails($tvshowId, array());
		$episodes = VideoLibrary::getEpisodes($tvshowId, $season, array(
					'episode',
					'runtime',
					'file'));

		if ($tvshowDetails === null || empty($episodes))
			throw new CHttpException(404, 'Not found');

		// Construct the playlist
		$playlist = new M3UPlaylist();
		$playlistName = $tvshowDetails->label.' - Season '.$season;

		foreach ($episodes as $episode)
		{
			$name = $tvshowDetails->label.' - '.VideoLibrary::getEpisodeString($season, $episode->episode);
			$links = VideoLibrary::getVideoLinks($episode->file);
			$linkCount = count($links);

			// Most TV shows only have one file, but you never know
			foreach ($links as $k=> $link)
			{
				$label = $linkCount > 1 ? $name.' (#'.++$k.')' : $name;

				$playlist->addItem(array(
					'runtime'=>(int)$episode->runtime,
					'label'=>$label,
					'url'=>$link));
			}
		}

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.$playlistName.'.m3u"');

		echo $playlist;
	}
	
	/**
	 * Serves a playlist containing the specified episode
	 * @param int $episodeId the episode ID
	 * @throws CHttpException if the episode's file(s) don't exist
	 */
	public function actionGetEpisodePlaylist($episodeId)
	{
		$episode = VideoLibrary::getEpisodeDetails($episodeId, array(
					'episode',
					'season',
					'showtitle',
					'runtime',
					'file'));

		if ($episode === null)
			throw new CHttpException(404, 'Not found');

		// Construct the playlist
		$playlist = new M3UPlaylist();
		$name = $episode->showtitle.' - '.VideoLibrary::getEpisodeString(
						$episode->season, $episode->episode);

		$links = VideoLibrary::getVideoLinks($episode->file);
		$linkCount = count($links);

		// Most TV shows only have one file, but you never know
		foreach ($links as $k=> $link)
		{
			$label = $linkCount > 1 ? $name.' (#'.++$k.')' : $name;

			$playlist->addItem(array(
				'runtime'=>(int)$episode->runtime,
				'label'=>$label,
				'url'=>$link));
		}

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.$name.'.m3u"');

		echo $playlist;
	}
	
	/**
	 * Returns a data provider containing the episodes for the specified show 
	 * and season
	 * @param int $tvshowId the TV show ID
	 * @param int $season the season number
	 * @return \LibraryDataProvider
	 */
	public function getEpisodeDataProvider($tvshowId, $season)
	{
		$episodes = VideoLibrary::getEpisodes($tvshowId, $season, array(
					'title',
					'plot',
					'runtime',
					'season',
					'episode',
					'streamdetails',
					'thumbnail',
					'file',
		));

		return new LibraryDataProvider($episodes, 'label');
	}

}