<?php
if ( ! class_exists( 'EZAdmin_Menu_Page' ) ) {
  require_once 'class-ezadmin-menu-page.php';
}

class EZAdmin_Settings_Page extends EZAdmin_Menu_Page {
  /**
   * Registered instances of EZAdmin_Settings_Section.
   *
   * @var array
   */
  public $sections = [];

  /**
   * Constructor.
   *
   * @param string $menu_slug
   * @param array $args {
   *  Optional. Arguments to override class property defaults.
   *
   * @type
   */
  public function __construct( $menu_slug, array $args = [] ) {
    parent::__construct( $menu_slug, $args );
    require_once(__DIR__ . '/class-ezadmin-settings-section.php');
  }

  /**
   * Render the page.
   */
  public function render() {
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <?php $this->the_description(); ?>
      <form method="post" action="options.php">
        <?php
        settings_fields( $this->menu_slug );
        foreach ( $this->sections as $section ) {
          $section->do_settings_section();
        }
        submit_button();
        ?>
      </form>
    </div>
    <?php
  }

  /**
   * Add settings section to the page.
   *
   * @param string $id
   * @param array $args
   *
   * @return EZAdmin_Settings_Section
   */
  public function add_settings_section( $id, $args = [] ) {
    if ( $id instanceof EZAdmin_Settings_Section ) {
      $section = $id;
    } else {
      $section = new EZAdmin_Settings_Section( $this, $id, $args );
    }
    $this->sections[] = $section;
    return $section;
  }
}
