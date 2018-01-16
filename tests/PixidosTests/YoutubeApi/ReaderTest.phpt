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

use Pixidos;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ReaderTest extends YoutubeApiTestCase
{
    
    
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
    
    
    public function testException()
    {
        $reader = self::getReader();
        
        Assert::throws(
            function () use ($reader) {
                $reader->getVideo('notExistYouTubeCode');
            }, Pixidos\YoutubeApi\Exceptions\YoutubeApiException::class,
            "Empty YouTube response, probably wrong 'notExistYouTubeCode' video id."
        );
    }
    
}

(new ReaderTest())->run();

