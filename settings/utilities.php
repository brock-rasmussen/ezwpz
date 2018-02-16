<?php
namespace EZWPZ\Settings;

function do_settings_controls($page, $section, $field) {
  global $wp_settings_fields;
  if (isset($wp_settings_fields[$page][$section][$field]['args']['ezwpz']['controls']) && \is_array($wp_settings_fields[$page][$section][$field]['args']['ezwpz']['controls'])) {
    $field = $wp_settings_fields[$page][$section][$field];
    $is_alone_in_field = \count($field['args']['ezwpz']['controls']) === 1;

    \ob_start();
    foreach ($field['args']['ezwpz']['controls'] as $control) {
      if (\is_callable($control))
        \call_user_func($control, $is_alone_in_field);
    }
    $controls = \ob_get_clean();

    if (!$is_alone_in_field) {
      $controls = \sprintf('<fieldset><legend class="screen-reader-text">%s</legend>%s</fieldset>', $field['title'], $controls);
    }

    echo $controls;
  }
}
