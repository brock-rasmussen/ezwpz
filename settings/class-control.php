<?php
namespace EZWPZ\Settings;
use EZWPZ\Core\Priority;

class Control {
  use Priority;

  /**
   * Settings Manager.
   * @var Manager
   */
  public $manager;

  /**
   * Setting attached to control.
   * @var string
   */
  public $setting;

  /**
   * Page slug where control is displayed.
   * @var string
   */
  public $page = 'general';

  /**
   * Section slug where control is displayed.
   * @var string
   */
  public $section = 'default';

  /**
   * Field slug where control is displayed.
   * @var string
   */
  public $field;

  /**
   * Control slug.
   * @var string
   */
  public $id;

  /**
   * Control type.
   * @var string
   */
  public $type = 'text';

  /**
   * Control label.
   * @var string
   */
  public $label = '';

  /**
   * Control description.
   * @var string
   */
  public $description = '';

  /**
   * Default value.
   * @var mixed
   */
  public $default = '';

  /**
   * Array of choices. $value => $label.
   * For 'radio', 'select', 'email', 'number', 'tel', 'text', 'or 'url' type controls.
   * @var array
   */
  public $choices = [];

  /**
   * Additional input attributes. $attribute => $value.
   * Not used for all control types.
   * @var array
   */
  public $input_attrs = [];

  /**
   * Control callback. Renders the control.
   * @var callable
   */
  public $callback;

  /**
   * Callback to sanitize data.
   * @var callable
   */
  public $sanitize_callback;

  /**
   * Control constructor.
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

    if (!isset($this->callback))
      $this->callback = [$this, 'the_control'];

    if (!isset($this->sanitize_callback)) {
      $wp_sanitize_functions = [
        'email' => 'sanitize_email',
        'richtext' => 'wp_filter_post_kses',
        'number' => 'intval',
        'textarea' => 'sanitize_textarea_field',
        'url' => 'esc_url_raw',
      ];

      if (isset($wp_sanitize_functions[$this->type]))
        $this->sanitize_callback = $wp_sanitize_functions[$this->type];
      else
        $this->sanitize_callback = 'sanitize_text_field';
    }

    if ($this->type === 'textarea') {
      $this->input_attrs = wp_parse_args($this->input_attrs, [
        'class' => 'large-text',
        'rows' => 10,
        'cols' => 50,
      ]);
    }

    if (in_array($this->type, ['email', 'text', 'url'])) {
      $this->input_attrs = wp_parse_args($this->input_attrs, [
        'class' => 'regular-text',
      ]);
    }

    if (!empty($this->description))
      $this->input_attrs['aria-describedby'] = esc_attr($this->id) . '-description';

    unset($this->input_attrs['id'], $this->input_attrs['name'], $this->input_attrs['value']);

    self::$instance_count += 1;
    $this->instance_number = self::$instance_count;

    $this->manager = $manager;
    $this->id = $id;
  }

  public function init() {
    global $wp_registered_settings, $ezwpz_settings_controls;
    if (!isset($this->field))
      return;

    if (isset($this->setting))
      $wp_registered_settings[$this->setting]['ezwpz']['controls'][$this->id] = $this;

    $args = [
      'setting' => $this->setting,
      'id' => $this->id,
      'type' => $this->type,
      'label' => $this->label,
      'description' => $this->description,
      'default' => $this->default,
      'choices' => $this->choices,
      'input_attrs' => $this->input_attrs,
    ];
    $ezwpz_settings_controls[$this->page][$this->section][$this->field][$this->id] = ['id' => $this->id, 'callback' => $this->callback, 'args' => $args];

    if (isset($this->setting) && isset($this->sanitize_callback))
      add_filter("sanitize_option_{$this->setting}", [$this, 'sanitize']);

    if (isset($this->setting) && !empty($this->default))
      add_filter("default_option_{$this->setting}", [$this, 'set_default']);
  }

  public function sanitize($data) {
    if ($this->is_alone_in_setting())
      $data = call_user_func($this->sanitize_callback, $data);
    else
      $data[$this->id] = call_user_func($this->sanitize_callback, $data[$this->id]);

    return $data;
  }

  public function set_default($data) {
    if ($this->is_alone_in_setting())
      $data = $this->default;
    else
      $data[$this->id] = $this->default;

    return $data;
  }

  public function the_control($args, $is_alone_in_field) {
    $is_alone_in_setting = $this->is_alone_in_setting();

    $value = $this->value();
    $this->input_attrs = array_merge($this->input_attrs, [
      'id' => esc_attr($this->id),
      'type' => esc_attr($this->type),
      'value' => esc_attr($value),
    ]);

    if (isset($this->setting))
      $this->input_attrs['name'] = $is_alone_in_setting ? esc_attr($this->setting) : esc_attr($this->setting) . '[' . esc_attr($this->id) . ']';
    else
      $this->input_attrs['name'] = esc_attr($this->id);

    $input_attrs = $this->input_attrs;

    $description = !empty($this->description) ? sprintf('<p id="%s-description" class="description">%s</p>', $input_attrs['id'], $this->description) : '';

    switch ($this->type) {
//      case 'checkbox':
//        printf('<p><label for="%s"><input%s%s> %s</label></p>', $input_attrs['id'], $this->input_attrs(), checked($input_attrs['value'], true, false), esc_html($this->label));
//        echo $description;
//        break;

      case 'dropdown-categories':
        $dropdown = wp_dropdown_categories([
          'id' => $input_attrs['id'],
          'name' => $input_attrs['name'],
          'class' => isset($input_attrs['class']) ? $input_attrs['class'] : 'postform',
          'show_option_none' => __('&mdash; Select &mdash;'),
          'option_none_value' => 0,
          'selected' => $input_attrs['value'],
          'echo' => 0,
        ]);
        if (!empty($description))
          $dropdown = str_replace('<select', '<select aria-describedby="' . $input_attrs['id'] . '-description"', $dropdown);
        echo '<p>' . $dropdown . '</p>';
        echo $description;
        break;

      case 'dropdown-pages':
        $dropdown = wp_dropdown_pages([
          'id' => $input_attrs['id'],
          'name' => $input_attrs['name'],
          'class' => isset($input_attrs['class']) ? $input_attrs['class'] : 'postform',
          'show_option_none' => __('&mdash; Select &mdash;'),
          'option_none_value' => 0,
          'selected' => $input_attrs['value'],
          'echo' => 0,
        ]);
        if (!empty($description))
          $dropdown = str_replace('<select', '<select aria-describedby="' . $input_attrs['id'] . '-description"', $dropdown);
        echo '<p>' . $dropdown . '</p>';
        echo $description;
        break;

//      case 'radio':
//        if (empty($this->choices))
//          return;
//        break;

      case 'richtext':
        echo '<p>';
        wp_editor($value, $input_attrs['id'], ['textarea_name' => $input_attrs['name']]);
        echo '</p>';
        echo $description;
        break;

//      case 'select':
//        if (empty($this->choices))
//          return;
//
//        echo '<p>';
//        if (!$is_alone_in_field && !empty($this->label))
//          printf('<label for="%s">%s</label>', $input_attrs['id'], esc_html($this->label));
//        echo '</p>';
//        break;

      case 'textarea':
        unset($input_attrs['value'], $this->input_attrs['value']);

        echo '<p>';
        if (!$is_alone_in_field && !empty($this->label))
          printf('<label for="%s">%s</label>', $input_attrs['id'], esc_html($this->label));
        printf('<textarea%s>%s</textarea>', $this->input_attrs(), esc_textarea($value));
        echo '</p>';
        echo $description;
        break;

      default:
        echo '<p>';
        if (!$is_alone_in_field && !empty($this->label))
          printf('<label for="%s">%s</label>', $input_attrs['id'], esc_html($this->label));
        printf('<input%s>', $this->input_attrs());
        echo '</p>';
        echo $description;
        break;
    }
  }

  public function input_attrs() {
    $attrs = '';
    foreach ($this->input_attrs as $name => $value)
      $attrs .= " {$name}='{$value}'";

    return $attrs;
  }

  public function value() {
    if (!isset($this->setting))
      return $this->default;

    $value = get_option($this->setting, null);

    if (isset($value) && !$this->is_alone_in_setting() && isset($value[$this->id]))
      $value = $value[$this->id];

    if (!isset($value))
      $value = $this->default;

    return $value;
  }

  public function is_alone_in_setting() {
    global $wp_registered_settings;
    return count($wp_registered_settings[$this->setting]['ezwpz']['controls']) < 2;
  }

}
