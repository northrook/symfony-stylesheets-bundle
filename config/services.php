<?php declare( strict_types = 1 );

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Northrook\Symfony\Stylesheets\StylesheetGenerationService;

return static function ( ContainerConfigurator $container ) : void {

	$fromRoot = static fn ( string $set = '' ) => $set ? trim(
		'%kernel.project_dir%' . DIRECTORY_SEPARATOR . trim(
			str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $set ), DIRECTORY_SEPARATOR,
		) . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR,
	) : $set;

	// Parameters
	$container->parameters()
	          ->set( 'dir.root', $fromRoot() )
	          ->set( 'dir.stylesheets.output', $fromRoot( "/public/assets/styles" ) )
	;

	// Services
	$container->services()
		//
		//
		// 🗃️️ - Stylesheet Generation Service
		      ->set( 'stylesheet.service.generator', StylesheetGenerationService::class )
	          ->args( [
		                  service( 'parameter_bag' ),
		                  service( 'session' )->nullOnInvalid(),
		                  service( 'logger' )->nullOnInvalid(),
		                  service( 'debug.stopwatch' )->nullOnInvalid(),
	                  ] )
	          ->autowire()
	          ->alias( StylesheetGenerationService::class, 'stylesheet.service.generator' )
	;


};