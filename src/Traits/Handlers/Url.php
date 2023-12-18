<?php
/**
 * URL Handler definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Traits\Handlers;

use DI\Attribute\Inject;

/**
 * URL Handler Trait
 *
 * Allows classes that use this trait to work with URL helpers
 *
 * @subpackage Traits
 */
trait Url
{
	/**
	 * URL to plugin instance
	 *
	 * @var string
	 */
	protected string $url = '';
	/**
	 * Set the base URL
	 * Can include an additional string for appending to the URL of the plugin
	 *
	 * @param string $url : root directory to use.
	 * @param string $append : string to append to the directory path.
	 *
	 * @return void
	 */
	#[Inject]
	public function setUrl( #[Inject( 'config.url' )] string $url, string $append = '' ): void
	{
		$this->url = $this->appendUrl( $url, $append );
	}
	/**
	 * Get the url with string appended
	 *
	 * @param string $append : string to append to the URL.
	 *
	 * @return string complete url
	 */
	public function url( string $append = '' ): string
	{
		return $this->appendUrl( $this->url, $append );
	}
	/**
	 * Append string safely to end of a url
	 *
	 * @param string $base : the base url.
	 * @param string $append : the string to append.
	 *
	 * @return string
	 */
	protected function appendUrl( string $base, string $append = '' ): string
	{
		return ! empty( $append )
			? untrailingslashit( trailingslashit( $base ) . ltrim( $append, '/' ) )
			: untrailingslashit( $base );
	}
}
