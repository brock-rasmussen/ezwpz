<?php
namespace EZWPZ\Settings;

require_once 'utilities.php';
require_once 'class-setting.php';
require_once 'class-section.php';
require_once 'class-field.php';
require_once 'class-control.php';
use EZWPZ\Core\Singleton;

class Manager {
  use Singleton;

  /**
   * Instances of Setting
   * @var array
   */
  protected $settings = [];

  /**
   * Instances of Section
   * @var array
   */
  protected $sections = [];

  /**
   * Instances of Field
   * @var array
   */
  protected $fields = [];

  /**
   * Instances of Control
   * @var array
   */
  protected $controls = [];

  /**
   * Manager constructor.
   */
  protected function __construct() {
    \add_action('init', [$this, 'init']);
    \add_action('admin_init', [$this, 'init_settings']);
    \add_action('admin_init', [$this, 'init_settings_sections']);
    \add_action('admin_init', [$this, 'init_settings_fields']);
    \add_action('admin_init', [$this, 'init_settings_controls']);
  }

  /**
   * Add action that plugins and themes can tie into to easily access the class.
   */
  public function init() {
    \do_action('ezwpz_settings', self::get_instance());
  }

  /**
   * Add a setting.
   * @param string $id
   * @param array $args
   */
  public function add_setting($id, $args = []) {
    if ($id instanceof Setting)
      $setting = $id;
    else
      $setting = new Setting($this, $id, $args);

    $this->settings[$id] = $setting;
  }

  /**
   * Get a setting.
   * @param string $id
   * @return bool|mixed
   */
  public function get_setting($id) {
    if (isset($this->settings[$id]))
      return $this->settings[$id];
    return false;
  }

  /**
   * Remove a setting.
   * @param string $id
   * @return bool
   */
  public function remove_setting($id) {
    if (isset($this->settings[$id])) {
      unset($this->settings[$id]);
      return true;
    }
    return false;
  }

  /**
   * Initialize settings.
   */
  public function init_settings() {
    foreach ($this->settings as $setting) {
      $setting->init();
    }
  }

  /**
   * Add a section.
   * @param string $id
   * @param array $args
   */
  public function add_section($id, $args = []) {
    if ($id instanceof Section)
      $section = $id;
    else
      $section = new Section($this, $id, $args);

    $this->sections[$section->page][$id] = $section;
  }

  /**
   * Get a section.
   * @param string $page
   * @param string $id
   * @return bool|mixed
   */
  public function get_section($page, $id) {
    if (isset($this->sections[$page][$id]))
      return $this->sections[$page][$id];
    return false;
  }

  /**
   * Remove a section.
   * @param string $page
   * @param string $id
   * @return bool
   */
  public function remove_section($page, $id) {
    if (isset($this->sections[$page][$id])) {
      unset($this->sections[$page][$id]);
      return true;
    }
    return false;
  }

  /**
   * Initialize Sections.
   */
  public function init_settings_sections() {
    foreach ($this->sections as $page => $sections) {
      \uasort($sections, [$this, 'sort_priority']);
      foreach ($sections as $section) {
        $section->init();
      }
    }
  }

  /**
   * Add a field.
   * @param string $id
   * @param array $args
   */
  public function add_field($id, $args = []) {
    if ($id instanceof Field)
      $field = $id;
    else
      $field = new Field($this, $id, $args);

    $this->fields[$field->page][$field->section][$id] = $field;
  }

  /**
   * Get a field.
   * @param string $page
   * @param string $section
   * @param string $id
   * @return bool|mixed
   */
  public function get_field($page, $section, $id) {
    if (isset($this->fields[$page][$section][$id]))
      return $this->fields[$page][$section][$id];
    return false;
  }

  /**
   * Remove a field.
   * @param string $page
   * @param string $section
   * @param string $id
   * @return bool
   */
  public function remove_field($page, $section, $id) {
    if (isset($this->fields[$page][$section][$id])) {
      unset($this->fields[$page][$section][$id]);
      return true;
    }
    return false;
  }

  /**
   * Initialize Fields.
   */
  public function init_settings_fields() {
    foreach ($this->fields as $page => $sections) {
      foreach ($sections as $section => $fields) {
        \uasort($fields, [$this, 'sort_priority']);
        foreach ($fields as $field) {
          $field->init();
        }
      }
    }
  }

  /**
   * Add a control.
   * @param string $id
   * @param array $args
   */
  public function add_control($id, $args = []) {
    if ($id instanceof Control)
      $control = $id;
    else
      $control = new Control($this, $id, $args);

    $this->controls[$control->page][$control->section][$control->field][$id] = $control;
  }

  /**
   * Get a control.
   * @param string $page
   * @param string $section
   * @param string $field
   * @param string $id
   * @return bool|mixed
   */
  public function get_control($page, $section, $field, $id) {
    if (isset($this->controls[$page][$section][$field][$id]))
      return $this->controls[$page][$section][$field][$id];
    return false;
  }

  /**
   * Remove a control.
   * @param string $page
   * @param string $section
   * @param string $field
   * @param string $id
   * @return bool
   */
  public function remove_control($page, $section, $field, $id) {
    if (isset($this->controls[$page][$section][$field][$id])) {
      unset($this->controls[$page][$section][$field][$id]);
      return true;
    }
    return false;
  }

  /**
   * Initialize Controls.
   */
  public function init_settings_controls() {
    foreach ($this->controls as $page => $sections) {
      foreach ($sections as $section => $fields) {
        foreach ($fields as $field => $controls) {
          \uasort($fields, [$this, 'sort_priority']);
          foreach ($controls as $control) {
            $control->init();
          }
        }
      }
    }
  }

  /**
   * Add sections, fields, controls, and settings through a config object.
   * @param string $page
   * @param string $setting
   * @param array $config
   */
  public function add_by_config($page, $setting, $config) {
    if (is_string($config) && file_exists($config)) {
      $config = json_decode(file_get_contents($config), true);
    }

    if (!is_array($config) || empty($config))
      return;

    if (isset($setting) && !$this->get_setting($setting))
      $this->add_setting($setting, ['page' => $page]);

    foreach ($config as $section_id => $section_args) {
      if (!is_array($section_args))
        continue;

      $section_args['page'] = $page;
      $this->add_section($section_id, $section_args);

      if (isset($section_args['fields']) && \is_array($section_args['fields'])) {
        foreach ($section_args['fields'] as $field_id => $field_args) {
          if (!is_array($field_args))
            continue;

          $field_args['page'] = $page;
          $field_args['section'] = $section_id;
          $this->add_field($field_id, $field_args);

          if (isset($field_args['controls']) && \is_array($field_args['controls'])) {
            foreach ($field_args['controls'] as $control_id => $control_args) {
              if (!is_array($field_args))
                continue;

              $control_args['page'] = $page;
              $control_args['section'] = $section_id;
              $control_args['field'] = $field_id;
              if (isset($setting))
                $control_args['setting'] = $setting;

              $this->add_control($control_id, $control_args);
            }
          }
        }
      }
    }
  }

  /**
   * Sort items by their priority. If same priority, sort by the instantiation order.
   * @param $prev
   * @param $curr
   * @return int
   */
  protected function sort_priority($prev, $curr) {
    $sort = \strnatcmp($prev->priority, $curr->priority);
    if (!$sort) {
      $sort = \strnatcmp($prev->instance_number, $curr->instance_number);
    }
    return $sort;
  }

}
