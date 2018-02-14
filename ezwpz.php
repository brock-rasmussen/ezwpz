<?php
/*
 * Plugin Name: Easy WPeasy
 * Author: Brock Rasmussen
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

define('EZWPZ_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));

require_once 'class-ezwpz.php';

/**
 * Function to return the EZWPZ class.
 * @return EZWPZ
 */
function ezwpz() {
  return EZWPZ::get_instance();
}

/**
 * Instantiate EZWPZ.
 */
ezwpz();

function ezwpz_menu_example($menu) {
  $menu->add_page('ezwpz_test_page', [
    'page_title' => __('Test Page', 'ezwpz'),
    'menu_title' => __('Test Page', 'ezwpz'),
  ]);
}
add_action('ezwpz_menu', 'ezwpz_menu_example');

function ezwpz_settings_example($settings) {
  // Create a setting with the below function.
  $settings->add_setting('ezwpz_test', [
    'page' => 'ezwpz_test_page',
  ]);

  // Sections, fields, and controls can be added using the functions below.
  $settings->add_section('test_section_1', [
    'page' => 'ezwpz_test_page',
    'title' => __('Test Section 1', 'ezwpz'),
    'description' => 'This is an example description. This has the `the_content` filter applied to automatically add paragraph tags and line breaks.',
  ]);
  $settings->add_field('test_single_control_field', [
    'page' => 'ezwpz_test_page',
    'section' => 'test_section_1',
    'title' => __('Field With a Single Control', 'ezwpz'),
    'label_for' => 'ezwpz_test_text',
  ]);
  $settings->add_control('ezwpz_test_text', [
    'page' => 'ezwpz_test_page',
    'section' => 'test_section_1',
    'field' => 'test_single_control_field',
    'setting' => 'ezwpz_test',
  ]);

  // Alternatively they can be added with a config object, with an associated page and setting.
  $settings->add_by_config('ezwpz_test_page', 'ezwpz_test', [
    'test_section_2' => [
      'title' => __('Test Section 2 - Priority', 'ezwpz'),
      'description' => 'This section should come first even though it is declared second.',
      'priority' => 5,
      'fields' => [
        'test_multiple_controls_field' => [
          'title' => __('Field With Multiple Controls', 'ezwpz'),
          'controls' => [
            'test_checkbox' => [
              'type' => 'checkbox',
              'label' => 'Enable this?',
            ],
          ],
        ],
      ],
    ],
  ]);

  // Or you can pass a json config file.
  $settings->add_by_config('ezwpz_test_page', 'ezwpz_test', EZWPZ_PLUGIN_DIR . '/example-settings-config.json');
}
add_action('ezwpz_settings', 'ezwpz_settings_example');
