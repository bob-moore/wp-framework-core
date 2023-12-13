<?php
/**
 * Admin Route Definition
 *
 * PHP Version 8.0.28
 *
 * @package WP Plugin Skeleton
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/wp-framework-core
 * @since   1.0.0
 */

namespace Devkit\WPCore\Routes;

use Devkit\WPCore\Abstracts,
	Devkit\WPCore\Traits,
	Devkit\WPCore\Interfaces,
	Devkit\WPCore\DI\OnMount;

/**
 * Admin router class
 *
 * @subpackage Route
 */
class Admin extends Abstracts\Mountable implements Interfaces\Uses\ScriptDispatcher, Interfaces\Uses\StyleDispatcher
{
	use Traits\Uses\ScriptDispatcher;
	use Traits\Uses\StyleDispatcher;

	/**
	 * Load actions and filters, and other setup requirements
	 *
	 * @return void
	 */
	#[OnMount]
	public function mount(): void
	{
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAssets' ] );
	}
	/**
	 * Enqueue admin styles and JS bundles
	 *
	 * @return void
	 */
	public function enqueueAssets(): void
	{
		$this->enqueueScript(
			'admin',
			'admin/bundle.js'
		);
		$this->enqueueStyle(
			'admin',
			'admin/bundle.css'
		);
	}
}
