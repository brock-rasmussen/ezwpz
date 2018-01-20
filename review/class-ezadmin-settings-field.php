<?php
class EZAdmin_Settings_Field {
  /**
   * Page on which to show the section.
   *
   * @var EZAdmin_Settings_Page
   */
  public $page;

  /**
   * Section on which to show the control.
   *
   * @var EZAdmin_Settings_Section
   */
  public $section;

  /**
   * The EZAdmin_Setting the control is attached to.
   *
   * @var EZAdmin_Setting
   */
  public $setting;

  /**
   * Slug to identify the field.
   *
   * @var string
   */
  public $id;

  /**
   * When supplied, the setting title will be wrapped in a <label> element, its for attribute populated with this value.
   *
   * @var string
   */
  public $label_for = '';

  /**
   * CSS Class to be added to the <tr> element when the field is output.
   *
   * @var string
   */
  public $class = '';

  /**
   * Title for the field.
   *
   * @var string
   */
  public $title = '';

  /**
   * Control's type.
   *
   * @var string
   */
  public $type = 'text';

  /**
   * List of custom input attributes for control output, where attribute names are the keys and values are the values.
   *
   * @var array
   */
  public $input_attrs = [];

  /**
   * List of choices for 'radio' or 'select' type controls, where input values are the object keys, and labels are the
   * object values.
   *
   * @var array
   */
  public $choices = [];

  /**
   * Show UI for adding new content, currently only used for the dropdown-pages control.
   *
   * @var bool
   */
  public $allow_addition = false;

  /**
   * Description for the control.
   *
   * @var string
   */
  public $description = '';

  /**
   * EZAdmin_Settings_Control constructor.
   *
   * @param EZAdmin_Settings_Page $page
   * @param EZAdmin_Settings_Section $section
   * @param EZAdmin_Setting $setting
   * @param string $id
   * @param array $args
   */
  public function __construct( $page, $section, $setting, $id, array $args = [] ) {
    $keys = array_keys( get_object_vars( $this ) );
    foreach ( $keys as $key ) {
      if ( isset( $args[ $key ] ) ) {
        $this->$key = $args[ $key ];
      }
    }

    $this->page = $page;
    $this->section = $section;
    $this->setting = $setting;
    $this->id = $id;

    add_action( 'admin_init', [ $this, 'init' ] );
  }

  /**
   * Add the settings control.
   */
  public function init() {
    add_settings_field( $this->id, $this->title, [ $this, 'render' ], $this->page->menu_slug, $this->section->id );
  }

  public function do_settings_field() {
    $class = $this->class;
    $label_for = $this->label_for;
    $title = $this->title;

    if ( ! empty( $class ) ) {
      $class = sprintf( ' class="%s"', esc_attr( $class ) );
    }

    if ( ! empty( $label_for ) && ! empty( $title ) ) {
      $title = sprintf( '<label for="%s">%s</label>', esc_attr( $label_for ), $title );
    }

    echo "<tr{$class}><th scope='row'>{$title}</th><td>";
    $this->render();
    echo '</td></tr>';
  }

  public function render() {
    $input_id = 'settings-control-' . $this->id;
    $description_id = 'settings-description-' . $this->id;
    $describedby_attr = ! empty( $this->description ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

    switch( $this->type ) {
      case 'checkbox':
      case 'radio':
        if ( empty( $this->choices ) )
          return;

        $legend = ! empty( $this->section->title ) ? sprintf( '<legend class="screen-reader-text">%s</legend>', $this->section->title ) : '';
        ?>
        <fieldset>
          <legend class="screen-reader-text"><?php echo $this->section->title; ?></legend>
          <?php
          foreach ( $this->choices as $value => $label ) {
            printf( '<p><label><input name="%s" type="%s" value="%s" %s> %s</label></p>', 'name', esc_attr( $this->type ), esc_attr( $value ), checked( $value, 'value' , false ), $label );
          }
          ?>
        </fieldset>
        <?php
        break;
      case 'select':
        $control = '';
        break;
      case 'textarea':
        $control = '';
        break;
      case 'dropdown-pages':
        $control = '';
        break;
      default:
        printf( '<input id="%s" class="regular-text" type="%s">', $input_id, esc_attr( $this->type ) );
        break;
    }
  }

  /**
   * Render the custom attributes for the control's input element.
   */
  public function input_attrs() {
    foreach( $this->input_attrs as $attr => $value ) {
      echo $attr . '="' . esc_attr( $value ) . '" ';
    }
  }

  /**
   * Enqueue control related scripts.
   *
   * @param string $handle
   * @param string $src
   * @param array $deps
   * @param string|bool|null $ver
   * @param bool $in_footer
   */
  final public function enqueue_scripts( $handle, $src = '', $deps = [], $ver = false, $in_footer = false ) {
    $this->page->enqueue_script( $handle, $src, $deps, $ver, $in_footer );
  }

  /**
   * Enqueue control related styles.
   *
   * @param string $handle
   * @param string $src
   * @param array $deps
   * @param string|bool|null $ver
   * @param string $media
   */
  final public function enqueue_styles( $handle, $src = '', $deps = [], $ver = false, $media = 'all' ) {
    $this->page->enqueue_style( $handle, $src, $deps, $ver, $media );
  }
}
