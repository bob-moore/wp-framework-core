<?php
/**
 * Container Builder
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\DI;

use Devkit\WPCore\Helpers,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\Deps,
	DI\Definition\Source\DefinitionSource,
	DI\Definition\Reference,
	DI\Definition\Helper;

use Psr\Container\ContainerInterface;

/**
 * Builder for Service Containers
 *
 * @subpackage DI
 */
class ContainerBuilder extends \DI\ContainerBuilder
{
	/**
	 * Saved containers for later retrieval
	 *
	 * Used to cache containers, so services can be retrieved without
	 * singletons
	 *
	 * @var array<string, ContainerInterface>
	 */
	protected static array $containers = [];
	/**
	 * Constructor for new instances
	 *
	 * Sets parent containerClass to DL\Container instead of default
	 */
	public function __construct()
	{
		parent::__construct( Container::class );
	}
	/**
	 * Get a cached service container
	 *
	 * @param string $container_id : name of cached container to retrieve.
	 *
	 * @return ContainerInterface|null
	 */
	public static function locateContainer( string $container_id ): ContainerInterface|null
	{
		return isset( self::$containers[ $container_id ] ) ? self::$containers[ $container_id ] : null;
	}
	/**
	 * Save a service container to the cache
	 *
	 * @param string             $container_id : name of container to reference it by.
	 * @param ContainerInterface $container : Container instance to cache.
	 *
	 * @return void
	 */
	public static function cacheContainer( string $container_id, ContainerInterface $container ): void
	{
		self::$containers[ $container_id ] = $container;
	}
	/**
	 * Add definitions to the container.
	 *
	 * @param string|array<mixed>|DefinitionSource ...$definitions Can be an array of definitions, the
	 *                                                      name of a file containing definitions
	 *                                                      or a DefinitionSource object.
	 * @return $this
	 */
	public function addDefinitions( string|array|DefinitionSource ...$definitions ): self
	{
		$controller_definitions = [];

		foreach ( $definitions as $definition ) {
			$controller_definitions += $this->autowireControllers( $definition );
		}

		parent::addDefinitions( ...$definitions );

		parent::addDefinitions( $controller_definitions );

		return $this;
	}
	/**
	 * Auto wire controllers
	 *
	 * @param string|array<string, mixed>|DefinitionSource|Helper\DefinitionHelper $definition : definition(s).
	 * @param string                                                               $key : optional key, used in recursion.
	 *
	 * @return array<string, mixed>
	 */
	protected function autowireControllers(
		string|array|DefinitionSource|Helper\DefinitionHelper $definition,
		string $key = ''
	): array {
		$extended_definitions = [];

		if ( is_array( $definition ) ) {
			foreach ( $definition as $key => $definitions ) {
				$extended_definitions += $this->autowireControllers( $definitions, $key );
			}
		} elseif (
			is_object( $definition )
			&& is_subclass_of( $definition, Helper\DefinitionHelper::class )
			&& ! empty( $key )
		) {
			$definition_object = $definition->getDefinition( $key );

			if (
				is_object( $definition_object )
				&& method_exists( $definition_object, 'getClassName' )
			) {
				$class_name = $definition_object->getClassName();

				if ( Helpers::implements( $class_name, Interfaces\Controller::class ) ) {
					$extended_definitions = $class_name::getServiceDefinitions();
				}
			}
		}

		return $extended_definitions;
	}
	/**
	 * Wrapper for parent auto wire function. Only used for simplicity
	 *
	 * @param string $class_name : name of service to auto wire.
	 *
	 * @return Helper\DefinitionHelper
	 */
	public static function autowire( string $class_name = null ): Helper\DefinitionHelper
	{
		return \DI\autowire( $class_name );
	}
	/**
	 * Helper for defining an object.
	 *
	 * @param string|null $class_name Class name of the object.
	 *                               If null, the name of the entry (in the container) will be used as class name.
	 */
	public static function create( string $class_name = null ): Helper\DefinitionHelper
	{
		return \DI\create( $class_name );
	}
	/**
	 * Wrapper for parent get function. Only used for simplicity
	 *
	 * @param string $class_name : name of service to retrieve.
	 *
	 * @return Reference;
	 */
	public static function get( string $class_name ): Reference
	{
		return \DI\get( $class_name );
	}

	/**
	 * Helper for defining a container entry using a factory function/callable.
	 *
	 * @param callable|array<mixed>|string $factory : The factory is a callable that takes the container as parameter
	 *                                                and returns the value to register in the container.
	 */
	public static function factory( callable|array|string $factory ): Helper\DefinitionHelper
	{
		return \DI\factory( $factory );
	}
	/**
	 * Decorate the previous definition using a callable.
	 *
	 * Example:
	 *
	 *     'foo' => decorate(function ($foo, $container) {
	 *         return new CachedFoo($foo, $container->get('cache'));
	 *     })
	 *
	 * @param callable|array<mixed>|string $decorator : The callable takes the decorated object as first parameter and
	 *                                                  the container as second.
	 */
	public static function decorate( callable|array|string $decorator ): Helper\DefinitionHelper
	{
		return \DI\decorate( $decorator );
	}
}
