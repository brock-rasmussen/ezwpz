<?php
namespace EZWPZ\Theme;
use EZWPZ\Core\Singleton;

class Layout {
  use Singleton;

  /**
   * Template file to be wrapped.
   * @var string
   */
  protected $template;

  /**
   * Add a template layout.
   */
  public function init() {
    \add_action('template_include', [$this, 'layout']);
  }

  /**
   *
   * @param string $template
   * @return string
   */
  public function layout($template) {
    $this->template = $template;
    $layouts = \apply_filters('ezwpz_theme_layout', ['layout/theme.php']);
    return \locate_template($layouts);
  }

  /**
   * Include the template file.
   * Use inside the layout template file.
   */
  public function template() {
    include $this->template;
  }
}
