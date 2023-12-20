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

use DI\Definition\ArrayDefinition,
    DI\Definition\Helper;

/**
 * Defines a attribute class that runs on mount
 *
 * @subpackage DI
 */
class ArrayDefinitionHelper implements Helper\DefinitionHelper
{
    /**
     * Values to return
     *
     * @var array
     */
    protected array $values = [];
    /**
     * Constructor
     *
     * @param array $values : array of values to return.
     */
    public function __construct( array $values )
    {
        $this->values = $values;
    }
    /**
     * Get definition
     *
     * @param string $entryName : Container entry name.
     *
     * @return ArrayDefinition
     */
    public function getDefinition( string $entryName ): ArrayDefinition 
    {
        return new ArrayDefinition( $this->values );
    }
    /**
	 * Un-nest nested arrays
	 *
	 * @param mixed   $definitions : definition array to un-nest.
	 * @param string  $key : optional key for recursion.
	 * @param integer $iterations : optional number of iterations that have ran.
	 *
	 * @return mixed
	 */
	protected function dotNotationValues( mixed $definitions, string $key = '', int $iterations = 0 ): mixed
	{
		$unnested_definitions = [];

		if ( is_array( $definitions ) ) {
			++$iterations;
			foreach ( $definitions as $nested_key => $definition ) {
				$unnested_definitions += $this->dotNotationValues( 
                    $definition, 
                    ! empty( $key ) ? $key . '.' . $nested_key : $nested_key, 
                    $iterations
                );
			}
		}
		else {
			$unnested_definitions[ $key ] = $definitions;
		}
		
		return $unnested_definitions;
	}
    /**
     * Get nested values and return as dot notation
     *
     * @param string $key : the top level array key, if any.
     *
     * @return void
     */
    public function getNestedValues( string $key ) {
        return $this->dotNotationValues( [ $key => $this->values ] );
    }
}