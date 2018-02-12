<?php
require_once 'settings/class-settings-manager.php';

use EZWPZ\Admin\Settings\Manager;

function ezwpz_settings_init() {
  do_action('ezwpz_settings', Manager::get_instance());
}
add_action('init', 'ezwpz_settings_init');
