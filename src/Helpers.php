<?php
/**
 * Helper Functions
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore;

/**
 * App Factory
 *
 * @subpackage Utilities
 */
class Helpers
{
	/**
	 * Check if a class uses a specified parent class, interface, or trait
	 *
	 * @param string|object $instance_or_class : class or object to check.
	 * @param string        $needle : interface, class, or trait name.
	 *
	 * @return boolean
	 */
	public static function classUses( string|object $instance_or_class, string $needle ): bool
	{
		$class_name = self::className( $instance_or_class );

		if ( empty( $class_name ) ) {
			return false;
		}

		return match ( true ) {
			class_exists( $needle )     => is_subclass_of( $class_name, $needle ),
			interface_exists( $needle ) => self::implements( $class_name, $needle ),
			trait_exists( $needle )     => self::uses( $class_name, $needle ),
			default                         => false
		};
	}
	/**
	 * Get the string class name of an object or name
	 *
	 * @param string|object $instance_or_class : either an object or class name.
	 *
	 * @return string|false
	 */
	public static function className( string|object $instance_or_class ): string|false
	{
		$name = match ( true ) {
			is_object( $instance_or_class ) => get_class( $instance_or_class ),
			class_exists( $instance_or_class ) => $instance_or_class,
			default => false
		};
		return $name;
	}
	/**
	 * Recursive `uses` function
	 *
	 * Used to check all traits of class and parent to see if it is implemented
	 * in the target class/object
	 *
	 * @param string|object $instance_or_class : instance or class name.
	 * @param string        $trait_class : trait name to use.
	 *
	 * @return boolean
	 */
	public static function uses( string|object $instance_or_class, string $trait_class ): bool
	{
		$class_name = is_object( $instance_or_class ) ? get_class( $instance_or_class ) : $instance_or_class;

		if ( ! class_exists( $class_name ) ) {
			return false;
		}
		return in_array( $trait_class, self::getTraits( $class_name ), true );
	}
	/**
	 * Check if class implements an interface
	 *
	 * Checks against a supplied interface class name.
	 *
	 * @param string|object $instance_or_class : instance or class name.
	 * @param string        $interface_class : interface class to check against.
	 *
	 * @return boolean
	 */
	public static function implements( string|object $instance_or_class, string $interface_class ): bool
	{
		$class_name = is_object( $instance_or_class ) ? get_class( $instance_or_class ) : $instance_or_class;

		if ( ! class_exists( $class_name ) ) {
			return false;
		}
		return in_array( $interface_class, class_implements( $class_name ), true );
	}
	/**
	 * Recursively get all traits used by stack
	 *
	 * @param object|string $instance_or_class : class to check.
	 *
	 * @return array<string>
	 */
	public static function getTraits( object|string $instance_or_class ): array
	{
		$class_name = is_object( $instance_or_class ) ? get_class( $instance_or_class ) : $instance_or_class;

		if ( ! class_exists( $class_name ) ) {
			return [];
		}

		$traits = self::usesTrait( $class_name );

		$parents = class_parents( $class_name );

		if ( ! empty( $parents ) ) {
			foreach ( $parents as $parent ) {
				$traits += self::usesTrait( $parent );
			}
		}
		return array_unique( $traits );
	}
	/**
	 * Check traits of a single class.
	 *
	 * @param string|object $instance_or_class : class to check.
	 *
	 * @return array<string>
	 */
	public static function usesTrait( string|object $instance_or_class ): array
	{
		$class_name = is_object( $instance_or_class ) ? get_class( $instance_or_class ) : $instance_or_class;

		if ( ! class_exists( $class_name ) ) {
			return [];
		}

		$traits = class_uses( $class_name );

		return ! empty( $traits ) ? $traits : [];
	}
	/**
	 * Wrapper to call functions from twig
	 *
	 * @param mixed ...$args : all arguments passed, unknown.
	 *
	 * @return mixed
	 */
	public function executeFunction( ...$args )
	{
		$function = array_shift( $args );
		ob_start();
		try {
			$output  = is_callable( $function ) ? call_user_func( $function, ...$args ) : null;
			$content = ob_get_clean();
			return $output ?? $content;
		} catch ( \Error $e ) {
			return null;
		}
	}
	/**
	 * Check if a particular plugin is active and present in the environment
	 *
	 * @param string $plugin : dir/name.php of the plugin to check.
	 *
	 * @return bool
	 */
	public static function isPluginActive( string $plugin ): bool
	{
		if ( ! defined( 'ABSPATH' ) || ! defined( 'WP_PLUGIN_DIR' ) ) {
			return false;
		}

		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_file( WP_PLUGIN_DIR . '/' . $plugin ) && is_plugin_active( $plugin );
	}
	/**
	 * Replace slashes and spaces in a string with underscores, and convert to lowercase
	 *
	 * @param string $raw_string : string to slugify.
	 *
	 * @return string
	 */
	public static function slugify( string $raw_string ): string
	{
		return strtolower( str_replace( [ '\\', '/', ' ', '-' ], '_', $raw_string ) );
	}
	/**
	 * Attempt to infer the plugin directory for this application.
	 *
	 * @return string
	 */
	public static function defaultPluginDir(): string
	{
		$base = str_replace( trailingslashit( WP_PLUGIN_DIR ), '', __DIR__ );

		$parts = explode( '/', $base );

		return trailingslashit( WP_PLUGIN_DIR ) . $parts[0] . '/';
	}
		/**
		 * Recursively search an array for a pipe seperated value. Pipe is used to search nested arrays as many levels deep
		 * as necessary, until no more search terms are available or the value is found.
		 *
		 * @param array $haystack array to search in
		 * @param array|string $needle needle to look for, reduced until single value. Array or pipe seperated string
		 * @return mixed|null
		 */
	/**
	 * Search an array using string notation
	 *
	 * @param array<mixed>         $haystack : the array to search.
	 * @param string|array<string> $needle : what key to search for.
	 * @param string               $separator : optional separator to use, defaults to '.'.
	 *
	 * @return mixed
	 */
	public static function searchArray( array $haystack, string|array $needle, $separator = '.' ): mixed
	{
		if ( is_string( $needle ) ) {
			$needle = explode( $separator, $needle );
		}

		$current = array_shift( $needle );

		if ( empty( $needle ) ) {
			return $haystack[ $current ] ?? null;
		} elseif ( ! isset( $haystack[ $current ] ) || ! is_array( $haystack[ $current ] ) ) {
			return null;
		} else {
			return self::searchArray( $haystack[ $current ], $needle );
		}
	}
	/**
	 * Infer the type of package this is being used in.
	 *
	 * @return string
	 */
	public static function packageType(): string
	{
		$path = realpath( __DIR__ );

		return match ( true ) {
			str_contains( $path, WP_PLUGIN_DIR ) => 'plugin',
			str_contains( $path, get_stylesheet_directory() )
			&& ! str_contains( $path, get_template_directory() ) => 'child-theme',
			str_contains( $path, get_template_directory() ) => 'theme',
			default => 'child-theme',
		};
	}

	/**
	 * Infer default directory based on type.
	 *
	 * @param string|null $package_type : the package type to use.
	 *
	 * @return string
	 */
	public static function getDefaultDir( ?string $package_type = null ): string
	{
		return match ( $package_type ?? self::packageType() ) {
			'plugin'      => self::defaultPluginDir(),
			'theme'       => untrailingslashit( get_template_directory() ),
			'child-theme' => untrailingslashit( get_stylesheet_directory() ),
			default       => untrailingslashit( get_stylesheet_directory() ),
		};
	}
	/**
	 * Infer url based on type.
	 *
	 * @param string|null $dir : the base directory to use.
	 * @param string|null $package_type : the package type to use.
	 *
	 * @return string
	 */
	public static function getDefaultUrl( ?string $dir = null, ?string $package_type = null ): string
	{
		return match ( $package_type ?? self::packageType() ) {
			'plugin'      => plugin_dir_url(
				trailingslashit(
					trailingslashit( $dir ?? self::getDefaultDir( $package_type ) ) . '*.php'
				)
			),
			'theme'       => untrailingslashit( get_template_directory_uri() ),
			'child-theme' => untrailingslashit( get_stylesheet_directory_uri() ),
			default       => untrailingslashit( get_stylesheet_directory_uri() ),
		};
	}
	/**
	 * Log an item to query monitor
	 *
	 * @param mixed $item : the thing to log.
	 *
	 * @return void
	 */
	public static function debug( mixed $item ): void
	{
		do_action( 'qm/debug', $item );
	}
}
