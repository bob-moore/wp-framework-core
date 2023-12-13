<?php
/**
 * Main app file
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
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
 * @subpackage Traits
 */
class Main extends Abstracts\Mountable implements Interfaces\Main
{
	use Traits\Handlers\Url;
	use Traits\Handlers\Directory;

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
	protected const PACKAGE = '';
	/**
	 * Constructor for new instance of plugin
	 *
	 * @param string $package : name of the package this instance belongs to.
	 * @param string $root_file : path to root file of the plugin.
	 *
	 * @throws ValueError Error thrown if required data not provided.
	 */
	public function __construct( string $package = '', string $root_file = '' )
	{
		/**
		 * Maybe set package
		 */
		if ( empty( $this->package ) ) {
			if ( empty( $package ) && empty( static::PACKAGE ) ) {
				throw new ValueError( esc_html__( 'Package ID is required' ) );
			} else {
				$this->setPackage( ! empty( $package ) ? $package : static::PACKAGE );
			}
		}
		/**
		 * Maybe set url
		 */
		if ( empty( $this->url ) ) {
			if ( empty( $root_file ) ) {
				throw new ValueError( esc_html__( 'Root plugin file required to set URL' ) );
			} else {
				$this->setUrl( plugin_dir_url( $root_file ) );
			}
		}
		/**
		 * Maybe set dir
		 */
		if ( empty( $this->dir ) ) {
			if ( empty( $root_file ) ) {
				throw new ValueError( esc_html__( 'Root plugin file required to set DIR' ) );
			} else {
				$this->setDir( plugin_dir_path( $root_file ) );
			}
		}

		$this->service_container = $this->getContainer();

		parent::__construct();
	}
	/**
	 * Set the class package
	 *
	 * @param string $package : name used to reference the current instance.
	 *
	 * @return void
	 */
	public function setPackage( string $package ): void
	{
		if ( ! empty( trim( $package ) ) ) {
			parent::setPackage( $package );
		} elseif ( empty( $this->package ) ) {
			parent::setPackage( str_ireplace( '\\', '_', static::class ) );
		}
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
			apply_filters(
				"{$this->package}_definitions",
				$this->getServiceDefinitions()
			)
		);

		return $container_builder->build();
	}
	/**
	 * Get container from the cache if available, else instantiate a new one
	 *
	 * Cache is used to give access to the container instead of a singleton
	 *
	 * @return ContainerInterface
	 */
	protected function getContainer(): ContainerInterface
	{
		$container = ContainerBuilder::locateContainer( $this->package );

		if ( ! $container ) {
			$container = $this->buildContainer();
			ContainerBuilder::cacheContainer( $this->package, $container );
		}

		return $container;
	}
	/**
	 * Get service definitons to add to service container
	 *
	 * @return array<string, mixed>
	 */
	protected function getServiceDefinitions(): array
	{
		return [
			'app.package'                  => $this->package,
			'app.url'                      => $this->url,
			'app.dir'                      => $this->dir,
			'assets.url'                   => 'dist',
			'assets.dir'                   => 'dist',
			'views.dir'                    => 'views',
			Controllers\Dispatchers::class => ContainerBuilder::autowire(),
			Controllers\Routes::class      => ContainerBuilder::autowire(),
			Controllers\Services::class    => ContainerBuilder::autowire(),
		];
	}
	/**
	 * Mount the plugin
	 *
	 * @return void
	 */
	/**
	 * Undocumented function
	 *
	 * @param string $package : name of the package this instance belongs to.
	 * @param string $root_file : path to root file of the plugin.
	 *
	 * @return self
	 */
	public static function mount( string $package = '', string $root_file = '' ): static
	{
		$instance = new static( $package, $root_file );

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
		$this->mountActions();

		$this->mountControllers();

		parent::onMount();
	}
	/**
	 * Mount the Actions
	 *
	 * @return void
	 */
	protected function mountActions(): void
	{
		add_filter( "{$this->package}_has_route", [ $this, 'hasRoute' ], 5, 2 );
		add_action( "{$this->package}_load_route", [ $this, 'loadRoute' ], 5 );
	}
	/**
	 * Mount the controller classes
	 *
	 * @return void
	 */
	protected function mountControllers(): void
	{
		$this->service_container->get( Controllers\Services::class );
		$this->service_container->get( Controllers\Routes::class );
		$this->service_container->get( Controllers\Dispatchers::class );
	}
	/**
	 * Check if a particular route exists
	 *
	 * @param boolean $has_route : default value.
	 * @param string  $route_alias : name of route to find.
	 *
	 * @return boolean
	 */
	public function hasRoute( bool $has_route, string $route_alias ): bool
	{
		return false === $has_route ? $this->service_container->has( $route_alias ) : $has_route;
	}
	/**
	 * Load a route from the service container
	 *
	 * @param string $route_alias : name of route to load.
	 *
	 * @return void
	 */
	public function loadRoute( string $route_alias ): void
	{
		$this->service_container->get( $route_alias );
	}
	/**
	 * Locate a specific service
	 *
	 * Use primarily by 3rd party interactions to remove actions/filters
	 *
	 * @param string $service : name of service to locate.
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
}
