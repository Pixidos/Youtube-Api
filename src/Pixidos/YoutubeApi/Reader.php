<?php

namespace Pixidos\YoutubeApi;

use Composer\CaBundle\CaBundle;
use DateInterval;
use GuzzleHttp\Client;
use Nette;
use Pixidos\YoutubeApi\Exceptions\YoutubeApiException;
use Tracy\Debugger;


class Reader extends Nette\Object
{
	/** @var string */
	const FETCH_URL = 'https://www.googleapis.com/youtube/v3/videos?key=%s&part=snippet,contentDetails&id=%s';

	/** @var string */
	private $apiKey;

	/** @var Client */
	private $httpClient;


	public function __construct($apiKey, Client $httpClient = NULL)
	{
		$this->apiKey = $apiKey;
		if ($httpClient === NULL) {
			$httpClient = new Client([
				'verify' => CaBundle::getSystemCaRootBundlePath(),
			]);
		}
		$this->httpClient = $httpClient;
	}


	/**
	 * Fetches video data by youtube url
	 * @param  string $videoUrl YouTube url
	 * @return Video
	 * @throws Nette\InvalidArgumentException
	 */
	public function getVideoByUrl($videoUrl)
	{
		$url = new Nette\Http\Url($videoUrl);

		if (stripos($url->host, 'youtu.be') !== FALSE) {
			return $this->getVideo(trim($url->getPath(), '/'));
		}

		$videoId = $url->getQueryParameter('v');
		if (NULL === $videoId || FALSE === stripos($url->host, 'youtube.com')) {
			throw new Nette\InvalidArgumentException('videoUrl must be valid youtube url.');
		}

		return $this->getVideo($videoId);
	}


	/**
	 * Fetchs video data
	 * @param  string $videoId
	 * @return Video
	 */
	public function getVideo($videoId)
	{
		return $this->parseData($this->getData($videoId), $videoId);
	}


	protected function getData($videoId)
	{
		$url = sprintf(self::FETCH_URL, $this->apiKey, $videoId);
		$response = $this->httpClient->get($url, [
			'http_errors' => FALSE,
		]);

		if ($response->getStatusCode() !== 200) {
			throw new YoutubeApiException("Unable to parse YouTube video: '{$videoId}' ({$response->getStatusCode()}) {$response->getReasonPhrase()}");
		}

		return $response->getBody()->getContents();
	}


	protected function parseData($data, $videoId)
	{
		$data = Nette\Utils\Json::decode($data);
		if (!isset($data->items[0]->snippet) || !isset($data->items[0]->contentDetails)) {
			throw new YoutubeApiException("Empty YouTube response, probably wrong '{$videoId}' video id.");
		}
		
		$snippet = $data->items[0]->snippet;
		$details = $data->items[0]->contentDetails;

		$video = new Video($videoId);

		$video->setTitle($snippet->title);
		$video->setDescription($snippet->description);

		$interval = new DateInterval($details->duration);
		$video->setDuration($interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s);

		foreach (['default', 'medium', 'high', 'standard', 'maxres'] as $type) {
			if (isset($snippet->thumbnails->$type)) {
				$video->addThumb( new Thumbnail($type, $snippet->thumbnails->$type));
			}
		}

		return $video;
	}
}
