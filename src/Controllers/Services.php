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
	Twig\Environment,
	Devkit\WPCore\DI\OnMount,
	Devkit\WPCore\Services as Service,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\Abstracts,
	Devkit\WPCore\Helpers;

use Timber\Timber;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Services extends Abstracts\Mountable implements Interfaces\Controller
{
	/**
	 * Get definitions that should be added to the service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		$config = [];

		if ( class_exists( Timber::class ) ) {
			$config .= [
				/**
				 * Class implementations
				 */
				Service\Compiler::class             => ContainerBuilder::autowire(),
				/**
				 * Interfaces mapping
				 */
				Interfaces\Services\Compiler::class => ContainerBuilder::get( Service\Compiler::class ),
			];
		}
		return $config;
	}
	/**
	 * Mount compiler filters & add twig functions
	 *
	 * @param Interfaces\Services\Compiler $compiler : instance of compiler service.
	 *
	 * @return void
	 */
	#[OnMount]
	public function mountCompiler( Interfaces\Services\Compiler $compiler ): void
	{
		add_filter( 'timber/locations', [ $compiler, 'templateLocations' ] );

		add_action( "{$this->package}_render_template", [ $compiler, 'render' ], 10, 2 );
		add_filter( "{$this->package}_compile_template", [ $compiler, 'compile' ], 10, 2 );

		add_action( "{$this->package}_render_string", [ $compiler, 'renderString' ], 10, 2 );
		add_filter( "{$this->package}_compile_string", [ $compiler, 'compileString' ], 10, 2 );
	}
}
