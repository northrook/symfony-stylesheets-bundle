<?php

namespace Northrook\Symfony\Stylesheets\Stylesheets;

use Northrook\Types\Path;
use Northrook\Types\Type\Properties;

/**
 * @property bool   $forceRegeneration
 * @property bool   $saveOnResponse
 * @property Path   $rootDirectory
 * @property Path   $outputDirectory
 * @property string $fileName
 */
final class Options extends Properties
{
	public function getOutputPath() : Path {
		return $this->outputDirectory->add( $this->fileName );
	}
}