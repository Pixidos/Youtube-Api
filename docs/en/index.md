# Quickstart

This extension is here to provide [Youtube API](https://developers.google.com/youtube/) system for Nette Framework.


## Installation

The best way to install Pixidos/Youtube-Api is using  [Composer](http://getcomposer.org/):

```sh
$ composer require pixidos/youtube-api
```

and you can enable the extension using your neon config

```yml
extensions:
    youtubeApi: Pixidos\YoutubeApi\DI\YoutubeApiExtension
```

and setting

```yml
youtubeApi:
    apiKey: PUT_YOUR_API_KEY_HERE
```

## Usage


```php
namespace App\Presenter;
use Pixidos;


class ExamplePreseneter extends \Nette\Application\UI\Presenter
{

	/**
	 * @var Pixidos\YoutubeApi\Reader
	 * @inject
	 */
	public $youtubeReader;

	public function actionSomeAction()
	{
		try {
			/**
			 *  Get youtube video info by video-key
			 *
			 * @var Pixidos\YoutubeApi\Video $video
			 */
			$video = $this->youtubeReader->getVideo('<video-key>');

			// Alternative is get video info by url
			// $video = $this->youtubeReader->getVideoByUrl('<youtube-video-url>');


			//Title
			$video->getTitle();
			//Description
			$video->getDescription();
			//Duration in second
			$video->getDuration();
			// url
			$video->getUrl(bool $https = TRUE, bool $embed = FALSE );
			// embed url shortcut
			$video->getEmbedUrl(bool $https = TRUE);
			// Thumbnails[] | NULL
			$video->getThumbs();

			// or you can get exactly thumb resolution
			// posible is 'maxres','standard','high', 'medium', 'default'
			// but not every video contain all sizes
			// when size is not available and $fallback is TRUE (defalut)
			// method return closest available resolution firstime try bigger then smaller
			/** @var Pixidos\YoutubeApi\Thumbnail $thumb */
			$thumb = $video->getThumb(string $reslution, bool $fallback = TRUE);
			
			// url of thumbnail images
			$thumb->getUrl();
			// thumbnail images height
			$thumb->getHeight();
			// thumbnail images width
			$thumb->getWidth();
			// thumbnail images resolution key
			$thumb->getKey();

			// get max resolution thumbnail
			$video->getMaxThumb();
			

		} catch (Pixidos\YoutubeApi\Exceptions\YoutubeApiException $e) {
			// ...
		}

	}

}

```
