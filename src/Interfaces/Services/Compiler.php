<?php
/**
 * Compiler Service interface definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces\Services;

use Twig\Environment;

/**
 * Service class for router actions
 *
 * @subpackage Interfaces
 */
interface Compiler
{
	/**
	 * Filters the default locations array for twig to search for templates. We never use some paths, so there's
	 * no reason to waste overhead looking for templates there.
	 *
	 * @param array<string,mixed> $locations : Array of absolute paths to
	 *                                        available templates.
	 *
	 * @return array<string,mixed> $locations
	 */
	public function templateLocations( array $locations ): array;
	/**
	 * Register custom function with TWIG
	 *
	 * @param Environment $twig : instance of twig environment.
	 *
	 * @return Environment
	 */
	public function loadFunctions( Environment $twig ): Environment;
	/**
	 * Register custom filters with TWIG
	 *
	 * @param Environment $twig : instance of twig environment.
	 *
	 * @return Environment
	 */
	public function loadFilters( Environment $twig ): Environment;
	/**
	 * Add a function to collection of twig functions
	 *
	 * @param string                   $name : name of function to bind.
	 * @param string|array<int, mixed> $callback : callback function.
	 * @param array<string, mixed>     $args : args to add to twig function.
	 *
	 * @see https://twig.symfony.com/doc/3.x/advanced.html
	 * @see https://timber.github.io/docs/guides/extending-timber/
	 *
	 * @return void
	 */
	public function addFunction( string $name, string|array $callback, array $args = [] ): void;
	/**
	 * Add a filter to collection of twig functions
	 *
	 * @param string                   $name : name of filter to bind.
	 * @param string|array<int, mixed> $callback : callback function.
	 * @param array<string, mixed>     $args : args to add to twig filter.
	 *
	 * @see https://twig.symfony.com/doc/3.x/advanced.html
	 * @see https://timber.github.io/docs/guides/extending-timber/
	 *
	 * @return void
	 */
	public function addFilter( string $name, string|array $callback, array $args = [] ): void;
}
