<?php
namespace EZWPZ\Menu;

class Page {
  /**
   * Menu manager.
   * @var Manager
   */
  public $manager;

  /**
   * Slug name to refer to this menu by.
   * @var string
   */
  public $id;

  /**
   * Text to be displayed in the title tags.
   * @var string
   */
  public $page_title = '';

  /**
   * Text used in the menu.
   * @var string
   */
  public $menu_title = '';

  /**
   * Text used for the page's submenu title when applicable.
   * @var string
   */
  public $submenu_title = '';

  /**
   * Capability required for this menu to be displayed to the user.
   * @var string
   */
  public $capability = 'manage_options';

  /**
   * Menu icon. 1) base64-encoded SVG using a data URI, 2) dashicons helper class name, 3) 'none' to add icon via CSS.
   * @var string
   */
  public $icon_url = '';

  /**
   * Callback to render page.
   * @var mixed
   */
  public $callback;

  /**
   * Menu position.
   * @var int|null
   */
  public $position = null;

  /**
   * Parent menu item slug name.
   * @var string
   */
  public $parent_slug = '';

  /**
   * Page constructor.
   * @param Manager $manager
   * @param string $id
   * @param array $args
   */
  public function __construct($manager, $id, $args = []) {
    $keys = \array_keys(\get_object_vars($this));
    foreach ($keys as $key) {
      if (isset($args[$key])) {
        $this->$key = $args[$key];
      }
    }

    if (!isset($this->callback))
      $this->callback = [$this, 'render'];

    $this->manager = $manager;
    $this->id = $id;
  }

  /**
   * Add page to menu.
   */
  public function init() {
    if (!empty($this->parent_slug))
      \add_submenu_page($this->parent_slug, $this->page_title, $this->menu_title, $this->capability, $this->id, $this->callback);
    else
      \add_menu_page($this->page_title, $this->menu_title, $this->capability, $this->id, $this->callback, $this->icon_url, $this->position);

    if (!empty($this->submenu_title) && empty($this->parent_slug))
      \add_submenu_page($this->id, $this->page_title, $this->submenu_title, $this->capability, $this->id, $this->callback);
  }

  /**
   * Callback function to render the page.
   */
  public function render() {
    $page = $this->id;
    ?>
    <div class="wrap">
      <h1><?php echo \esc_html(\get_admin_page_title()); ?></h1>
      <form method="post" action="/wp-admin/options.php">
        <?php
        \settings_fields($page);
        \do_settings_sections($page);
        ?>
        <table class="form-table">
          <?php \do_settings_fields($page, 'default'); ?>
        </table>
        <?php
        \submit_button();
        ?>
      </form>
    </div>
    <?php
  }

}
