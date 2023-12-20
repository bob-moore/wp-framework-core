<?php
/**
 * Mountable definition
 *
 * PHP Version 8.0.28
 *
 * @package Devkit_WP_Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\DI;

use Attribute;

/**
 * Defines a attribute class that runs on mount
 *
 * @subpackage DI
 */
#[Attribute( Attribute::TARGET_METHOD )]
final class OnMount
{
	/**
	 * Priority that function runs on mount
	 *
	 * @var integer
	 */
	protected int $priority = 10;
	/**
	 * Defined parameters
	 *
	 * @var array<string, mixed>
	 */
	protected array $parameters = [];
	/**
	 * Method name
	 *
	 * @var string
	 */
	protected string $method = '';
	/**
	 * Constructor
	 *
	 * @param integer $priority : optional numeric priority. Higher runs later.
	 */
	public function __construct( int $priority = 10 )
	{
		$this->setPriority( $priority );
	}
	/**
	 * Setter for $priority field
	 *
	 * @param integer $priority : numeric priority. Higher runs later.
	 *
	 * @return void
	 */
	public function setPriority( int $priority ): void
	{
		$this->priority = $priority;
	}
	/**
	 * Getter for $priority field
	 *
	 * @return integer
	 */
	public function getPriority(): int
	{
		return $this->priority;
	}
	/**
	 * Setter for $method field
	 *
	 * @param string $method : name of method attribute belongs to.
	 *
	 * @return void
	 */
	public function setMethod( string $method ): void
	{
		$this->method = $method;
	}
	/**
	 * Getter for method field
	 *
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}
	/**
	 * Setter for parameters
	 *
	 * @param array<string, mixed> $parameters : array of parameters to override existing or set in bulk.
	 *
	 * @return void
	 */
	public function setParameters( array $parameters ): void
	{
		$this->parameters = array_merge( $this->parameters, $parameters );
	}
	/**
	 * Setter for single parameter
	 *
	 * @param string $parameter : name of single parameter.
	 * @param mixed  $value : value of single parameter.
	 *
	 * @return void
	 */
	public function setParameter( string $parameter, mixed $value ): void
	{
		$this->parameters[ $parameter ] = $value;
	}
	/**
	 * Getter for parameters
	 *
	 * @return array<string, mixed>
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}
}
