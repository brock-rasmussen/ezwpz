<?php
require_once 'menu/class-menu-manager.php';

use EZWPZ\Admin\Menu\Manager;

function ezwpz_menu_init() {
  do_action('ezwpz_menu', Manager::get_instance());
}
add_action('init', 'ezwpz_menu_init');
