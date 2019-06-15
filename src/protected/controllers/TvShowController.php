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
		return array('getEpisodePlaylist', 'getSeasonPlaylist', 'getTVShowPlaylist');
	}

	/**
	 * Lists all TV shows in the library
	 */
	public function actionIndex()
	{
		// Get the appropriate request parameters from the filter
		$filterForm = new TVShowFilterForm();
		$tvshows = VideoLibrary::getTVShows($filterForm->buildRequestParameters());

		// Redirect to the details page if this is the only TV show
		if (count($tvshows) === 1 && $filterForm->name === $tvshows[0]->label)
			$this->redirect(['details', 'id' => $tvshows[0]->getId()]);

		$dataProvider = new LibraryDataProvider($tvshows);
		$dataProvider->makeSortable();
		
		$this->render('index', [
			'dataProvider' => $dataProvider,
			'filterForm'   => $filterForm,
		]);
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

		$this->renderEpisodeList($season);
	}
	
	/**
	 * Serves a playlist containing all episodes from the specified TV show
	 * @param int $tvshowId the TV show ID
	 */
	public function actionGetTVShowPlaylist($tvshowId)
	{
		$tvshow = VideoLibrary::getTVShowDetails($tvshowId, array());

		$this->log('"%s" streamed TV show "%s"', Yii::app()->user->name, $tvshow->getDisplayName());
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
		
		$this->log('"%s" streamed season %d of "%s"', Yii::app()->user->name, $season, $seasonDetails->showtitle);
		$this->servePlaylist($seasonDetails);
	}
	
	/**
	 * Serves a playlist containing the specified episode
	 * @param int $id the episode ID
	 * @throws CHttpException if the episode's file(s) don't exist
	 */
	public function actionGetEpisodePlaylist($id, $playlistFormat)
	{
		$episode = VideoLibrary::getEpisodeDetails($id, array(
					'episode',
					'season',
					'showtitle',
					'runtime',
					'thumbnail',
					'file'));
		
		$this->log('"%s" streamed %s of "%s"', Yii::app()->user->name, $episode->getEpisodeString(), $episode->showtitle);
		$this->servePlaylist($episode, $playlistFormat);
	}
	
	/**
	 * Displays a list of the recently added episodes
	 */
	public function actionRecentlyAdded()
	{
		$episodes = VideoLibrary::getRecentlyAddedEpisodes();
		
		$dataProvider = new LibraryDataProvider($episodes);
		$dataProvider->makeSortable('dateadded DESC');

		$this->render('recentlyAdded', [
			'dataProvider' => $dataProvider,
		]);
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
