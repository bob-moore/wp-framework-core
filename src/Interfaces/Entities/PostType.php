<?php
/**
 * Post Type Interface
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces\Entities;

/**
 * Post Type definition
 *
 * @subpackage Interfaces
 */
interface PostType
{
	/**
	 * Getter for post type name
	 *
	 * @return string
	 */
	public function getName(): string;
	/**
	 * Getter for post type definition
	 *
	 * @return array<string, mixed>
	 */
	public function getDefinition(): array;
}
