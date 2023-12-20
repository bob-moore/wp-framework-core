<?php
/**
 * Main interface definition
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
use Psr\Container\ContainerInterface;

/**
 * Main interface requirements
 *
 * @subpackage Interfaces
 */
interface Main
{
	/**
	 * Default constructor
	 *
	 * @param array $config : configuration array to merge with defaults.
	 */
	public function __construct( array $config = [] );
		/**
	 * Helper function to mount new instance of class
	 *
	 * @param array $config : configuration array to merge with defaults.
	 *
	 * @return self
	 */
	public static function mount( array $config = [] ): static;
		/**
	 * Setter for service container
	 *
	 * @param ContainerInterface $container : instance of service container.
	 *
	 * @return void
	 */
	public function setContainer( ContainerInterface $container ): void;
		/**
	 * Locate a specific service
	 *
	 * Use primarily by 3rd party interactions to remove actions/filters
	 *
	 * @param string $service : name of service to locate.
	 *
	 * @return mixed
	 */
	public static function locateService( string $service, string $package = '' ): mixed;
		/**
	 * Check if a specific service exists
	 *
	 * @param string $service : name of service to locate.
	 * @param string $package : package id of container to search in.
	 *
	 * @return mixed
	 */
	public static function hasService( string $service, string $package = '' ): mixed;
}
