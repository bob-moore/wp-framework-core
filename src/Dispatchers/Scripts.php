<?php
/**
 * Scripts dispatcher
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Dispatchers;

use DI\Attribute\Inject,
	Devkit\WPCore\Abstracts,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\Traits;

/**
 * Dispatcher to handle JS file enqueueing
 *
 * @subpackage Dispatchers
 */
class Scripts extends Abstracts\Mountable implements
	Interfaces\Dispatchers\Scripts,
	Interfaces\Handlers\Directory,
	Interfaces\Handlers\Url
{
	use Traits\Handlers\Url;
	use Traits\Handlers\Directory;

	/**
	 * Set the base directory - relative to the main plugin file
	 *
	 * Can include an additional string, to make it relative to a different file
	 *
	 * @param string $dir : the root directory path.
	 * @param string $append : additional string to append to the directory path.
	 *
	 * @return void
	 */
	#[Inject]
	public function setDir( #[Inject( 'assets.dir' )] string $dir, string $append = '' ): void
	{
		$this->dir = untrailingslashit( $dir );
	}
	/**
	 * Set the base URL
	 * Can include an additional string for appending to the URL of the plugin
	 *
	 * @param string $url : root directory to use.
	 * @param string $append : additional string to append to the URL path.
	 *
	 * @return void
	 */
	#[Inject]
	public function setUrl( #[Inject( 'assets.url' )] string $url, string $append = '' ): void
	{
		$this->url = $this->appendUrl( $url, $append );
	}
	/**
	 * Get script assets from {handle}.asset.php
	 *
	 * @param string             $path : relative path to script.
	 * @param array<int, string> $dependencies : current dependencies passed, if any.
	 * @param string             $version : current version passed, if any.
	 *
	 * @return array<string, mixed>
	 */
	private function scriptAssets( string $path, array $dependencies = [], string $version = '' ): array
	{
		$asset_file = sprintf(
			'%s/%s.asset.php',
			$this->dir(),
			str_ireplace( '.js', '', $path )
		);



		if ( is_file( $asset_file ) ) {
			$args = include $asset_file;

			$assets = [
				'dependencies' => wp_parse_args( $args['dependencies'], $dependencies ),
				'version'      => empty( $version ) ? $args['version'] : $version,
			];
		} else {
			$assets = [
				'dependencies' => $dependencies,
				'version'      => $version,
			];
		}

		return $assets;
	}
	/**
	 * Enqueue a script in the build/dist directories
	 *
	 * @param string             $handle : handle to register.
	 * @param string             $path : relative path to script.
	 * @param array<int, string> $dependencies : any set dependencies not in assets file, optional.
	 * @param string             $version : version of JS file, optional.
	 * @param boolean            $in_footer : whether to enqueue in footer, optional.
	 *
	 * @return void
	 */
	public function enqueue(
		string $handle,
		string $path,
		array $dependencies = [],
		string $version = '',
		$in_footer = true
	): void {

		$handle = $this->register( $handle, $path, $dependencies, $version, $in_footer );

		if ( wp_script_is( $handle, 'registered' ) ) {
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Register a JS file with WordPress
	 *
	 * @param string             $handle : handle to register.
	 * @param string             $path : relative path to script.
	 * @param array<int, string> $dependencies : any set dependencies not in assets file, optional.
	 * @param string             $version : version of JS file, optional.
	 * @param boolean            $in_footer : whether to enqueue in footer, optional.
	 *
	 * @return string
	 */
	public function register(
		string $handle,
		string $path,
		array $dependencies = [],
		string $version = '',
		$in_footer = true
	): string {
		/**
		 * Get full file path
		 */
		$file = $this->dir( $path );
		/**
		 * Bail if local file, but empty
		 */
		if ( is_file( $file ) && ! filesize( $file ) ) {
			return $handle;
		}
		/**
		 * Load local assets if local file
		 */
		if ( is_file( $file ) ) {
			$assets = $this->scriptAssets( $path, $dependencies, $version );

			$dependencies = $assets['dependencies'];

			$version = ! empty( $assets['version'] ) ? $assets['version'] : filemtime( $file );

			$handle = str_replace( [ '/', '\\', ' ' ], '-', $this->package ) . '-' . $handle;

			$path = $this->url( $path );
		}

		$valid = str_starts_with( $path, '//' )
		|| filter_var( $path, FILTER_VALIDATE_URL );

		if ( ! $valid ) {
			return $handle;
		}
		/**
		 * Enqueue script
		 */
		wp_register_script(
			$handle,
			$path,
			apply_filters( "{$this->package}_{$handle}_script_dependencies", $dependencies ),
			$version,
			$in_footer
		);

		return $handle;
	}
}
