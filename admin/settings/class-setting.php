<?php
namespace EZWPZ\Admin\Settings;

class Setting {
  /**
   * Option group.
   * @var string
   */
  public $page = 'general';

  /**
   * Option name.
   * @var string
   */
  public $id;

  /**
   * Data type associated with setting. 'string', 'boolean', 'integer', or 'number'.
   * @var string
   */
  public $type = 'string';

  /**
   * Setting description.
   * @var string
   */
  public $description = '';

  /**
   * Callback to sanitize the value.
   * @var callable
   */
  public $sanitize_callback;

  /**
   * Whether to include this setting in the REST API.
   * @var bool
   */
  public $show_in_rest = false;

  /**
   * Default value.
   * @var mixed
   */
  public $default;

  /**
   * Setting constructor.
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

    $this->manager = $manager;
    $this->id = $id;
  }

  /**
   * Register setting.
   */
  public function init() {
    register_setting($this->page, $this->id, [
      'type' => $this->type,
      'description' => $this->description,
      'sanitize_callback' => $this->sanitize_callback,
      'show_in_rest' => $this->show_in_rest,
      'default' => $this->default,
    ]);
  }
}
