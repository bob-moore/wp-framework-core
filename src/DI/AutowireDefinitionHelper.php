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

use DI\Definition\Helper;

use Devkit\WPCore\Helpers,
    Devkit\WPCore\Interfaces;

/**
 * Defines a attribute class that runs on mount
 *
 * @subpackage DI
 */
class AutowireDefinitionHelper extends Helper\AutowireDefinitionHelper
{
    /**
     * Return children of auto wired controller classes.
     *
     * @param string $key : the key to get the definition for.
     *
     * @return array<string, mixed>
     */
    public function autowireChildren( string $key = '' ): array
    {
        $children = [];

        $definition = $this->getDefinition( $key );

        if ( is_object( $definition ) ) {
            
            $class_name = $definition->getClassName();
            
            if ( Helpers::implements( $class_name, Interfaces\Controller::class ) ) {
                
                $children = $class_name::getServiceDefinitions();
            }
        }
        return $children;
    }
}
