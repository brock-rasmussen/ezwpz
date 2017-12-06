<?php
class EZAdmin_Settings_Page extends EZAdmin_Menu_Page {
  /**
   * Page sections.
   * @var array
   */
  public $sections = [];

  /**
   * EZAdmin_Settings_Page constructor.
   * @param string $menu_slug
   * @param array $args
   */
  public function __construct( $menu_slug, array $args = [] ) {
    parent::__construct( $menu_slug, $args );
    require_once(__DIR__ . '/class-ezadmin-settings-section.php');
  }

  /**
   * Render the page.
   */
  public function render() {
    $option_group = $page = $this->menu_slug;
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form method="post" action="options.php">
        <?php
        settings_fields( $option_group );
        do_settings_sections( $page );
        submit_button();
        ?>
      </form>
    </div>
    <?php
  }

  /**
   * Add settings section to the page.
   * @param string $id
   * @param array $args
   * @return EZAdmin_Settings_Section
   */
  public function add_settings_section( $id, $args = [] ) {
    if ( $id instanceof EZAdmin_Settings_Section ) {
      $section = $id;
    } else {
      $section = new EZAdmin_Settings_Section( $id, $this, $args );
    }

    $this->sections[] = $section;
    return $section;
  }
}
