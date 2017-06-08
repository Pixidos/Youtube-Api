<?php

/**
 * Test: Pixidos\YoutubeApi\Reader
 * @testCase PixidosTest\YoutubeApi\ReaderTest
 * @original-author     Jan Skrasek
 * @original-package    Nextras\YoutubeApi
 * @author Ondra Votava
 * @package Pixidos\YoutubeApi
 */

namespace PixidosTests\YoutubeApi;

use Nette\Neon\Neon;
use Pixidos;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ReaderTest extends YoutubeApiTestCase
{
	/**
	 * @return Pixidos\YoutubeApi\Reader
	 */
	public static function getReader()
	{
		$config = file_get_contents(__DIR__ . '/config/youtubeapi.config.neon');
		$config = Neon::decode($config);
		/** @var Pixidos\YoutubeApi\Reader $reader */
		$apiKey = $config['youtubeApi']['apiKey'];
		if ($apiKey === 'PUT_YOUR_API_KEY_HERE') {
			if (!$apiKey = getenv('apiKey')) {
				dump($apiKey);
				dump($_ENV);
				throw new \RuntimeException('You need set your api key in { YoutubeApi/config/youtubeapi.config.neon } ');
			}

		}
		return new Pixidos\YoutubeApi\Reader($apiKey);
	}


	/**
	 * Reader test
	 */
	public function testReader()
	{
		$reader = self::getReader();
		/** @var Pixidos\YoutubeApi\Video $video */
		$video = $reader->getVideo('Hdh4b_aMwuA');

		$video2 = $reader->getVideoByUrl($video->getUrl());
		Assert::equal($video, $video2);

	}

	/**
	 * Video entity test
	 */
	public function testVideo()
	{
		$reader = self::getReader();
		$video = $reader->getVideo('Hdh4b_aMwuA');

		Assert::true($video instanceof Pixidos\YoutubeApi\Video);
		Assert::same('API Testing Tutorial Part 1', $video->getTitle());
		Assert::same('https://www.youtube.com/watch?v=Hdh4b_aMwuA', $video->getUrl());
		Assert::truthy($video->getDescription());
		Assert::same(1122, $video->getDuration());

		Assert::same(5, count($video->getThumbs()));

		// check only basic
		foreach (['default', 'medium', 'high', 'standard', 'maxres'] as $type) {
			$thumbnail = $video->getThumb($type);
			Assert::true(!empty($thumbnail->getUrl()));
			Assert::true(!empty($thumbnail->getWidth()));
			Assert::true(!empty($thumbnail->getHeight()));
		}
	}

	public function testException()
	{
		$reader = self::getReader();

		Assert::throws(function () use ($reader) {
			$reader->getVideo('notExistYouTubeCode');
		}, Pixidos\YoutubeApi\Exceptions\YoutubeApiException::class,
			"Empty YouTube response, probably wrong 'notExistYouTubeCode' video id.");
	}

}

(new ReaderTest())->run();

