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

namespace Devkit\WPCore\Handlers;

use Devkit\WPCore\Helpers,
    Devkit\WPCore\Interfaces,
	Devkit\WPCore\Abstracts;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Posts extends Abstracts\Mountable implements Interfaces\Handlers\Posts
{
    /**
	 * Array of custom post types
	 *
	 * @var array<Interfaces\Entities\PostType>
	 */
	protected array $post_types = [];
    /**
	 * Post Type Setter
	 *
	 * @param Interfaces\Entities\PostType ...$post_types : array of post type objects.
	 *
	 * @return void
	 */
	public function setPostTypes( Interfaces\Entities\PostType ...$post_types ): void
	{
		$this->post_types = array_merge( $this->post_types, $post_types );
	}
    /**
	 * Register custom post types
	 *
	 * @return void
	 */
	public function registerPostTypes(): void
	{
        $post_types = apply_filters( "{$this->package}_post_types", $this->post_types );

		foreach ( $post_types as $post_type ) {
			if ( ! Helpers::implements( $post_type, Interfaces\Entities\PostType::class ) ) {
				continue;
			}
			register_post_type( $post_type->getName(), $post_type->getDefinition() );
		}
	}
}
