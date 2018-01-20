<?php
/**
 * Class EZAdmin_Setting
 * @see https://developer.wordpress.org/reference/functions/register_setting/
 */

class EZAdmin_Setting {
  /**
   * Page slug (i.e. $option_group).
   *
   * @var EZAdmin_Settings_Page
   */
  protected $page;

  /**
   * Option slug (i.e. $option_name).
   *
   * @var string
   */
  protected $id;

  /**
   * Data type associated with this setting. 'string', 'boolean', 'integer', or 'number'.
   *
   * @var string
   */
  protected $type = 'string';

  /**
   * Description of data attached to this setting.
   *
   * @var string
   */
  protected $description = '';

  /**
   * Whether data from this setting should be included in the REST API.
   *
   * @var bool
   */
  protected $show_in_rest = false;

  /**
   * Settings fields attached to this setting.
   *
   * @var array
   */
  protected $fields = [];

  /**
   * EZAdmin_Setting constructor.
   *
   * @param EZAdmin_Settings_Page $page
   * @param string $id
   * @param array $args
   */
  public function __construct( $page, $id, $args = [] ) {
    $keys = array_keys( get_object_vars( $this ) );
    foreach( $keys as $key ) {
      if ( isset( $args[ $key ] ) ) {
        $this->$key = $args[ $key ];
      }
    }

    $this->page = $page;
    $this->id = $id;

    add_action( 'admin_init', [ $this, 'init' ] );
  }

  /**
   * Initialize the setting.
   */
  public function init() {
    $args = [
      'type' => $this->type,
      'description' => $this->description,
      'sanitize_callback' => [ $this, 'sanitize' ],
      'show_in_rest' => $this->show_in_rest,
      'default' => $this->get_default(),
    ];
    register_setting( $this->page->menu_slug, $this->id, $args );
  }

  /**
   * Get the default value.
   *
   * @return array
   */
  public function get_default() {
    $default = [];
    foreach ( $this->fields as $field ) {
      $default[$field->id] = $field->default;
    }

    return $default;
  }

  /**
   * Sanitize the setting.
   *
   * @param $data
   *
   * @return array
   */
  public function sanitize( $data ) {
    $sanitized = [];
    foreach ( $this->fields as $field ) {
      $sanitized[$field->id] = $field->sanitize( $data[$i] );
    }

    return $sanitized;
  }

  /**
   * Attach a field to the setting.
   *
   * @param EZAdmin_Settings_Control $field
   */
  public function add_settings_field( $field ) {
    $this->fields[] = $field;
  }
}
