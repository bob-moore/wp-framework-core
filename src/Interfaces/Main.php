<?php
/**
 * Main interface definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces;

/**
 * Main interface requirements
 *
 * @subpackage Interfaces
 */
interface Main
{
	/**
	 * Constructor for new instance of plugin
	 *
	 * @param string $package : name of the package this instance belongs to.
	 * @param string $root_file : path to root file of the plugin.
	 */
	public function __construct( string $package = '', string $root_file = '' );
}
