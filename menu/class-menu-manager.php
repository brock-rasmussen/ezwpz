<?php
namespace EZWPZ\Menu;

require_once 'class-page.php';
use EZWPZ\Core\Singleton;

class Manager {
  use Singleton;

  /**
   * Instances of Page
   * @var array
   */
  protected $pages = [];

  /**
   * Instances of Help_Tab
   * @var array
   */
  protected $help_tabs = [];

  /**
   * Manager constructor.
   */
  protected function __construct() {
    \add_action('init', [$this, 'init']);
    \add_action('admin_menu', [$this, 'init_pages']);
  }

  /**
   * Add action that plugins and themes can tie into to easily access the class.
   */
  public function init() {
    \do_action('ezwpz_menu', self::get_instance());
  }

  /**
   * Add a page.
   * @param string $id
   * @param array $args
   */
  public function add_page($id, $args = []) {
    if ($id instanceof Page)
      $page = $id;
    else
      $page = new Page($this, $id, $args);

    $this->pages[$id] = $page;
  }

  /**
   * Get a page.
   * @param string $id
   * @return bool|mixed
   */
  public function get_page($id) {
    if (isset($this->pages[$id]))
      return $this->pages[$id];
    return false;
  }

  /**
   * Remove a page.
   * @param string $id
   * @return bool
   */
  public function remove_page($id) {
    if (isset($this->pages[$id])) {
      unset ($this->pages[$id]);
      return true;
    }
    return false;
  }

  /**
   * Initialize Pages.
   */
  public function init_pages() {
    if (!empty($this->pages) && \is_array($this->pages)) {
      foreach ($this->pages as $page) {
        $page->init();
      }
    }
  }

  /**
   * Add a page.
   * @param string $id
   * @param array $args
   */
//  public function add_help_tab($id, $args = []) {
//    if ($id instanceof Page)
//      $help_tab = $id;
//    else
//      $help_tab = new Page($this, $id, $args);
//
//    $this->help_tabs[$id] = $help_tab;
//  }

  /**
   * Get a help_tab.
   * @param string $id
   * @return bool|mixed
   */
//  public function get_help_tab($id) {
//    if (!isset($this->help_tabs[$id]))
//      return false;
//    return $this->help_tabs[$id];
//  }

  /**
   * Remove a help_tab.
   * @param string $id
   * @return bool
   */
//  public function remove_help_tab($id) {
//    if (isset($this->help_tabs[$id])) {
//      unset ($this->help_tabs[$id]);
//      return true;
//    }
//    return false;
//  }

  /**
   * Initialize Pages.
   */
//  public function init_help_tabs() {
//    if (!empty($this->help_tabs) && \is_array($this->help_tabs)) {
//      foreach ($this->help_tabs as $help_tab) {
//        $help_tab->init();
//      }
//    }
//  }

}
