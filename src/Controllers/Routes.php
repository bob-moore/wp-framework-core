<?php
/**
 * Route Controller
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Controllers;

use Devkit\WPCore\DI\ContainerBuilder,
	Devkit\WPCore\Abstracts,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\DI\OnMount,
	Devkit\WPCore\Services,
	Devkit\WPCore\Helpers,
	Devkit\WPCore\Main,
	Devkit\WPCore\Routes as Route;

/**
 * Controls the registration and execution of Routes
 *
 * @subpackage Controllers
 */
class Routes extends Abstracts\Mountable implements Interfaces\Controller
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Services\Router::class            => ContainerBuilder::autowire(),
			Interfaces\Services\Router::class => ContainerBuilder::get( Services\Router::class ),
			'route'                           => ContainerBuilder::array(
				[
					'frontend' => ContainerBuilder::autowire( Route\Frontend::class ),
					'admin'    => ContainerBuilder::autowire( Route\Frontend::class ),
					'login'    => ContainerBuilder::autowire( Route\Frontend::class ),
				]
			)
		];
	}
	/**
	 * Mount router functions/filters
	 *
	 * @param Interfaces\Services\Router $router : instance of router service.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountRouter( Interfaces\Services\Router $router ): void
	{
		add_action( 'wp', [ $router, 'route' ] );
		add_action( 'admin_init', [ $router, 'route' ] );
		add_action( 'login_init', [ $router, 'route' ] );
	}
	/**
	 * Mount router functions/filters
	 *
	 * @return void
	 */
	#[OnMount]
	public function mount(): void
	{
		add_action( "{$this->package}_trigger_route", [ $this, 'route' ] );
	}
	/**
	 * Setter for $route
	 *
	 * @return void
	 */
	public function route( string $route ): void
	{
		$alias = 'route.' . strtolower( $route );
		if ( $this->routeExists( $alias ) && ! $this->routeHasMounted() ) {
			$route_instance = Main::locateService( $alias, $this->package );
			do_action( "{$this->package}_route_mounted", $alias, $route_instance );
			do_action( "{$this->package}_{$route}_mounted", $route_instance );
		}
	}
	/**
	 * Check if a route exists
	 *
	 * @param string $route_alias : string name of route to check.
	 *
	 * @return boolean
	 */
	public function routeExists( string $route_alias ): bool
	{
		return apply_filters( "{$this->package}_has_route",
			Main::hasService( $route_alias, $this->package ),
			$route_alias
		);
	}
	/**
	 * Determine if a route has already been loaded
	 *
	 * @return int
	 */
	public function routeHasMounted(): int
	{
		return did_action( "{$this->package}_route_mounted" );
	}
}
