<?php
/**
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 25.05.2017
 * Time: 17:37
 */

namespace Pixidos\YoutubeApi\DI;

use Nette;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;


/**
 * Class YoutubeApiExtension
 * @package Pixidos\YoutubeApi\DI
 * @author Ondra Votava <me@ondravotava.cz>
 */
class YoutubeApiExtension extends CompilerExtension
{
    
    public function loadConfiguration()
    {
        $config = $this->getConfig();
        Validators::assertField($config, 'apiKey');
        
        $builder = $this->getContainerBuilder();
        
        $builder->addDefinition($this->prefix('reader'))
                ->setClass('Pixidos\YoutubeApi\Reader', [$config['apiKey']]);
        
    }
    
    
    public static function register(Nette\Configurator $configurator)
    {
        $configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
            $compiler->addExtension('youtubeApi', new YoutubeApiExtension());
        };
    }
}
