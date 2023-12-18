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

namespace Devkit\WPCore\Handlers;

use Devkit\WPCore\Interfaces,
	Devkit\WPCore\Abstracts;

use Timber\Timber,
    Timber\Menu;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Menus extends Abstracts\Mountable implements Interfaces\Handlers\Menus
{
    /**
     * Array of nav menus
     *
     * @var array<string, mixed>
     */
    protected array $menus = [];
    /**
     * Setter for menus field.
     *
     * @param array<string, mixed> $menus
     *
     * @return void
     */
    public function setMenus( array $menus ): void
    {
        $this->menus = array_merge( $this->menus, $menus );
    }
    /**
     * Register navigation menus with WordPress.
     *
     * @return void
     */
    public function registerNavMenus(): void
    {
        $menus = apply_filters( "{$this->package}_nav_menus", $this->menus );

        register_nav_menus( $menus );
    }
    /**
     * Get a menu by name or ID
     *
     * @param string $name_or_id : name or ID of menu to retrieve.
     * @param array  $args       : arguments to pass to Timber::get_menu().
     *
     * @return Menu|null
     */
    public function getMenu( string $name_or_id, array $args = [] ): ?Menu
    {
        if ( has_nav_menu( $name_or_id ) || is_nav_menu( $name_or_id ) )
		{
			return Timber::get_menu( $name_or_id, $args );
		}
    }
    /**
     * Get menu with all pages.
     *
     * @return Menu
     */
    public function pagesMenu(): Menu
    {
        return Timber::get_pages_menu();
    }
}
