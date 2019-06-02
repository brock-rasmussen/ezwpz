<?php
namespace EZWPZ\Menu;

class HelpTab {
  /**
   * Menu manager.
   * @var Manager
   */
  public $manager;

  /**
   * Page slugs where help tab is displayed.
   * @var array
   */
  public $pages = [];

  /**
   * Tab slug.
   * @var string
   */
  public $id;

  /**
   * Tab title.
   * @var string
   */
  public $title = '';

  /**
   * Tab content in text or HTML.
   * @var string
   */
  public $content = '';

  /**
   * Callback to generate the tab content.
   * @var callable
   */
  public $callback;

  /**
   * Tab priority
   * @var int
   */
  public $priority = 10;

  /**
   * Help_Tab constructor.
   * @param Manager $manager
   * @param string $id
   * @param array $args
   */
  public function __construct($manager, $id, $args = []) {
    if (isset($args['pages']) && is_string($args['pages']))
      $args['pages'] = [$args['pages']];

    $keys = array_keys(get_object_vars($this));
    foreach ($keys as $key) {
      if (isset($args[$key])) {
        $this->$key = $args[$key];
      }
    }

    $this->manager = $manager;
    $this->id = $id;
  }

  /**
   * Add help tab to page.
   */
  public function init() {

  }
}
