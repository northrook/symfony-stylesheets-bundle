<?php

namespace Northrook\Symfony\Stylesheets\Services;

use JetBrains\PhpStorm\ArrayShape;
use Northrook\Stylesheets\ColorPalette;
use Northrook\Stylesheets\DynamicRules;
use Northrook\Stylesheets\Stylesheet;
use Northrook\Support\Str;
use Northrook\Symfony\Stylesheets\Options;
use Northrook\Types\Path;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Stopwatch\Stopwatch;

class StylesheetGenerationService
{


	// @todo Move this to config, as primary only.
	// When just provided a primary color, autogenerate a light and dark baseline color
	private const PALETTE_FILE = [
		'baseline' => '222,9,10',
		'primary'  => '222,100,50',
	];


	public Stylesheet   $generator;
	public ColorPalette $palette;
	public Options      $options;


	/**
	 * @var Path[]
	 */
	private array $templateDirectories = [];

	/** @todo Use {@see \Northrook\Types\Type\Record} when refactoring northrook/stylesheets. */
	private array $includedStylesheets = [];

	private Path   $rootDirectory;
	private Path   $outputDirectory;
	private string $fileName;

	public function __construct(
		private ParameterBagInterface $parameterBag,
		private ?Session              $session = null,
		private ?Logger               $logger = null,
		private ?Stopwatch            $stopwatch = null,
	) {
		$this->options         = new Options(
			[
				'rootDirectory'   => $this->parameterBag->get( 'dir.root' ),
				'outputDirectory' => $this->parameterBag->get( 'dir.stylesheets.output' ),
				'fileName'        => 'styles.css',
			]
		);
		$this->rootDirectory   = Path::type( $this->parameterBag->get( 'dir.root' ) );
		$this->outputDirectory = Path::type( $this->parameterBag->get( 'dir.stylesheets.output' ) );

		dd( $this );
	}

	public function save( ?string $path = null ) : void {

		$path = $this->options->getOutputPath();

		if ( !$path->isValid ) {
			$this->logger->error(
				'The output path is invalid. Stylesheet generation {status}.',
				[
					'status'   => 'halted',
					'path'     => $path,
					'pathName' => $path->value,
				],
			);
			return;
		}

		$this->generator = new Stylesheet(
			palette : $this->palette ?? new ColorPalette( self::PALETTE_FILE ),
			force   : $this->options->forceRegeneration
		);

		foreach ( $this->includedStylesheets as $index => $stylesheet ) {
			if ( substr_count( $stylesheet, '.' ) > 1 ) {
				$parent = strchr( $stylesheet, '.', true ) . '.css';
				$parent = Path::type( $parent );
				if ( $parent->isValid ) {
					$this->generator->addStylesheets( $parent );
				}
			}
			$stylesheet = Path::type( $stylesheet );
			if ( $stylesheet->isValid ) {
				$this->generator->addStylesheets( $stylesheet );
			}
		}

		if ( empty( $this->templateDirectories ) ) {
			$this->templateDirectories[] = $this->rootDirectory . 'templates';
		}

		$this->generator->dynamicRules = new DynamicRules(
			$this->options->rootDirectory,
			$this->templateDirectories
		);

		$this->generator->save( $path );

	}

	public function setRootDirectory( string $rootDirectory ) : self {
		$this->rootDirectory = Path::type( $rootDirectory );
		return $this;
	}

	public function setOutputDirectory( string $outputDirectory, ?string $fileName = null ) : self {
		$this->outputDirectory = Path::type( $outputDirectory );
		if ( null !== $fileName ) {
			return $this->setFileName(
				$fileName ?: $this->parameterBag->get( '' ),
			);
		}
		return $this;
	}

	public function setFileName( string $fileName ) : self {
		$this->fileName = Str::end( $fileName, '.css' );
		return $this;
	}

	public function setPalette( ColorPalette $palette ) : self {
		$this->palette = $palette;
		return $this;
	}

	public function includeStylesheets( string | array $path ) : self {
		$this->includedStylesheets = array_merge( $this->includedStylesheets, (array) $path );
		return $this;
	}

	public function scanTemplateFiles( string ...$in ) : self {
		foreach ( $in as $path ) {
			$this->templateDirectories[] = Path::type( $path );
		}
		return $this;
	}


}