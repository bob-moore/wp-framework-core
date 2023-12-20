<?php
/**
 * Environment Handler definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Traits\Handlers;

use DI\Attribute\Inject;

/**
 * Environment Handler Trait
 *
 * Allows classes that use this trait to work with environment helpers
 *
 * @subpackage Traits
 */
trait Environment
{
	/**
	 * Environment Type
	 *
	 * @var string
	 */
	protected string $env;
	/**
	 * Set the environment type
	 *
	 * @return void
	 */
	#[Inject]
	public function setEnvironment(): void
	{
		switch ( true ) {
			case in_array( wp_get_environment_type(), [ 'development', 'local', 'testing' ], true ):
				$this->env = 'dev';
				break;
			case defined( 'WP_DEBUG' ) && WP_DEBUG:
				$this->env = 'debugging';
				break;
			default:
				$this->env = 'production';
				break;
		}
	}
	/**
	 * Quickly check if in dev environment
	 *
	 * @return bool
	 */
	public function isDev(): bool
	{
		if ( ! isset( $this->env ) ) {
			$this->setEnvironment();
		}
		return 'env' === $this->env;
	}
}
