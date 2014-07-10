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
		$tvshows = VideoLibrary::getTVShows($filterForm->buildRequestParameters());
		
		$this->renderIndex($tvshows, $filterForm);
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
	 * Wrapper for renderEpisodeList()
	 * @see renderEpisodeList()
	 */
	public function actionSeason($tvshowid, $season)
	{
		$season = VideoLibrary::getSeasonDetails($tvshowid, $season);
		
		if (!$season)
			throw new PageNotFoundException();
		
		// Get the TV show details so we can get the correct show title
		$tvshow = VideoLibrary::getTVShowDetails($tvshowid, array('year'));
		
		$this->pageTitle = Yii::t('TVShows', 'Season {season} - {showTitle}', array(
			'{season}'=>$season->season, '{showTitle}'=>$tvshow->getDisplayName()));
		
		$this->renderEpisodeList($season);
	}

	/**
	 * AJAX wrapper for renderEpisodeList.
	 * @see renderEpisodeList
	 */
	public function actionRenderEpisodeList($tvshowid, $season)
	{
		$this->layout = false;
		
		$season = VideoLibrary::getSeasonDetails($tvshowid, $season);
		
		if (!$season)
			throw new PageNotFoundException();

		$this->renderEpisodeList($season);
	}
	
	/**
	 * Serves a playlist containing all episodes from the specified TV show
	 * @param int $tvshowId the TV show ID
	 */
	public function actionGetTVShowPlaylist($tvshowId)
	{
		$tvshow = VideoLibrary::getTVShowDetails($tvshowId, array());

		if (!$tvshow)
			throw new PageNotFoundException();

		$this->log('"%s" streamed season "%s"', Yii::app()->user->name, $tvshow->getDisplayName());
		$this->servePlaylist($tvshow);
	}
	
	/**
	 * Serves a playlist containing all episodes from the specified TV show 
	 * season.
	 * @param int $tvshowId the TV show ID
	 * @param int $season the season
	 */
	public function actionGetSeasonPlaylist($tvshowId, $season)
	{
		// Get the season details so we can determine the playlist name
		$seasonDetails = VideoLibrary::getSeasonDetails($tvshowId, $season);
		
		if (!$season)
			throw new PageNotFoundException();
		
		$this->log('"%s" streamed season %d of "%s"', Yii::app()->user->name, $season, $seasonDetails->showtitle);
		$this->servePlaylist($seasonDetails);
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
					'thumbnail',
					'file'));

		if ($episode === null)
			throw new PageNotFoundException();
		
		$this->log('"%s" streamed %s of "%s"', Yii::app()->user->name, $episode->getEpisodeString(), $episode->showtitle);
		$this->servePlaylist($episode);
	}
	
	/**
	 * Displays a list of the recently added episodes
	 */
	public function actionRecentlyAdded()
	{
		$episodes = VideoLibrary::getRecentlyAddedEpisodes();

		$this->render('recentlyAdded', array(
			'dataProvider'=>new LibraryDataProvider($episodes),
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
		$episodes = VideoLibrary::getEpisodes($tvshowId, $season);

		// We never want pagination here
		return new LibraryDataProvider($episodes, array(
			'pagination'=>false,
		));
	}
	
	/**
	 * Renders the list of episodes for the specified season
	 * @param Season $season the season
	 */
	private function renderEpisodeList($season)
	{
		$this->render('_episodes', array(
			'season'=>$season));
	}

}