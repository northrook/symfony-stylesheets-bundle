<?php declare( strict_types = 1 );

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Northrook\Symfony\Core\Support\Path;
use Northrook\Symfony\Stylesheets\Services\StylesheetGenerationService;

return static function ( ContainerConfigurator $container ) : void {

    // Parameters
    $container->parameters()
              ->set( 'dir.root', Path::from( '%kernel.project_dir%', ) )
              ->set( 'dir.stylesheets.output', Path::from( '%kernel.project_dir%/public/assets/styles' ) )
    ;

    // Services
    $container->services()
        //
        //
        // 🗃️️ - Stylesheet Generation Service
              ->set( 'stylesheet.service.generator', StylesheetGenerationService::class )
              ->args(
                  [
                      service( 'parameter_bag' ),
                      service( 'request_stack' ),
                      service( 'logger' )->nullOnInvalid(),
                      service( 'debug.stopwatch' )->nullOnInvalid(),
                  ],
              )
              ->autowire()
              ->alias( StylesheetGenerationService::class, 'stylesheet.service.generator' )
    ;


};