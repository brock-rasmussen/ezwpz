<?php
/*
 * @link https://github.com/brock-rasmussen/ezwpz
 * @since 1.0.0
 * @package EZWPZ
 *
 * @wordpress-plugin
 * Plugin Name: EZWPZ
 * Plugin URI: https://github.com/brock-rasmussen/ezwpz
 * Description: The EZWPZ toolkit makes WordPress development a breeze.
 * Version: 1.0.0
 * Author: Brock Rasmussen
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ezwpz
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use EZWPZ\Plugin;

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'EZWPZ_VERSION', '1.0.0' );

/**
 * Constants to easily grab files for the plugin.
 */
define( 'EZWPZ_PLUGIN_DIR', __DIR__ );
define( 'EZWPZ_PLUGIN_PATH', __FILE__ );

/**
 * Registers the autoloader.
 */
require plugin_dir_path( EZWPZ_PLUGIN_PATH ) . 'autoload.php';

/**
 * Runs during plugin activation.
 */
register_activation_hook( EZWPZ_PLUGIN_PATH, [ 'EZWPZ\Plugin', 'activate' ] );

/**
 * Runs during plugin deactivation.
 */
register_deactivation_hook( EZWPZ_PLUGIN_PATH, [ 'EZWPZ\Plugin', 'deactivate' ] );

EZWPZ\Plugin::get_instance();
