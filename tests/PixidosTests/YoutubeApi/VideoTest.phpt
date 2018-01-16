<?php
/**
 * Test: Pixidos\YoutubeApi\Reader
 * @testCase PixidosTest\YoutubeApi\ReaderTest
 */

namespace PixidosTests\YoutubeApi;

/**
 * Class VideoTest
 * @package PixidosTests\YoutubeApi
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
use Pixidos;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class VideoTest extends YoutubeApiTestCase
{


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

	public function testReadOnlyProperty()
	{
		$reader = self::getReader();
		$video = $reader->getVideo('Hdh4b_aMwuA');

		Assert::throws(function () use ($video) {
			$video->setDuration('rewrite');
		}, Pixidos\YoutubeApi\Exceptions\YoutubeApiException::class,
			'You try override read-only duration property');
	}

	public function testThumnails()
	{
		/** @var Pixidos\YoutubeApi\Video $video */
		$video = self::getFakeVideo();
		$thumb = $video->getThumb('standard');

		Assert::same($thumb->getWidth(), 640);
		Assert::same($thumb->getHeight(), 280);
		Assert::same($thumb->getUrl(), 'https://fakeurl');
		Assert::same($thumb->getKey(), 'standard');

		$thumb = $video->getThumb('medium');
		Assert::same($thumb->getKey(), 'standard');

		$thumb = $video->getThumb('medium', FALSE);
		Assert::null($thumb);

		$thumb = $video->getMaxThumb();
		Assert::same($thumb->getKey(), 'maxres');

	}

	public function testExceptions()
	{
		$video = self::getFakeVideo(FALSE);

		Assert::throws(function () use ($video) {
			$video->getMaxThumb();
		}, Pixidos\YoutubeApi\Exceptions\YoutubeApiException::class,
			'Video not contains any thumbnail');

		Assert::throws(function () use ($video) {
			$video->getThumb('non-exist-key');
		}, Pixidos\YoutubeApi\Exceptions\YoutubeApiException::class,
			'non-exist-key key not exists you can use only one of this '. implode('|', ['maxres', 'standard', 'high', 'medium', 'default'] ));
	}
}

(new VideoTest())->run();
