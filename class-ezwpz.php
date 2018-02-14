<?php
// CORE
require_once 'core/trait-priority.php';
require_once 'core/trait-singleton.php';
use EZWPZ\Core\Singleton;

// MENU
require_once 'menu/class-menu-manager.php';
use EZWPZ\Menu\Manager as Menu_Manager;

// SETTINGS
require_once 'settings/class-settings-manager.php';
use EZWPZ\Settings\Manager as Settings_Manager;

class EZWPZ {
  use Singleton;

  protected $menu;
  protected $settings;

  protected function __construct() {
    $this->menu = Menu_Manager::get_instance();
    $this->settings = Settings_Manager::get_instance();
    do_action('ezwpz_init');
  }

  public function menu() {
    return $this->menu;
  }

  public function settings() {
    return $this->settings;
  }
}
