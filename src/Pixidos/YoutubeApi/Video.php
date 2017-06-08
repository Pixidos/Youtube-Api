<?php


namespace Pixidos\YoutubeApi;


use Pixidos\YoutubeApi\Exceptions\YoutubeApiException;

class Video
{
	private $id;

	/** @var string */
	private $title;

	/** @var string */
	private $description;

	/** @var int */
	private $duration;

	/** @var array */
	public $thumbs = [];

	private static $resolution = ['maxres','standard','high', 'medium', 'default', ];


	/**
	 * Video constructor.
	 * @param $id
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
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
	 * @param string $title
	 * @return Video
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Video
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @param bool $https
	 * @param bool $embed
	 * @return string
	 */
	public function getUrl($https = TRUE, $embed = FALSE)
	{
		$url = ($https ? 'https://' : 'http://') . 'www.youtube.com/' . ($embed ? 'embed/' : 'watch?v=') . $this->getId();
		return $url;
	}

	/**
	 * @param bool $https
	 * @return string
	 */
	public function getEmbedUrl($https = TRUE)
	{
		return $this->getUrl($https, TRUE);
	}

	/**
	 * @return int
	 */
	public function getDuration()
	{
		return $this->duration;
	}

	/**
	 * @param int $duration
	 * @return Video
	 */
	public function setDuration($duration)
	{
		$this->duration = $duration;
		return $this;
	}

	/**
	 * @return Thumbnail[]
	 */
	public function getThumbs()
	{
		return $this->thumbs;
	}

	/**
	 * @param $resolution
	 * @return Thumbnail|null
	 */
	public function getThumb($resolution)
	{
		return array_key_exists($resolution, $this->thumbs) ? $this->thumbs[$resolution] : NULL;

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
	 * @return Thumbnail
	 * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
	 */
	public function getMaxThumb()
	{
		foreach (self::$resolution as $key){
			if(array_key_exists($key, $this->thumbs)){
				return $this->thumbs[$key];
			}
		}
		throw new YoutubeApiException('Video not contains any thumbnail');
	}
}
