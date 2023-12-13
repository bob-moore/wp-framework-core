<?php
/**
 * Style User definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
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
 * Used by classes to import the style dispatcher
 *
 * @subpackage Traits
 */
trait StyleDispatcher
{
	/**
	 * Style handler service instance
	 *
	 * @var Interfaces\Dispatchers\Styles|null
	 */
	protected ?Interfaces\Dispatchers\Styles $style_dispatcher;
	/**
	 * Setter for the style dispatcher
	 *
	 * @param Interfaces\Dispatchers\Styles $style_dispatcher : instance of style dispatcher.
	 *
	 * @return void
	 */
	#[Inject]
	public function setStyleDispatcher( Interfaces\Dispatchers\Styles $style_dispatcher ): void
	{
		$this->style_dispatcher = $style_dispatcher;
	}
	/**
	 * Getter for style dispatcher
	 *
	 * @return Interfaces\Dispatchers\Styles|null
	 */
	public function getStyleDispatcher(): ?Interfaces\Dispatchers\Styles
	{
		return $this->style_dispatcher;
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
		$this->style_dispatcher->enqueue(
			$handle,
			$path,
			$dependencies,
			$version,
			$screens
		);
	}
}
