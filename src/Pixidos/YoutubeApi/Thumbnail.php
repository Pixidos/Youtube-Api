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
 * @author Ondra Votava <ondra.votava@pixidos.com>
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
	 * @param mixed $key
	 * @return Thumbnail
	 */
	protected function setKey($key)
	{
		$this->key = $key;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param mixed $url
	 * @return Thumbnail
	 */
	protected function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @param mixed $height
	 * @return Thumbnail
	 */
	protected function setHeight($height)
	{
		$this->height = $height;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @param mixed $width
	 * @return Thumbnail
	 */
	protected function setWidth($width)
	{
		$this->width = $width;
		return $this;
	}


}