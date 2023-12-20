<?php
/**
 * Uses Styles interface definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Interfaces\Uses;

use Devkit\WPCore\Interfaces;

/**
 * Uses\Styles interface
 *
 * Used to type hint against Devkit\WPCore\Interfaces\Uses\Styles.
 *
 * @subpackage Interfaces
 */
interface Styles
{
	/**
	 * Setter for the style handler
	 *
	 * @param Interfaces\Handlers\Styles $style_handler : instance of style handler.
	 *
	 * @return void
	 */
	public function setStyleHandler( Interfaces\Handlers\Styles $style_handler = null ): void;
	/**
	 * Getter for style handler
	 *
	 * @return Interfaces\Handlers\Styles|null
	 */
	public function getStyleHandler(): ?Interfaces\Handlers\Styles;
	/**
	 * Enqueue a style in the dist/build directories
	 *
	 * @param string             $handle : handle to register.
	 * @param string             $path : relative path to css file.
	 * @param array<int, string> $dependencies : any dependencies that should be loaded first, optional.
	 * @param string             $version : version of CSS file, optional.
	 * @param string             $screens : what screens to register for, optional.
	 *
	 * @return void
	 */
	public function enqueueStyle(
		string $handle,
		string $path,
		array $dependencies = [],
		string $version = null,
		$screens = 'all'
	): void;
}
