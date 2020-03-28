<?php
/**
 * @since 1.0.0
 * @package EZWPZ
 * @subpackage EZWPZ/lib
 */

namespace EZWPZ;

use EZWPZ\Core\Singleton;

/**
 * Class Plugin.
 * @since 1.0.0
 * @package EZWPZ
 */
class Plugin
{
	use Singleton;

	/**
	 * @var Admin
	 */
	protected $admin;

	/**
	 * Constructor.
	 * @since 1.0.0
	 */
	protected function __construct()
	{
		$this->admin = new Admin($this);
		add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
	}

	/**
	 * Fires during plugin activation.
	 * @since 1.0.0
	 */
	public static function activate()
	{
	}

	/**
	 * Fires during plugin deactivation.
	 * @since 1.0.0
	 */
	public static function deactivate()
	{
	}

	/**
	 * Define plugin locale for internationalization.
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain()
	{
		\load_plugin_textdomain(
			'ezwpz',
			false,
			EZWPZ_PLUGIN_DIR . 'languages'
		);
	}
}
