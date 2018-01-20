<?php
class EZAdmin_Settings_Section {
  /**
   * The slug of the settings page on which to show the section.
   *
   * @var EZAdmin_Settings_Page
   */
  public $page;

  /**
   * Slug to identify the section.
   *
   * @var string
   */
  public $id;

  /**
   * Title of the section.
   *
   * @var string
   */
  public $title = '';

  /**
   * Content between the heading and the fields.
   *
   * @var string
   */
  public $description = '';

  /**
   * Section fields.
   * @var array
   */
  public $fields = [];

  /**
   * EZAdmin_Settings_Section constructor.
   *
   * @param EZAdmin_Settings_Page $page
   * @param string $id
   * @param array $args
   */
  public function __construct( $page, $id, array $args = [] ) {
    $keys = array_keys( get_object_vars( $this ) );
    foreach ( $keys as $key ) {
      if ( isset( $args[ $key ] ) ) {
        $this->$key = $args[ $key ];
      }
    }

    $this->page = $page;
    $this->id = $id;

    require_once('class-ezadmin-settings-field.php');

    add_action( 'admin_init', [ $this, 'init' ] );
  }

  /**
   * Add the settings section.
   */
  public function init() {
    add_settings_section( $this->id, $this->title, [ $this, 'the_description' ], $this->page->menu_slug );
  }

  /**
   * Render the section.
   */
  public function do_settings_section() {
    if ( $this->title ) {
      printf( '<h2>%s</h2>', $this->title );
    }

    $this->the_description();

    if ( count( $this->fields ) ) {
      echo '<table class="form-table">';
      foreach ( $this->fields as $field ) {
        $field->do_settings_field();
      }
      echo '</table>';
    }
  }

  /**
   * Get the description.
   *
   * @return bool|string
   */
  public function get_the_description() {
    if ( ! empty( $this->description ) ) {
      return apply_filters( 'the_content', $this->description );
    }
    return false;
  }

  /**
   * Output the description.
   */
  protected function the_description() {
    echo $this->get_the_description();
  }

  /**
   * Add a settings field.
   *
   * @param EZAdmin_Setting $setting
   * @param string $id
   * @param array $args
   *
   * @return EZAdmin_Settings_Field
   */
  public function add_settings_field( $setting, $id, array $args = [] ) {
    if ( $id instanceof EZAdmin_Settings_Field ) {
      $field = $id;
    } else {
      $field = new EZAdmin_Settings_Field( $this->page, $this, $setting, $id, $args );
    }

    $this->fields[] = $field;
    return $field;
  }
}
