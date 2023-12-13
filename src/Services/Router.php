<?php
/**
 * Router Service Definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Services;

use Devkit\WPCore\Abstracts,
	Devkit\WPCore\Interfaces;

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
		return array_reverse( apply_filters( "{$this->package}_routes", $routes ) );
	}
	/**
	 * Getter for routes
	 * 
	 * @param array<string> $default_routes : routes to prepend to the list.
	 *
	 * @return array<string>
	 */
	public function getRoutes( array $default_routes = [] ): array
	{
		if ( empty( $this->routes ) ) {
			$this->routes = $this->defineRoutes();
		}

		return ! empty( $default_routes )
		? array_merge( $default_routes, $this->routes )
		: $this->routes;
	}
	/**
	 * Fire router ready action
	 *
	 * @return void
	 */
	public function dispatchRoutes(): void
	{
		do_action( "{$this->package}_router_ready", $this->getRoutes() );
	}
}
