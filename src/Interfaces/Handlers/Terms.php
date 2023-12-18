<?php
/**
 * Handler Controller
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces\Handlers;

use Devkit\WPCore\Interfaces\Entities;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
interface Terms
{
    /**
	 * Taxonomies setter
	 *
	 * @param Entities\Taxonomy ...$taxonomies : array of taxonomy objects.
	 *
	 * @return void
	 */
	public function setTaxonomies( Entities\Taxonomy ...$taxonomies ): void;
	/**
	 * Register custom taxonomies
	 *
	 * @return void
	 */
	public function registerTaxonomies(): void;
}
