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

namespace Devkit\WPCore\Controllers;

use Devkit\WPCore\DI\ContainerBuilder,
	Devkit\WPCore\Handlers as Handler,
	Devkit\WPCore\DI\OnMount,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\Traits,
	Devkit\WPCore\Abstracts,
	Devkit\WPCore\Helpers;

use DI\Attribute\Inject;

use Psr\Container\ContainerInterface;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Handlers extends Abstracts\Mountable implements Interfaces\Controller
{
	use Traits\Handlers\Directory;

	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			/**
			 * Class Implementations
			 */
			Handler\Posts::class               => ContainerBuilder::autowire(),
			Handler\Terms::class               => ContainerBuilder::autowire(),
			Handler\Blocks::class              => ContainerBuilder::autowire(),
			Handler\Menus::class               => ContainerBuilder::autowire(),
			Handler\Styles::class              => ContainerBuilder::autowire(),
			Handler\Scripts::class             => ContainerBuilder::autowire(),
			/**
			 * Interfaces Aliases
			 */
			Interfaces\Handlers\Posts::class   => ContainerBuilder::get( Handler\Posts::class ),
			Interfaces\Handlers\Terms::class   => ContainerBuilder::get( Handler\Terms::class ),
			Interfaces\Handlers\Blocks::class  => ContainerBuilder::get( Handler\Blocks::class ),
			Interfaces\Handlers\Menus::class   => ContainerBuilder::get( Handler\Menus::class ),
			Interfaces\Handlers\Styles::class  => ContainerBuilder::get( Handler\Styles::class ),
			Interfaces\Handlers\Scripts::class => ContainerBuilder::get( Handler\Scripts::class ),
		];
	}
	/**
	 * Set the base directory referenced by this class
	 *
	 * @param string $app_dir : path to directory containing blocks.
	 * @param string $assets_dir : additional string to append to the directory path.
	 *
	 * @return void
	 */
	#[Inject]
	public function setDir( 
		#[Inject( 'config.dir' )] string $app_dir, 
		#[Inject( 'config.assets.dir' )] string $assets_dir
	): void
	{
		$this->dir = $this->appendDir( $app_dir, $assets_dir );
	}
	/**
	 * Mount blocks handler
	 *
	 * @param Handler\Blocks $handler : instance of block handler.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountBlocks( Handler\Blocks $handler ): void
	{
		if ( is_dir( $this->dir( 'blocks' ) ) ) {
			foreach ( glob( $this->dir( 'blocks/*/block.json' ) ) as $block_file ) {
				$handler->setBlocks( $block_file );
			}
	
			add_action( 'init', [ $handler, 'registerBlocks' ] );
		}
	}
}
