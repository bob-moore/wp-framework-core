<?php
/**
 * Router Service interface definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces\Services;

/**
 * Services\Router interface
 *
 * Used to type hint against Devkit\WPCore\Interfaces\Services\Router.
 *
 * @subpackage Interfaces
 */
interface Router
{
	/**
	 * Getter for routes
	 *
	 * @return array<string>
	 */
	public function getRoutes(): array;
	/**
	 * Fire actions to load routes
	 *
	 * @return void
	 */
	public function route(): void;
}
