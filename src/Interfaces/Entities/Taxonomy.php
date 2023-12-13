<?php
/**
 * Taxonomy interface definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces\Entities;

/**
 * Taxonomy definition
 *
 * @subpackage Interfaces
 */
interface Taxonomy
{
	/**
	 * Getter for taxonomy name
	 *
	 * @return string
	 */
	public function getName(): string;
	/**
	 * Getter for taxonomy definition
	 *
	 * @return array<string, mixed>
	 */
	public function getDefinition(): array;
	/**
	 * Getter for taxonomy post types
	 *
	 * @return array<string>
	 */
	public function getPostTypes(): array;
}
