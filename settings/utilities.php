<?php
namespace EZWPZ\Settings;

function do_settings_controls($page, $section, $field) {
  global $wp_settings_fields, $ezwpz_settings_controls;

  if (isset($ezwpz_settings_controls[$page][$section][$field]) && \is_array($ezwpz_settings_controls[$page][$section][$field])) {
    $controls = $ezwpz_settings_controls[$page][$section][$field];
    $is_alone_in_field = \count($controls) === 1;

    \ob_start();
    foreach ($controls as $control) {
      if (\is_callable($control['callback']))
        \call_user_func($control['callback'], $control['args'], $is_alone_in_field);
    }
    $output = \ob_get_clean();

    if (!$is_alone_in_field && isset($wp_settings_fields[$page][$section][$field])) {
      $output = \sprintf('<fieldset><legend class="screen-reader-text">%s</legend>%s</fieldset>', $wp_settings_fields[$page][$section][$field]['title'], $output);
    }

    echo $output;
  }
}

function render_page() {
  $page = $_GET['page'];
  ?>
  <div class="wrap">
    <h1><?php echo \esc_html(\get_admin_page_title()); ?></h1>
    <form method="post" action="/wp-admin/options.php">
      <?php
      \settings_fields($page);
      \do_settings_sections($page);
      ?>

      <?php
      ob_start();
      \do_settings_fields($page, 'default');
      $default_fields = ob_get_clean();
      ?>

      <?php if (!empty($default_fields)) : ?>
        <table class="form-table">
          <?php \do_settings_fields($page, 'default'); ?>
        </table>
      <?php endif; ?>

      <?php
      \submit_button();
      ?>
    </form>
  </div>
  <?php
}
