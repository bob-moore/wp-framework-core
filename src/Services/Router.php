<?php
/**
 * Router Service Definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Services;

use Devkit\WPCore\Abstracts,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\DI\OnMount;

/**
 * Service class for router actions
 *
 * @subpackage Services
 */
class Router extends Abstracts\Mountable implements Interfaces\Services\Router
{
	/**
	 * Routes available on current context
	 *
	 * @var array<int, string>
	 */
	protected array $routes = [];
	// /**
	//  * Fire Mounted action on mount
	//  *
	//  * @return void
	//  */
	// #[OnMount]
	// public function mount(): void
	// {
	// 	if ( ! $this->hasMounted() ) {
	// 		do_action( "{$this->slug()}_mounted" );
	// 	}
	// }
	/**
	 * Define current route(s)
	 *
	 * Can't be run until 'wp' action when the query is available
	 *
	 * @return array<string>
	 */
	protected function defineRoutes(): array
	{
		$routes = match ( true ) {
			is_front_page() && ! is_home() => [ 'single', 'frontpage' ],
			is_home() => [ 'archive', 'blog' ],
			is_search() => [ 'archive', 'search' ],
			is_archive() => [ 'archive' ],
			is_singular() => [ 'single' ],
			is_404() => [ '404' ],
			is_login() => [ 'login' ],
			is_admin() => [ 'admin' ],
			default => []
		};

		if ( ! is_admin() && ! is_login() ) {
			array_unshift( $routes, 'frontend' );
		}

		return array_reverse( apply_filters( "{$this->package}_routes", $routes ) );
	}
	/**
	 * Getter for routes
	 *
	 * @return array<string>
	 */
	public function getRoutes(): array
	{
		if ( empty( $this->routes ) ) {
			$this->routes = $this->defineRoutes();
		}
		return $this->routes;
	}
	/**
	 * Setter for $route
	 *
	 * @return void
	 */
	public function route(): void
	{
		foreach ( $this->getRoutes() as $route ) {
			do_action( "{$this->package}_trigger_route", $route );
		}
	}
}
