<?php
class EZAdmin_Settings_Manager {
  /**
   * Registered instances of EZAdmin_Settings_Page.
   * @var array
   */
  protected $pages = [];

  /**
   * Registered instances of EZAdmin_Settings_Section.
   * @var array
   */
  protected $sections = [];

  /**
   * Registered instances of EZAdmin_Setting.
   * @var array
   */
  protected $settings = [];

  /**
   * Registered instances of EZAdmin_Settings_Field.
   * @var array
   */
  protected $controls = [];

  /**
   * Page types.
   * @var array
   */
  protected $registered_page_types = [];

  /**
   * Section types.
   * @var array
   */
  protected $registered_section_types = [];

  /**
   * Field types.
   * @var array
   */
  protected $registered_field_types = [];

  /**
   * Constructor.
   */
  public function __construct( $args = [] ) {
    require_once(__DIR__ . '/class-ezadmin-settings-page.php');
    require_once(__DIR__ . '/class-ezadmin-settings-section.php');
    require_once(__DIR__ . '/class-ezadmin-settings-setting.php');
    require_once(__DIR__ . '/class-ezadmin-settings-control.php');

    require_once(__DIR__ . '/settings/class-ezadmin-settings-color-control.php');
//    require_once( __DIR__ . '/settings/class-ezadmin-settings-media-control.php' );
//    require_once( __DIR__ . '/settings/class-ezadmin-settings-upload-control.php' );
//    require_once( __DIR__ . '/settings/class-ezadmin-settings-image-control.php' );
//    require_once( __DIR__ . '/settings/class-ezadmin-settings-background-image-control.php' );
//    require_once( __DIR__ . '/settings/class-ezadmin-settings-background-position-control.php' );
//    require_once( __DIR__ . '/settings/class-ezadmin-settings-cropped-image-control.php' );
    require_once( __DIR__ . '/settings/class-ezadmin-site-icon-control.php' );
    require_once( __DIR__ . '')

    do_action( 'ezadmin', $this );
  }

  /**
   * Add menu page.
   * @param string $id
   * @param array  $args
   * @return EZAdmin_Menu_Page|EZAdmin_Settings_Page
   */
  public static function add_menu_page( $id, $args = [] ) {
    if ( $id instanceof EZAdmin_Menu_Page ) {
      $page = $id;
    } else {
      $page = new EZAdmin_Settings_Page( $id, $args );
    }

    return $page;
  }


  /**
   * Register a setting.
   * @param string $option_group
   * @param string $option_name
   * @param array $args
   * @return EZAdmin_Setting
   */
  public static function register_setting( $option_group, $option_name, $args = [] ) {
    if ( $option_group instanceof EZAdmin_Setting ) {
      $setting = $option_group;
    } else {
      $setting = new EZAdmin_Setting( $option_group, $option_name, $args );
    }

    return $setting;
  }
}

add_action( 'init', function() { new EZAdmin(); } );
