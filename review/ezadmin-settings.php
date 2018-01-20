<?php
require_once 'ezadmin-controls.php';

/**
 * Callback to render a settings page.
 */
if ( !function_exists('ezadmin_settings_page') ) {
  function ezadmin_settings_page() {
    if ( !is_admin() ) return;
    $page = wp_unslash( $_GET['page'] );
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form method="post" action="options.php">
        <?php
        settings_fields($page);
        do_settings_sections($page);
        submit_button();
        ?>
      </form>
    </div>
    <?php
  }
}

/**
 * Make additional args available to the settings section callback.
 *
 * @param string $page
 * @param string $section
 * @param string $description
 */
if ( !function_exists('add_ezadmin_settings_section_args') ) {
  function ezadmin_add_settings_section_description($page, $section, $description) {
    global $wp_settings_sections;
    $wp_settings_sections[$page][$section]['description'] = $description;
  }
}

/**
 * Callback to render a settings section description.
 *
 * @param array $section Passed from add_settings_section().
 */
if ( !function_exists('ezadmin_settings_section') ) {
  function ezadmin_settings_section($section) {
    if ( !empty($section['description']) )
      echo apply_filters( 'the_content', $section['description'] );
  }
}

/**
 * Add a settings field.
 *
 * @param string $page
 * @param string $section
 * @param string $id
 * @param array $args {
 *    An array of optional arguments to override the defaults.
 *
 *    @type string $title     Passed to add_settings_field().
 *    @type string $label_for Passed to add_settings_field().
 *    @type string $class     Passed to add_settings_field().
 * }
 */
if ( !function_exists('ezadmin_settings_field') ) {
  function ezadmin_settings_field($page, $section, $id, $args = []) {
    global $ezadmin_settings_controls;

    $args = wp_parse_args($args, [
      'title' => '',
    ]);

    $args['id'] = $id;

    if ( !empty($ezadmin_settings_controls[$page][$section][$id]) ) {
      $args['controls'] = $ezadmin_settings_controls[$page][$section][$id];
    }

    add_settings_field($id, $args['title'], 'render_ezadmin_settings_field', $page, $section, $args);
  }
}

/**
 * Callback to render a settings field.
 *
 * @param array $args Passed from add_settings_field().
 */
if ( !function_exists('render_ezadmin_settings_field') ) {
  function render_ezadmin_settings_field($args) {
    if ( empty($args['controls']) ) return;
    foreach ( $args['controls'] as $control ) {
      ezadmin_control();
    }
  }
}

/**
 * Add a settings control.
 *
 * @param string $page
 * @param string $section
 * @param string $field
 * @param string $id
 * @param array $args {
 *    An array of optional arguments to override the defaults.
 *
 *    @type string $title     Passed to add_settings_field().
 *    @type string $label_for Passed to add_settings_field().
 *    @type string $class     Passed to add_settings_field().
 * }
 */
if ( !function_exists('ezadmin_settings_control') ) {
  function ezadmin_settings_control($page, $section, $field, $id, $args) {
    global $ezadmin_settings_controls;

    $args = wp_parse_args($args, []);

    $ezadmin_settings_controls[$page][$section][$field][$id] = $args;
  }
}
