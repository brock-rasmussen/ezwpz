<?php
namespace EZWPZ\Settings;
use EZWPZ\Core\Priority;

class Section {
  use Priority;

  /**
   * Settings Manager.
   * @var Manager
   */
  public $manager;

  /**
   * Page slug where section is displayed.
   * @var string
   */
  public $page = 'general';

  /**
   * Section slug.
   * @var string
   */
  public $id;

  /**
   * Section title.
   * @var string
   */
  public $title = '';

  /**
   * Section callback. Renders content between the title and the fields.
   * @var callable
   */
  public $callback;

  /**
   * Section description.
   * @var string
   */
  public $description;

  /**
   * Section constructor.
   * @param Manager $manager
   * @param string $id
   * @param array $args
   */
  public function __construct($manager, $id, $args = []) {
    $keys = array_keys(get_object_vars($this));
    foreach ($keys as $key) {
      if (isset($args[$key])) {
        $this->$key = $args[$key];
      }
    }

    if (!isset($this->callback) && !empty($this->description))
      $this->callback = [$this, 'the_description'];

    self::$instance_count += 1;
    $this->instance_number = self::$instance_count;

    $this->manager = $manager;
    $this->id = $id;
  }

  /**
   * Add section to page.
   */
  public function init() {
    global $wp_settings_sections;
    add_settings_section($this->id, $this->title, $this->callback, $this->page);

    if (!empty($this->description))
      $wp_settings_sections[$this->page][$this->id]['ezwpz']['description'] = $this->description;
  }

  /**
   * Render the description.
   * @param array $section
   */
  public function the_description($section) {
    if (!empty($section['ezwpz']['description']))
      echo apply_filters('the_content', $section['ezwpz']['description']);
  }

}
