<?php
if ( ! class_exists( 'EZAdmin_Util' ) )
  require_once 'class-ezadmin-util.php';

class EZAdmin_Settings {
  /**
   * Add a settings field.
   *
   * @param string $page
   * @param string $section
   * @param string $id
   * @param array $args
   *    title
   *    label_for
   *    class
   */
  public static function add_field($page, $section, $id, $args = []) {
    $args = wp_parse_args($args, [
      'title' => ''
    ]);

    $args['page'] = $page;
    $args['section'] = $section;
    $args['field'] = $id;

    add_settings_field($id, $args['title'], 'EZAdmin_Settings::field', $page, $section, $args);
  }

  public static function add_control($page, $section, $field, $id, $args = []) {
    global $ezadmin_settings_controls;

    $args = wp_parse_args($args, [
      'type' => 'text',
      'label' => '',
      'description' => '',
      'choices' => [],
      'input_attrs' => [],
    ]);
    $args['id'] = $id;

    $ezadmin_settings_controls[$page][$section][$field][$id] = $args;
  }

  /**
   * Callback to render the field.
   *
   * @param array $args
   */
  public static function field($args) {
    global $ezadmin_settings_controls;


    foreach( (array) $ezadmin_settings_controls[$args['page']][$args['section']][$args['field']] as $control ) {
      var_dump($control);
      EZAdmin_Util::control( $control );
    }
  }
}
