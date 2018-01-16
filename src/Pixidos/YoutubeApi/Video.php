<?php

namespace Pixidos\YoutubeApi;

use Pixidos\YoutubeApi\Exceptions\YoutubeApiException;

/**
 * Class Video
 * @package Pixidos\YoutubeApi
 * @author Ondra Votava <me@ondravotava.cz>
 */
class Video
{
    private static $resolution = ['maxres', 'standard', 'high', 'medium', 'default',];
    /** @var array */
    public $thumbs = [];
    /** @var string */
    private $id;
    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var int */
    private $duration;
    
    /**
     * Video constructor.
     *
     * @param string $id
     * @param string $title
     * @param string $description
     */
    public function __construct($id, $title, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @param bool $https
     * @param bool $embed
     *
     * @return string
     */
    public function getUrl($https = true, $embed = false)
    {
        $url = ($https ? 'https://' : 'http://') . 'www.youtube.com/' . ($embed ? 'embed/' : 'watch?v=') . $this->getId();
        
        return $url;
    }
    
    /**
     * @param bool $https
     *
     * @return string
     */
    public function getEmbedUrl($https = true)
    {
        return $this->getUrl($https, true);
    }
    
    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }
    
    /**
     * @internal method is only for internal use
     *
     * @param int $duration
     *
     * @return Video
     * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
     */
    public function setDuration($duration)
    {
        if (null === $this->duration) {
            $this->duration = $duration;
            
            return $this;
        }
        throw new YoutubeApiException('You try override read-only duration property');
    }
    
    /**
     * @return Thumbnail[]
     */
    public function getThumbs()
    {
        return $this->thumbs;
    }
    
    /**
     * Get exactly thumb resolution
     * posible is 'maxres','standard','high', 'medium', 'default'
     * but not every video contain all sizes
     * when size is not available and $fallback is TRUE (defalut)
     * method try return closest available resolution firstime try bigger then smaller
     * when cant return nothing throw exception
     *
     * @param      $resolution
     * @param bool $fallback
     *
     * @return null|Thumbnail
     * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
     */
    public function getThumb($resolution, $fallback = true)
    {
        $resolution = strtolower($resolution);
        
        $thumb = array_key_exists($resolution, $this->thumbs) ? $this->thumbs[$resolution] : null;
        if (null !== $thumb) {
            return $thumb;
        }
        
        $key = array_search($resolution, self::$resolution, true);
        if (false === $key) {
            throw new YoutubeApiException(
                $resolution . ' key not exists you can use only one of this ' .
                implode('|', self::$resolution)
            );
        }
        
        if ($fallback) {
            if ($key > 0) {
                return $this->getThumb(self::$resolution[--$key]);
            }
            
            return $this->getMaxThumb();
        }
        
        return null;
        
    }
    
    /**
     * @param Thumbnail $thumbnail
     */
    public function addThumb(Thumbnail $thumbnail)
    {
        if (!array_key_exists($thumbnail->getKey(), $this->thumbs)) {
            $this->thumbs[$thumbnail->getKey()] = $thumbnail;
        }
    }
    
    /**
     * Return max available thumbnail size
     * @return Thumbnail
     * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
     */
    public function getMaxThumb()
    {
        foreach (self::$resolution as $key) {
            if (array_key_exists($key, $this->thumbs)) {
                return $this->thumbs[$key];
            }
        }
        throw new YoutubeApiException('Video not contains any thumbnail');
    }
}

