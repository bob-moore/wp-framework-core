<?php
/**
 * Handler Controller
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
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
interface Posts
{
    /**
	 * Post Type Setter
	 *
	 * @param Entities\PostType ...$post_types : array of post type objects.
	 *
	 * @return void
	 */
	public function setPostTypes( Entities\PostType ...$post_types ): void;
    /**
	 * Register custom post types
	 *
	 * @return void
	 */
	public function registerPostTypes(): void;
}
