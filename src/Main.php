<?php
/**
 * Main app file
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore;

use Devkit\WPCore\DI\ContainerBuilder;

use Psr\Container\ContainerInterface;

use ValueError;

/**
 * Main App Class
 *
 * Used as the base for a plugin/theme
 *
 * @subpackage Main
 */
class Main extends Abstracts\Mountable implements Interfaces\Main, Interfaces\Controller
{
	/**
	 * The service container for dependency injections, and locating service
	 * instances
	 *
	 * @var ContainerInterface
	 */
	protected ContainerInterface $service_container;
	/**
	 * The optional package name.
	 *
	 * @var string
	 */
	protected const PACKAGE = null;
	/**
	 * Configuration array
	 *
	 * @var array<string, mixed>
	 */
	protected array $config = [];
	/**
	 * Default constructor
	 *
	 * @param array<string, mixed> $config : configuration array to merge with defaults.
	 *
	 * @throws ValueError : throw exception if package is empty.
	 */
	public function __construct( array $config = [] )
	{
		$this->setConfig( $config );

		$this->setPackage( $this->config['package'] );

		parent::__construct();
	}
	/**
	 * Setter for config field.
	 *
	 * Ensures default values are set for package, dir, and url.
	 *
	 * @param array<string, mixed> $config : configuration array.
	 *
	 * @return void
	 */
	public function setConfig( array $config ): void
	{
		$type = strtolower(
				$config['type'] 
				?? Helpers::packageType()
			);

		$package = $config['package'] 
				?? static::PACKAGE 
				?? Helpers::slugify( basename( Helpers::getDefaultDir( $type ) )
			);

		$app_dir = untrailingslashit(
				$config['dir'] 
				?? Helpers::getDefaultDir( $type )
			);

		$app_url = untrailingslashit( 
				$config['url'] 
				?? Helpers::getDefaultUrl( $app_dir )
			);

		$assets = [
			'dir' => untrailingslashit( 
					ltrim(
						( is_string( $config['assets']['dir'] ?? false ) ? $config['assets']['dir'] : null )
						?? ( is_string( $config['assets'] ?? false ) ? trim( $config['assets'] ) : 'dist' ),
						'/' 
					)
				),
			'url' => ContainerBuilder::string( '{config.url}/{config.assets.dir}' ),
		];

		$views = [
			'dir' => untrailingslashit( 
				ltrim(
					( is_string( $config['views']['dir'] ?? false ) ? trim( $config['views']['dir'] ) : null )
					?? ( is_string( $config['views'] ?? false ) ? $config['views'] : 'views' ),
					'/'
				)
			),
		];

		$this->config = array_merge(
			$config,
			[
				'type'    => $type,
				'package' => $package,
				'dir'     => $app_dir,
				'url'     => $app_url,
				'assets'  => $assets,
				'views'   => $views,
			]
		);
	}
	/**
	 * Set individual configuration items
	 *
	 * @param string $key : which config item to set.
	 * @param mixed  $value : config value to set.
	 *
	 * @return void
	 */
	public function configure( string $key, mixed $value ): void
	{
		$this->config[ $key ] = $value;
	}
	/**
	 * Setter for service container
	 *
	 * @param ContainerInterface $container : instance of service container.
	 *
	 * @return void
	 */
	public function setContainer( ContainerInterface $container ): void
	{
		$this->service_container = $container;

		ContainerBuilder::cacheContainer( $this->package, $this->service_container );
	}
	/**
	 * Build the service container
	 * - Instantiate a new container builder
	 * - Add plugin specific definitions
	 * - Get service definitions from controllers
	 *
	 * @return ContainerInterface
	 */
	protected function buildContainer(): ContainerInterface
	{
		$container_builder = new ContainerBuilder();

		$container_builder->useAttributes( true );

		$container_builder->addDefinitions( 
			[ 'config' => ContainerBuilder::array( $this->config ) ],
			[ Controllers\Handlers::class => ContainerBuilder::autowire() ],
			static::getServiceDefinitions()
		);		

		$container = $container_builder->build();

		return $container;
	}
	/**
	 * Get container from the cache if available, else build a new one
	 *
	 * Cache is used to give access to the container instead of a singleton
	 *
	 * @return ContainerInterface|null
	 */
	protected function getContainer(): ?ContainerInterface
	{
		return $this->service_container ?? ContainerBuilder::locateContainer( $this->package );
	}
	/**
	 * Get service definitions to add to service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [];
	}
	/**
	 * Helper function to mount new instance of class
	 *
	 * @param array<string, mixed> $config : configuration array to merge with defaults.
	 *
	 * @return static
	 */
	public static function mount( array $config = [] ): static
	{
		$instance = new static( $config );

		$instance->setContainer(
			$instance->getContainer() ?? $instance->buildContainer()
		);

		$instance->onMount();

		return $instance;
	}
	/**
	 * Fire Mounted action on mount
	 *
	 * @return void
	 */
	public function onMount(): void
	{
		foreach ( static::getServiceDefinitions() as $key => $value ) {
			$this->service_container->get( $key );
		}
		parent::onMount();
	}
	/**
	 * Locate a specific service
	 *
	 * Use primarily by 3rd party interactions to remove actions/filters
	 *
	 * @param string $service : name of service to locate.
	 * @param string $package : package id of container to search in.
	 *
	 * @return mixed
	 */
	public static function locateService( string $service, string $package = '' ): mixed
	{
		$container = ContainerBuilder::locateContainer( ! empty( $package ) ? $package : static::PACKAGE );

		if ( $container ) {
			return $container->get( $service );
		} else {
			return null;
		}
	}
	/**
	 * Check if a specific service exists
	 *
	 * @param string $service : name of service to locate.
	 * @param string $package : package id of container to search in.
	 *
	 * @return mixed
	 */
	public static function hasService( string $service, string $package = '' ): mixed
	{
		$container = ContainerBuilder::locateContainer( ! empty( $package ) ? $package : static::PACKAGE );

		if ( $container ) {
			return $container->has( $service );
		} else {
			return false;
		}
	}
}
