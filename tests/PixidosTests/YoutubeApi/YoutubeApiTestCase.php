<?php
/**
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 07.06.2017
 * Time: 12:20
 */

namespace PixidosTests\YoutubeApi;

use Nette;
use Pixidos\YoutubeApi\DI\YoutubeApiExtension;
use Tracy\Debugger;

/**
 * Class YoutubeApiTestCase
 * @package PixidosTests\YoutubeApi
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */

abstract  class YoutubeApiTestCase extends \Tester\TestCase
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
	 */
	public function getContainer()
	{
		if($this->container == NULL){
			throw new \LogicException('First need run ' .  get_called_class() .'::prepareContainer() to initialize the container.');
		}
		return $this->container;
	}

}