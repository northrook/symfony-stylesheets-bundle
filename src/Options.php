<?php

namespace Northrook\Symfony\Stylesheets;

use Northrook\Types\Path;
use Northrook\Types\Type\Properties;

/**
 */
final class Options extends Properties
{

    public bool          $forceRegeneration = false;
    public SaveCondition $save              = SaveCondition::Manual;
    public Path          $rootDirectory;
    public Path          $outputDirectory;
    public string        $fileName;

    public function getOutputPath() : Path {
        return $this->outputDirectory->add( $this->fileName );
    }
}