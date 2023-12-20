<?php
/**
 * Controller interface definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces;

/**
 * Define controller requirements
 *
 * @subpackage Interfaces
 */

interface Controller
{
	/**
	 * Return an array of service definitions
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array;
}
