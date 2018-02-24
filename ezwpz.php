<?php
/*
 * Plugin Name: Easy WPeasy
 * Author: Brock Rasmussen
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

define('EZWPZ_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));

require_once 'class-ezwpz.php';

/**
 * Function to return the EZWPZ class.
 * @return EZWPZ
 */
function ezwpz() {
  return EZWPZ::get_instance();
}

/**
 * Instantiate EZWPZ.
 */
ezwpz();
