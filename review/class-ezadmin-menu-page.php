<?php
class EZAdmin_Menu_Page1 {
  /**
   * Text to be displayed in the page title tags.
   *
   * @var string
   */
  public $page_title = '';

  /**
   * Text to be used for the menu.
   *
   * @var string
   */
  public $menu_title = '';

  /**
   * Capability required for this menu to be displayed to the user.
   *
   * @var string
   */
  public $capability = 'manage_options';

  /**
   * The slug name to refer to this menu by.
   *
   * @var string
   */
  public $menu_slug;

  /**
   * Page description.
   *
   * @var string
   */
  public $description = '';

  /**
   * The icon to be used for this menu.
   *
   * @var string
   */
  public $icon_url = '';

  /**
   * The position in the menu this should appear.
   *
   * @var int
   */
  public $position = null;

  /**
   * Registered instances of EZAdmin_Menu_Page with $this->menu_slug as the parent_slug.
   *
   * @var array
   */
  public $submenu_pages = [];

  /**
   * The parent menu item.
   *
   * @var EZAdmin_Settings_Page|string
   */
  public $parent_slug;

  /**
   * The queue of scripts for the page.
   *
   * @var array
   */
  public $scripts = [];

  /**
   * The queue of styles for the page.
   *
   * @var array
   */
  public $styles = [];

  /**
   * EZAdmin_Menu_Page constructor.
   *
   * @param string $menu_slug
   * @param array $args
   */
  public function __construct( $menu_slug, array $args = [] ) {
    $keys = array_keys( get_object_vars( $this ) );
    foreach ( $keys as $key ) {
      if ( isset( $args[ $key ] ) ) {
        $this->$key = $args[ $key ];
      }
    }

    $this->menu_slug = $menu_slug;

    add_action( 'admin_menu', [ $this, 'init' ] );
  }

  /**
   * Add the menu page.
   */
  public function init() {
    $parent_slug = $this->parent_slug;
    $page_title = $this->page_title;
    $menu_title = $this->menu_title;
    $capability = $this->capability;
    $menu_slug = $this->menu_slug;

    if ( $parent_slug ) {
      $hook_suffix = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, [ $this, 'render' ] );
    } else {
      $hook_suffix = add_menu_page( $page_title, $menu_title, $capability, $menu_slug, [ $this, 'render' ], $this->icon_url );
    }

    add_action( "admin_print_scripts-{$hook_suffix}", [ $this, 'print_scripts' ] );
    add_action( "admin_print_styles-{$hook_suffix}", [ $this, 'print_styles' ] );
  }

  /**
   * Add a submenu page.
   *
   * @param string $menu_slug
   * @param array $args
   *
   * @return EZAdmin_Menu_Page|false
   */
  public function add_submenu_page( $menu_slug, $args = [] ) {
    // submenu pages can't have submenu pages.
    if ( $this->parent_slug ) return false;

    // if constructor provided, use that, otherwise instantiate a new EZAdmin_Menu_Page
    if ( $menu_slug instanceof EZAdmin_Menu_Page ) {
      $submenu_page = $menu_slug;
    } else {
      $args['parent_slug'] = $this->menu_slug;
      $submenu_page = new EZAdmin_Menu_Page( $menu_slug, $args );
    }

    $this->submenu_pages[] = $submenu_page;
    return $submenu_page;
  }

  /**
   * Enqueue scripts for the page.
   */
  final public function print_scripts() {
    foreach( $this->scripts as $script) {
      wp_enqueue_script( $script[0], $script[1], $script[2], $script[3], $script[4] );
    }
  }

  /**
   * Enqueue styles for the page.
   */
  final public function print_styles() {
    foreach ( $this->styles as $style ) {
      wp_enqueue_style( $style[0], $style[1], $style[2], $style[3], $style[4] );
    }
  }

  /**
   * Render the page.
   */
  public function render() {}

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
  public function the_description() {
    echo $this->get_the_description();
  }

  /**
   * Add a script to the queue for the page.
   *
   * @param string $handle
   * @param string $src
   * @param array $deps
   * @param string|bool|null $ver
   * @param bool $in_footer
   */
  final public function enqueue_script( $handle, $src = '', $deps = [], $ver = false, $in_footer = false ) {
    $this->scripts[] = [ $handle, $src, $deps, $ver, $in_footer ];
  }

  /**
   * Add a stylesheet to the queue for the page.
   *
   * @param string $handle
   * @param string $src
   * @param array $deps
   * @param string|bool|null $ver
   * @param string $media
   */
  final public function enqueue_style( $handle, $src = '', $deps = [], $ver = false, $media = 'all' ) {
    $this->styles[] = [ $handle, $src, $deps, $ver, $media ];
  }
}
