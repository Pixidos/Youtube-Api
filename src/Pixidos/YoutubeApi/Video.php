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

	private static $resolution = ['maxres', 'standard', 'high', 'medium', 'default',];


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
	 * @internal method is only for internal use
	 * @param string $title
	 * @return Video
	 * @throws YoutubeApiException
	 */
	public function setTitle($title)
	{
		if (NULL === $this->title) {
			$this->title = $title;
			return $this;
		}
		throw new YoutubeApiException('You try override read-only title property');
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @internal method is only for internal use
	 * @param string $description
	 * @return Video
	 * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
	 */
	public function setDescription($description)
	{
		if (NULL === $this->description) {
			$this->description = $description;
			return $this;
		}
		throw new YoutubeApiException('You try override read-only description property');

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
	 * @internal method is only for internal use
	 * @param int $duration
	 * @return Video
	 * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
	 */
	public function setDuration($duration)
	{
		if (NULL === $this->duration) {
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
	 * @param $resolution
	 * @param bool $fallback
	 * @return null|Thumbnail
	 * @throws \Pixidos\YoutubeApi\Exceptions\YoutubeApiException
	 */
	public function getThumb($resolution, $fallback = TRUE)
	{
		$resolution = strtolower($resolution);

		$thumb = array_key_exists($resolution, $this->thumbs) ? $this->thumbs[$resolution] : NULL;
		if (NULL !== $thumb) {
			return $thumb;
		}

		$key = array_search($resolution, self::$resolution, TRUE);
		if (FALSE === $key) {
			throw new YoutubeApiException($resolution . ' key not exists you can use only one of this ' .
				implode('|', self::$resolution));
		}

		if ($fallback) {
			if ($key > 0) {
				return $this->getThumb(self::$resolution[--$key]);
			}
			return $this->getMaxThumb();
		}

		return NULL;

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
