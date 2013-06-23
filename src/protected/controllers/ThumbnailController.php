<?php

/**
 * Handles thumbnails.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailController extends Controller
{

	/**
	 * Redirects to the absolute URL to a the specified thumbnail.
	 * @param string $thumbnailPath the VFS path to a thumbnail
	 */
	public function actionGet($thumbnailPath)
	{
		// TODO: Use better place holder image
		if (empty($thumbnailPath))
			echo Yii::app()->baseUrl.'/images/blank.png';

		$request = new SimpleJsonRpcClient\Request('Files.PrepareDownload', array(
			'path'=>$thumbnailPath));

		$response = $this->jsonRpcClient->performRequest($request);

		$this->redirect($this->getAbsoluteVfsUrl($response->result->details->path));
	}

}