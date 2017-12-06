<?php
class EZAdmin_Settings_Control {
  /**
   * Slug to identify the field.
   * @var string
   */
  public $id;

  /**
   * Title of the field.
   * @var string
   */
  public $title = '';

  /**
   * Page on which to show the section.
   * @var EZAdmin_Settings_Page
   */
  public $page;

  /**
   * Section on which to show the control.
   * @var EZAdmin_Settings_Section
   */
  public $section;

  /**
   * The `for` attribute of the <label> element.
   * @var string
   */
  public $label_for;

  /**
   * The EZAdmin_Setting the control is attached to.
   * @var EZAdmin_Setting
   */
  public $setting;

  /**
   * List of choices for 'radio' or 'select' type controls, where values are the keys, and labels are the values.
   * @var array
   */
  public $choices = [];

  /**
   * List of custom input attributes for control output, where attribute names are the keys and values are the values.
   * @var array
   */
  public $input_attrs = [];

  /**
   * Show UI for adding new content, currently only used for the dropdown-pages control.
   * @var bool
   */
  public $allow_addition = false;

  /**
   * Control's type.
   * @var string
   */
  public $type = 'text';

  /**
   * EZAdmin_Settings_Control constructor.
   * @param string $id
   * @param EZAdmin_Settings_Page $page
   * @param EZAdmin_Settings_Section $section
   * @param array $args
   */
  public function __construct( $id, $page, $section, array $args = [] ) {
    $keys = array_keys( get_object_vars( $this ) );
    foreach ( $keys as $key ) {
      if ( isset( $args[ $key ] ) ) {
        $this->$key = $args[ $key ];
      }
    }

    $this->id = $id;
    $this->page = $page;
    $this->section = $section;

    add_action( 'admin_init', [ $this, 'init' ] );
  }

  /**
   * Add the settings control.
   */
  public function init() {
    add_settings_field( $this->id, $this->title, [ $this, 'render' ], $this->page->menu_slug, $this->section->id );
  }

  public function render() {
    $input_id = 'settings-control-' . $this->id;
    $description_id = 'settings-description-' . $this->id;
    $describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
    switch ( $this->type ) {
      case 'checkbox':
    }
  }

  /**
   * Render the custom attributes for the control's input element.
   */
  public function input_attrs() {
    foreach( $this->input_attrs as $attr => $value ) {
      echo $attr . '="' . esc_attr( $value ) . '" ';
    }
  }

  /**
   * Enqueue control related scripts.
   * @param string $handle
   * @param string $src
   * @param array $deps
   * @param string|bool|null $ver
   * @param bool $in_footer
   */
  final public function enqueue_scripts( $handle, $src = '', $deps = [], $ver = false, $in_footer = false ) {
    $this->page->enqueue_script( $handle, $src, $deps, $ver, $in_footer );
  }

  /**
   * Enqueue control related styles.
   * @param string $handle
   * @param string $src
   * @param array $deps
   * @param string|bool|null $ver
   * @param string $media
   */
  final public function enqueue_styles( $handle, $src = '', $deps = [], $ver = false, $media = 'all' ) {
    $this->page->enqueue_style( $handle, $src, $deps, $ver, $media );
  }
}
