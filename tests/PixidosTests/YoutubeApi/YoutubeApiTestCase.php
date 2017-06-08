<?php
/**
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 07.06.2017
 * Time: 12:20
 */

namespace PixidosTests\YoutubeApi;

use Nette;
use Nette\Neon\Neon;
use Pixidos;
use Pixidos\YoutubeApi\DI\YoutubeApiExtension;

/**
 * Class YoutubeApiTestCase
 * @package PixidosTests\YoutubeApi
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
abstract class YoutubeApiTestCase extends \Tester\TestCase
{
	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @return Nette\DI\Container
	 */
	protected function prepareContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addParameters(array('container' => array('class' => 'SystemContainer_' . md5(TEMP_DIR))));
		$config->addConfig(sprintf(__DIR__ . '/../nette-reset.neon'));
		$config->addConfig(sprintf(__DIR__ . '/config/youtubeapi.config.neon'));
		YoutubeApiExtension::register($config);

		return $this->container = $config->createContainer();
	}

	/**
	 * @return Nette\DI\Container
	 * @throws \LogicException
	 */
	public function getContainer()
	{
		if (NULL === $this->container) {
			throw new \LogicException('First need run ' . get_called_class() . '::prepareContainer() to initialize the container.');
		}
		return $this->container;
	}

	/**
	 * @return Pixidos\YoutubeApi\Reader
	 * @throws \RuntimeException
	 */
	public static function getReader()
	{
		$config = file_get_contents(__DIR__ . '/config/youtubeapi.config.neon');
		$config = Neon::decode($config);
		/** @var Pixidos\YoutubeApi\Reader $reader */
		$apiKey = $config['youtubeApi']['apiKey'];
		if ($apiKey === 'PUT_YOUR_API_KEY_HERE') {
			if (!$apiKey = getenv('apiKey')) {
				throw new \RuntimeException('You need set your api key in { YoutubeApi/config/youtubeapi.config.neon } ');
			}

		}
		return new Pixidos\YoutubeApi\Reader($apiKey);
	}

	public static function getFakeVideo($withThumbs = TRUE)
	{
		$video = (new Pixidos\YoutubeApi\Video('Hdh4b_aMwuA'))
			->setTitle('Fake title')
			->setDuration(100)
			->setDescription(' ');

		if ($withThumbs) {
			$maxres = new Pixidos\YoutubeApi\Thumbnail(
				'maxres',
				Nette\Utils\ArrayHash::from(['height' => 720, 'width' => 1280, 'url' => 'https://fakeurl']));
			$standard = new Pixidos\YoutubeApi\Thumbnail('standard',
				Nette\Utils\ArrayHash::from(['height' => 280, 'width' => 640, 'url' => 'https://fakeurl']));
			$default = new Pixidos\YoutubeApi\Thumbnail('default',
				Nette\Utils\ArrayHash::from(['height' => 60, 'width' => 128, 'url' => 'https://fakeurl']));

			$video->addThumb($maxres);
			$video->addThumb($standard);
			$video->addThumb($default);
		}

		return $video;
	}
}