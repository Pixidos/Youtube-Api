<?php
/**
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 07.06.2017
 * Time: 10:39
 */

namespace Pixidos\YoutubeApi;

/**
 * Class Thumbnail
 * @package Pixidos\YoutubeApi
 * @author Ondra Votava <me@ondravotava.cz>
 */
class Thumbnail
{
    
    /** @var string */
    private $key;
    /** @var string */
    private $url;
    /** @var int */
    private $height;
    /** @var int */
    private $width;
    
    
    public function __construct($key, $values)
    {
        $this->setUrl($values->url)
             ->setHeight($values->height)
             ->setWidth($values->width);
        $this->setKey($key);
    }
    
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * @param string $key
     *
     * @return Thumbnail
     */
    protected function setKey($key)
    {
        $this->key = $key;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @param string $url
     *
     * @return Thumbnail
     */
    protected function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
    
    /**
     * @param int $height
     *
     * @return Thumbnail
     */
    protected function setHeight($height)
    {
        $this->height = (int)$height;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * @param int $width
     *
     * @return Thumbnail
     */
    protected function setWidth($width)
    {
        $this->width = (int)$width;
        
        return $this;
    }
    
    
}
