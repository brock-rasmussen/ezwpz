<?php
// CORE
require_once 'class-page.php';

// SETTINGS
require_once 'settings/class-setting.php';
require_once 'settings/class-settings-page.php';
require_once 'settings/class-settings-section.php';
require_once 'settings/class-settings-field.php';
require_once 'settings/class-settings-control.php';

/**
 * Easily add menu page.
 * @param string $id
 * @param array $args
 * @return EZWPZ_Settings\Page
 */
function ezwpz_settings_page($id, $args = []) {
  return new EZWPZ_Settings\Page($id, $args);
}

/**
 * Easily add a settings section.
 * @param string $page
 * @param string $id
 * @param array $args
 * @return EZWPZ_Settings\Section
 */
function ezwpz_settings_section($page, $id, $args = []) {
  return new EZWPZ_Settings\Section($page, $id, $args);
}

/**
 * Easily add a settings field.
 * @param string $page
 * @param string $section
 * @param string $id
 * @param array $args
 * @return EZWPZ_Settings\Field
 */
function ezwpz_settings_field($page, $section, $id, $args = []) {
  return new EZWPZ_Settings\Field($page, $section, $id, $args);
}

/**
 * Easily add a setting.
 * @param string $page
 * @param string $id
 * @param array $args
 * @return EZWPZ_Settings\Setting
 */
function ezwpz_setting($page, $id, $args = []) {
  return new EZWPZ_Settings\Setting($page, $id, $args);
}

/**
 * Easily add a control to the field.
 * @param string $page
 * @param string $section
 * @param string $field
 * @param string $setting
 * @param string $id
 * @param array $args
 * @return EZWPZ_Settings\Control
 */
function ezwpz_settings_control($page, $section, $field, $id, $args = []) {
  return new EZWPZ_Settings\Control($page, $section, $field, $id, $args);
}
