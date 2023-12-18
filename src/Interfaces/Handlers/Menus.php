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

use Timber\Menu;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
interface Menus
{
    /**
     * Setter for menus field.
     *
     * @param array<string, mixed> $menus
     *
     * @return void
     */
    public function setMenus( array $menus ): void;
    /**
     * Register navigation menus with WordPress.
     *
     * @return void
     */
    public function registerNavMenus(): void;
    /**
     * Get a menu by name or ID
     *
     * @param string $name_or_id : name or ID of menu to retrieve.
     * @param array  $args       : arguments to pass to Timber::get_menu().
     *
     * @return Menu|null
     */
    public function getMenu( string $name_or_id, array $args = [] ): ?Menu;
    /**
     * Get menu with all pages.
     *
     * @return Menu
     */
    public function pagesMenu(): Menu;
}
