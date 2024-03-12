<?php

namespace Northrook\Symfony\Stylesheets\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class SymfonyStylesheetsExtension extends Extension
{

	/**
	 * @throws Exception
	 */
	public function load( array $configs, ContainerBuilder $container ) : void {
		$config = new PhpFileLoader( $container, new FileLocator( dirname( __DIR__, 2 ) . '/config' ) );
		$config->load( 'services.php' );
	}
}