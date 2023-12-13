<?php
/**
 * Route Controller
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
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
			'route.frontend' => ContainerBuilder::autowire( Route\Frontend::class ),
			'route.admin'    => ContainerBuilder::autowire( Route\Frontend::class ),
			'route.login'    => ContainerBuilder::autowire( Route\Frontend::class ),
		];
	}
	/**
	 * Mount router functions/filters
	 *
	 * @return void
	 */
	#[OnMount]
	public function mount(): void
	{
		add_action( "{$this->package}_router_ready", [ $this, 'route' ] );
	}
	/**
	 * Setter for $route
	 *
	 * @return void
	 */
	public function route( array $routes ): void
	{
		foreach ( $routes as $route ) {
			$this->mountRoute( $route );
		}
		/**
		 * Final check to load frontend if nothing has loaded at this point
		 */
		if ( ! $this->routeHasLoaded() && ! is_admin() && ! wp_doing_ajax() && ! wp_doing_cron() ) {
			$this->mountRoute( 'frontend' );
		}
	}
	/**
	 * Load a singular route
	 *
	 * @param string $route : string route name.
	 *
	 * @return void
	 */
	protected function mountRoute( string $route ): void
	{
		$alias = 'route.' . strtolower( $route );

		$has_route = apply_filters( "{$this->package}_has_route", false, $alias );

		if ( ! $this->routeHasLoaded() && $has_route ) {
			do_action( "{$this->package}_load_route", $alias, $route );
		}
		do_action( "{$this->package}_route_{$route}", $alias );
	}
	/**
	 * Determine if a route has already been loaded
	 *
	 * @return int
	 */
	public function routeHasLoaded(): int
	{
		return did_action( "{$this->package}_route_loaded" );
	}
}
