<?php
class EZAdmin_Settings_Section {
  /**
   * Slug to identify the section.
   * @var string
   */
  public $id;

  /**
   * Title of the section.
   * @var string
   */
  public $title = '';

  /**
   * The slug of the settings page on which to show the section.
   * @var EZAdmin_Settings_Page
   */
  public $page;

  /**
   * Content between the heading and the fields.
   * @var string
   */
  public $description;

  /**
   * Section controls.
   * @var array
   */
  public $controls = [];

  /**
   * EZAdmin_Settings_Section constructor.
   * @param string $id
   * @param EZAdmin_Settings_Page $page
   * @param array $args
   */
  public function __construct( $id, $page, array $args = [] ) {
    $keys = array_keys( get_object_vars( $this ) );
    foreach ( $keys as $key ) {
      if ( isset( $args[ $key ] ) ) {
        $this->$key = $args[ $key ];
      }
    }

    $this->id = $id;
    $this->page = $page;

    require_once( 'class-ezadmin-settings-control.php' );

    add_action( 'admin_init', [ $this, 'init' ] );
  }

  /**
   * Add the settings section.
   */
  public function init() {
    add_settings_section( $this->id, $this->title, [ $this, 'render' ], $this->page->menu_slug );
  }

  /**
   * Render the $description.
   */
  public function render() {
    if ( $this->description )
      echo $this->description;
  }

  /**
   * Add a settings field.
   * @param string $id
   * @param array $args
   * @return EZAdmin_Settings_Control
   */
  public function add_settings_field( $id, array $args = [] ) {
    if ( $id instanceof EZAdmin_Settings_Control ) {
      $control = $id;
    } else {
      $control = new EZAdmin_Settings_Control( $id, $this->page, $this, $args );
    }

    $this->controls[] = $control;
    return $control;
  }
}
