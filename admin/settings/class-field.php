<?php
namespace EZWPZ\Admin\Settings;

require_once EZWPZ_PLUGIN_DIR . '/core/trait-priority.php';

use EZWPZ\Core\Priority;

class Field {
  use Priority;

  /**
   * Settings Manager.
   * @var Manager
   */
  public $manager;

  /**
   * Page slug where field is displayed.
   * @var string
   */
  public $page = 'general';

  /**
   * Section slug where field is displayed.
   * @var string
   */
  public $section = 'default';

  /**
   * Field slug.
   * @var string
   */
  public $id;

  /**
   * Field title.
   * @var string
   */
  public $title;

  /**
   * Field callback. Renders the field's controls.
   * @var callable
   */
  public $callback;

  /**
   * Adds a <label> wrapper to the title with the "for" attribute equal to the provided value.
   * @var string
   */
  public $label_for = '';

  /**
   * CSS class to be added to the <tr> element when the field is output.
   * @var string
   */
  public $class = '';

  /**
   * Field constructor.
   * @param Manager $manager
   * @param string $id
   * @param array $args
   */
  public function __construct($manager, $id, $args = []) {
    $keys = \array_keys(\get_object_vars($this));
    foreach ($keys as $key) {
      if (isset($args[$key])) {
        $this->$key = $args[$key];
      }
    }

    if (!isset($this->callback))
      $this->callback = [$this, 'do_settings_controls'];

    self::$instance_count += 1;
    $this->instance_number = self::$instance_count;

    $this->manager = $manager;
    $this->id = $id;
  }

  /**
   * Add field to section.
   */
  public function init() {
    \add_settings_field($this->id, $this->title, $this->callback, $this->page, $this->section, [
      'label_for' => $this->label_for,
      'class' => $this->class,
    ]);
  }

  /**
   * Render the controls.
   * @param array $field
   */
  public function do_settings_controls($field) {
    if (isset($field['ezwpz']['controls']) && \is_array($field['ezwpz']['controls'])) {
      $is_alone_in_field = \count($field['ezwpz']['controls']) === 1;

      \ob_start();
      foreach ($field['ezwpz']['controls'] as $control) {
        if (\is_callable($control)) {
          \call_user_func($control, $is_alone_in_field);
        }
      }
      $controls = \ob_get_clean();

      if (!$is_alone_in_field) {
        $controls = \sprintf('<fieldset><legend class="screen-reader-text">%s</legend>%s</fieldset>', $this->title, $controls);
      }

      echo $controls;
    }
  }

}
