<?php declare( strict_types = 1 );

namespace Northrook\Symfony\Stylesheets;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @version dev
 * @author  Martin Nielsen <mn@northrook.com>
 *
 * @link    https://github.com/northrook Documentation
 * @todo    Update URL to documentation : root of symfony-stylesheets-bundle
 */
final class SymfonyStylesheetsBundle extends Bundle
{

	public function getPath() : string {
		return dirname( __DIR__ );
	}

}