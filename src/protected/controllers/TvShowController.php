<?php

/**
 * Handles TV shows
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TvShowController extends MediaController
{
	
	/**
	 * Adds an AJAX-only filter on the renderEpisodeList action
	 * @return array the filters for this controller
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'ajaxOnly + renderEpisodeList',
		));
	}
	
	protected function getSpectatorProhibitedActions()
	{
		return array('getEpisodePlaylist', 'getSeasonPlaylist');
	}

	/**
	 * Lists all TV shows in the library
	 */
	public function actionIndex()
	{
		// Get the appropriate request parameters from the filter
		$filterForm = new TVShowFilterForm();

		$requestParameters = array(
			'properties'=>array('thumbnail', 'art', 'genre', 'year'));

		if (isset($_GET['TVShowFilterForm']))
		{
			$filterForm->attributes = $_GET['TVShowFilterForm'];

			if (!$filterForm->isEmpty() && $filterForm->validate())
				$requestParameters['filter'] = $filterForm->getFilter();
		}

		$tvshows = VideoLibrary::getTVShows($requestParameters);
		
		// Go directly to the details page if we have an exact match on the 
		// show name
		if (count($tvshows) === 1 && $filterForm->name === $tvshows[0]->label)
			$this->redirect(array('details', 'id'=>$tvshows[0]->getId()));

		$this->render('index', array(
			'dataProvider'=>new LibraryDataProvider($tvshows, 'tvshowid'),
			'filterForm'=>$filterForm));
	}
	
	/**
	 * Displays information about the specified show
	 * @param int $id the show ID
	 * @throws CHttpException if the show could not be found
	 */
	public function actionDetails($id)
	{
		$showDetails = VideoLibrary::getTVShowDetails($id, array(
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
			throw new PageNotFoundException();
		
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
	 * Renders the episode list for the specified TV show and season. This 
	 * action must only be called using AJAX.
	 */
	public function actionRenderEpisodeList($tvshowid, $season)
	{
		$season = VideoLibrary::getSeasonDetails($tvshowid, $season);
		
		$this->renderPartial('_episodes', array(
			'tvshowId'=>$tvshowid, 'season'=>$season));
	}
	
	/**
	 * Serves a playlist containing all episodes from the specified TV show 
	 * season.
	 * @param int $tvshowId the TV show ID
	 * @param int $season the season
	 */
	public function actionGetSeasonPlaylist($tvshowId, $season)
	{
		$episodes = VideoLibrary::getEpisodes($tvshowId, $season, array(
					'episode',
					'showtitle',
					'runtime',
					'file'));

		if (empty($episodes))
			throw new PageNotFoundException();

		// Construct the playlist
		$showTitle = $episodes[0]->showtitle;
		$playlist = new M3UPlaylist();
		$playlistName = $showTitle.' - Season '.$season;

		foreach ($episodes as $episode)
			foreach($this->getPlaylistItems($episode) as $item)
				$playlist->addItem($item);
		
		$this->log('"%s" streamed season %d of "%s"', Yii::app()->user->name, $season, $showTitle);

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.M3UPlaylist::sanitizeFilename($playlistName).'.m3u"');

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
			throw new PageNotFoundException();
		
		// Construct the playlist
		$playlist = new M3UPlaylist();
		$name = $episode->getDisplayName();
		
		foreach ($this->getPlaylistItems($episode) as $item)
			$playlist->addItem($item);

		$this->log('"%s" streamed %s of "%s"', Yii::app()->user->name, $episode->getEpisodeString(), $episode->showtitle);

		header('Content-Type: audio/x-mpegurl');
		header('Content-Disposition: attachment; filename="'.M3UPlaylist::sanitizeFilename($name).'.m3u"');

		echo $playlist;
	}
	
	/**
	 * Displays a list of the recently added episodes
	 */
	public function actionRecentlyAdded()
	{
		$episodes = VideoLibrary::getRecentlyAddedEpisodes();

		$this->render('recentlyAdded', array(
			'dataProvider'=>new LibraryDataProvider($episodes, 'episodeid'),
		));
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
					'plot',
					'runtime',
					'season',
					'episode',
					'streamdetails',
					'thumbnail',
					'file',
					'showtitle',
		));

		// We never want pagination here
		return new LibraryDataProvider($episodes, 'label', array(
			'pagination'=>false,
		));
	}

}