<?php

/**
 * Test: Pixidos\YoutubeApi\DI\YoutubeApiExtension
 * @testCase PixidosTests\YoutubeApi\DI\YoutubeApiExtensionTest
 *
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 08.06.2017
 * Time: 10:46
 */

namespace PixidosTests\YoutubeApi\DI;

use PixidosTests\YoutubeApi\YoutubeApiTestCase;
use Tester\Assert;

/**
 * Class YoutubeApiExtensionTest
 * @package PixidosTests\YoutubeApi\DI
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */

require_once __DIR__ . '/../../bootstrap.php';

class YoutubeApiExtensionTest extends YoutubeApiTestCase
{
    
    public function setUp()
    {
        parent::setUp();
        $this->prepareContainer();
    }
    
    public function testExtensionRegistred()
    {
        $container = $this->getContainer();
        $youtoubeApi = $container->getByType('Pixidos\YoutubeApi\Reader');
        Assert::type('Pixidos\YoutubeApi\Reader', $youtoubeApi);
    }
}

(new YoutubeApiExtensionTest())->run();
