<?php declare( strict_types = 1 );

namespace Northrook\Symfony\Stylesheets;

use Northrook\Symfony\Core\Support\Str;
use Northrook\Symfony\Stylesheets\Services\StylesheetGenerationService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * @version dev
 * @author  Martin Nielsen <mn@northrook.com>
 *
 * @link    https://github.com/northrook Documentation
 * @todo    Update URL to documentation : root of symfony-stylesheets-bundle
 */
final class SymfonyStylesheetsBundle extends AbstractBundle
{
    public function loadExtension(
        array                 $config,
        ContainerConfigurator $container,
        ContainerBuilder      $builder,
    ) : void {

        // Parameters
        $container->parameters()
                  ->set( 'dir.root', Str::parameterDirname( '%kernel.project_dir%', ) )
                  ->set(
                      'dir.stylesheets.output', Str::parameterDirname( '%kernel.project_dir%/public/assets/styles' ),
                  )
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


    }

    public function getPath() : string {
        return dirname( __DIR__ );
    }

}