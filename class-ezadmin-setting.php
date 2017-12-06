<?php
/**
 * Class EZAdmin_Setting
 * @see https://developer.wordpress.org/reference/functions/register_setting/
 */

class EZAdmin_Setting {
  /**
   * Page slug (i.e. $option_group).
   * @var string
   */
  protected $page;

  /**
   * Option slug (i.e. $option_name).
   * @var string
   */
  protected $id;

  /**
   * Data type associated with this setting. 'string', 'boolean', 'integer', or 'number'.
   * @var string
   */
  protected $type;

  /**
   * Description of data attached to this setting.
   * @var string
   */
  protected $description;

  /**
   * Function to sanitize the option's value.
   * @var callable
   */
  protected $sanitize_callback;

  /**
   * Whether data from this setting should be included in the REST API.
   * @var bool
   */
  protected $show_in_rest;

  /**
   * Settings fields attached to this setting.
   * @var array
   */
  protected $fields = [];

  /**
   * EZAdmin_Setting constructor.
   * @param string $page
   * @param string $id
   * @param array $args
   */
  public function __construct( $page, $id, $args = [] ) {
    $args = wp_parse_args( $args, [
      'type' => 'string',
      'description' => '',
      'sanitize_callback' => [ $this, 'sanitize' ],
      'show_in_rest' => false,
    ]);

    $this->page = $page;
    $this->id = $id;
    $this->type = $args['type'];
    $this->description = $args['description'];
    $this->sanitize_callback = $args['sanitize_callback'];
    $this->show_in_rest = $args['show_in_rest'];

    add_action( 'admin_init', [ $this, 'init' ] );
  }

  /**
   * Initialize the setting.
   */
  public function init() {
    $args = [
      'type' => $this->type,
      'description' => $this->description,
      'sanitize_callback' => $this->sanitize_callback,
      'show_in_rest' => $this->show_in_rest,
      'default' => $this->get_default(),
    ];
    register_setting( $this->page, $this->id, $args );
  }

  /**
   * Sanitize the setting.
   */
  public function sanitize($data) {
    $sanitized = [];
    foreach ( $this->fields as $i => $field ) {
      $sanitized[] = $field->sanitize( $data[$i] );
    }

    return $sanitized;
  }

  /**
   * Get the default of the setting.
   */
  public function get_default() {
    $default = [];
    foreach( $this->fields as $i => $field ) {
      $default[$field->get_id()] = $field->get_default();
    }

    return $default;
  }

  /**
   * Attach a field to the setting.
   */
  public function add_settings_field( $field ) {
    if ( $field instanceof EZAdmin_Settings_Field )
      $this->fields[] = $field;
  }
}
