<?php
/**
 * Style User definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Traits\Uses;

use DI\Attribute\Inject,
	Devkit\WPCore\Interfaces;

/**
 * Style user Trait
 *
 * Used by classes to import the style handler
 *
 * @subpackage Traits
 */
trait Styles
{
	/**
	 * Style handler service instance
	 *
	 * @var Interfaces\Handlers\Styles|null
	 */
	protected ?Interfaces\Handlers\Styles $style_handler;
	/**
	 * Setter for the style handler
	 *
	 * @param Interfaces\handlers\Styles $style_handler : instance of style handler.
	 *
	 * @return void
	 */
	#[Inject]
	public function setStyleHandler( 
		#[Inject( Interfaces\Handlers\Styles::class )] Interfaces\Handlers\Styles $style_handler = null
	): void
	{
		$this->style_handler = $style_handler;
	}
	/**
	 * Getter for style handler
	 *
	 * @return Interfaces\Handlers\Styles|null
	 */
	public function getStyleHandler(): ?Interfaces\Handlers\Styles
	{
		return $this->style_handler;
	}
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
	): void {
		if ( isset( $this->style_handler ) && ! is_null( $this->style_handler ) ) {
			$this->style_handler->enqueue(
				$handle,
				$path,
				$dependencies,
				$version,
				$screens
			);
		}
	}
}
