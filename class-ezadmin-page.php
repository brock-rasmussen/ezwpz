<?php
class EZAdmin_Page {
    /**
     * Text domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * Hook suffix.
     *
     * @var string
     */
    protected $hook_suffix;

    /**
     * Data URI base64-encoded svg, Dashicons helper class, or none.
     *
     * @var string
     */
    protected $icon_url;

    /**
     * Settings page slug.
     *
     * @var string
     */
    protected $menu_slug;

    /**
     * Menu title.
     *
     * @var string
     */
    protected $menu_title;

    /**
     * Settings page title.
     *
     * @var string
     */
    protected $page_title;

    /**
     * Parent page slug.
     *
     * @var string
     */
    protected $parent_slug;

    /**
     * Position in the menu order this page should appear.
     *
     * @var int
     */
    protected $position;

    /**
     * Settings schema.
     *
     * @var array
     */
    protected $settings_schema;

    /**
     * Track section IDs to ensure there are no duplicates (at least on the same page).
     *
     * @var array
     */
    protected $sections = [];

    /**
     * Track setting IDs to ensure there are no duplicates (at least on the same page).
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Track setting types to load extra scripts if necessary.
     *
     * @var array
     */
    protected $settings_types = [];

    /**
     * If true, save each setting in it's own option.
     *
     * @var bool
     */
    protected $single;

    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct( $args = [] ) {
        $args = wp_parse_args( $args, [
            'domain' => '',
            'icon_url' => 'none',
            'menu_slug' => '',
            'menu_title' => '',
            'page_title' => '',
            'parent_slug' => '',
            'position' => null,
            'settings_schema' => '',
            'single' => false,
        ] );

        if ( empty( $args['menu_slug'] ) || empty( $args['settings_schema'] ) )
            return;

        $this->domain = $args['domain'];
        $this->icon_url = $args['icon_url'];
        $this->menu_slug = $args['menu_slug'];
        $this->menu_title = $args['menu_title'];
        $this->page_title = $args['page_title'];
        $this->parent_slug = $args['parent_slug'];
        $this->position = $args['position'];
        $this->settings_schema = $args['settings_schema'];
        $this->single = $args['single'];

        add_action( 'admin_menu', [ $this, 'add_page' ] );
        add_action( 'admin_init', [ $this, 'add_settings' ] );
    }

    /**
     * Add admin page.
     *
     * @see https://developer.wordpress.org/reference/functions/add_menu_page/
     * @see https://developer.wordpress.org/reference/functions/add_submenu_page/
     */
    public function add_page() {
        $capability = 'manage_options';
        $icon_url = $this->icon_url;
        $menu_slug = $this->menu_slug;
        $menu_title = $this->menu_title;
        $page_title = $this->page_title;
        $parent_slug = $this->parent_slug;
        $position = $this->position;

        $callback = apply_filters( "{$menu_slug}_page_callback", [ $this, 'page' ] );

        if ( empty( $parent_slug ) ) :
            $hook_suffix = add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url, $position );
        else:
            $hook_suffix = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback );
        endif;

        $this->hook_suffix = $hook_suffix;

        add_action( "admin_print_styles-{$hook_suffix}", [ $this, 'enqueue_styles' ] );
        add_action( "admin_print_scripts-{$hook_suffix}", [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Page markup.
     */
    public function page() {
        $menu_slug = $this->menu_slug;
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( $menu_slug );
                do_settings_sections( $menu_slug );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Enqueue scripts if necessary.
     */
    protected function enqueue_scripts() {
        if ( in_array( 'color', $this->settings_types ) )
            wp_enqueue_script( "{$this->menu_slug}-color-picker", plugins_url( 'color-picker.js', __FILE__ ), [ 'wp-color-picker' ], false, true );

        if ( in_array( 'image_picker', $this->settings_types ) )
            wp_enqueue_media();
    }

    /**
     * Enqueue styles if necessary.
     */
    protected function enqueue_styles() {
        if ( in_array( 'color', $this->settings_types ) )
            wp_enqueue_style( 'wp-color-picker' );
    }

    /**
     * Process settings file to generate sections and settings.
     */
    protected function add_settings() {
        $domain = $this->domain;
        $menu_slug = $this->menu_slug;

        foreach ( $this->settings_schema as $section ) :
            if ( in_array( $section['id'], $this->sections ) )
                wp_die( sprintf( __( 'Duplicate section ID. Section IDs must be unique. ID <code>%s</code> already exists.', $domain ), $section['id'] ), __( 'Duplicate section ID', $domain ) );

            $this->sections[] = $section['id'];

            $section_callback = apply_filters( "{$menu_slug}_add_settings_section_callback", [ $this, 'section_callback' ] );
            $section_callback = apply_filters( "{$menu_slug}_add_settings_section_callback-{$section['id']}", $section_callback );

            add_settings_section( $section['id'], __( $section['label'], $domain ), $section_callback, $menu_slug );

            foreach ( $section['settings'] as $setting ) :
                if ( in_array( $setting['id'], $this->settings ) )
                    wp_die( sprintf( __( 'Duplicate setting ID. Setting IDs must be unique. ID <code>%s</code> already exists.', $domain ), $setting['id'] ), __( 'Duplicate setting ID', $domain ) );

                $this->settings[] = $setting['id'];

                if ( ! in_array( $setting['type'], $this->settings_types ) )
                    $this->settings_types[] = $setting['type'];

                $field_args = [];
                $register_args = [];
                $type = $setting['type'];

                // Set a sanitize callback
                if ( $type === 'color' ) {
                    $register_args['sanitize_callback'] = 'sanitize_hex_color';
                }
                if ( $type === 'email' ) {
                    $register_args['sanitize_callback'] = 'sanitize_email';
                }
                if ( in_array( $type, [ 'richtext', 'textarea' ] ) ) {
                    $register_args['sanitize_callback'] = 'sanitize_textarea_field';
                }
                if ( in_array( $type, [ 'date', 'datetime-local', 'month', 'text', 'time', 'week', 'url' ] ) ) {
                    $register_args['sanitize_callback'] = 'sanitize_text_field';
                }
                if ( in_array( $type, [ 'category','checkbox','image_picker','link_list','number','page','radio','range','select' ] ) ) {}

                if ( in_array( $setting['type'], [ 'color', 'email', 'image_picker', 'link_list', 'page', 'range', 'select', 'text', 'textarea', 'url' ] ) )
                    $field_args = [ 'label_for' => $setting['id'] ];

                if ( isset( $setting['default'] ) )
                    $register_args['default'] = $setting['default'];

                $register_args = apply_filters( "{$menu_slug}_register_setting", $register_args );
                $register_args = apply_filters( "{$menu_slug}_register_setting-{$setting['type']}", $register_args );
                $register_args = apply_filters( "{$menu_slug}_register_setting-{$setting['id']}", $register_args );

                $field_args = apply_filters( "{$menu_slug}_add_settings_field", $field_args );
                $field_args = apply_filters( "{$menu_slug}_add_settings_field-{$setting['type']}", $field_args );
                $field_args = apply_filters( "{$menu_slug}_add_settings_field-{$setting['id']}", $field_args );

                $field_args['setting'] = $setting;

                $settings_callback = apply_filters( "{$menu_slug}_add_settings_field_callback", [ $this, 'field_callback' ] );
                $settings_callback = apply_filters( "{$menu_slug}_add_settings_field_callback-{$setting['type']}", $settings_callback );
                $settings_callback = apply_filters( "{$menu_slug}_add_settings_field_callback-{$setting['id']}", $settings_callback );

                register_setting( $menu_slug, $setting['id'], $register_args );
                add_settings_field( $setting['id'], __( $setting['label'], $domain ), $settings_callback, $menu_slug, $section['id'], $field_args );
            endforeach;
        endforeach;
    }

    /**
     * Section callback.
     *
     * @param $section array
     */
    protected function section_callback( $section ) {
        $i = array_search( $section['id'], $this->sections );
        if ( $i !== false )
            echo $this->settings_schema[$i]['info'];
    }

    /**
     * Field callback.
     *
     * @param $field_args array
     */
    protected function field_callback( $field_args ) {
        if ( ! isset( $field_args['setting'] ) && ! isset( $field_args['setting']['type'] ) && ! isset( $field_args['setting']['id'] ) && ! isset( $field_args['setting']['label'] ) ) {
            _e( 'A <code>type</code>, <code>id</code>, and <code>label</code> are required for on each setting.', $this->domain );
            return;
        }

        $setting = $field_args['setting'];
        $value = get_option( $setting['id'] );
        $type = $setting['type'];

        if ( $type === 'category' ) {
            $this->dropdown_categories( $setting, $value );
        } elseif ( in_array( $type, [ 'checkbox', 'radio' ] ) ) {
            $this->selection_list( $setting, $value );
        } elseif ( in_array( $type, [ 'color', 'date', 'datetime-local', 'email', 'month', 'number', 'range', 'text', 'time', 'week', 'url' ] ) ) {
            $this->input( $setting, $value );
        } elseif ( $type === 'link_list' ) {
            $this->dropdown_nav_menus( $setting, $value );
        } elseif ( $type === 'page' ) {
            $this->dropdown_pages( $setting, $value );
        } elseif ( $type === 'richtext' ) {
            $this->editor( $setting, $value );
        } elseif ( $type === 'select' ) {
            $this->dropdown( $setting, $value );
        } elseif ( $type === 'textarea' ) {
            $this->textarea( $setting, $value );
        }

        if ( $setting['info'] )
            $this->info( $setting );
    }

    /**
     * Generate a datalist.
     *
     * @param $setting array  JSON of current setting from the schema
     *
     * @return string
     */
    protected function datalist( $setting ) {
        if ( ! isset( $setting['options'] ) ) {
            _e( '<code>select</code> fields require an <code>options</code> property.', $this->domain );
            return;
        }

        $domain = $this->domain;
        $id = $setting['id'];
        $menu_slug = $this->menu_slug;

        $attributes = "id='{$id}-list'";

        $options = '';
        foreach( $setting['options'] as $option ) {
            $options .= $this->option( __( $option['value'], $domain ) );
        }

        $output = sprintf( '<datalist %s>%s</datalist>', $attributes, $options );
        $output = apply_filters( "{$menu_slug}_datalist", $output );
        $output = apply_filters( "{$menu_slug}_datalist-{$id}", $output );
        return $output;
    }

    /**
     * Generate a select dropdown.
     *
     * @param $setting array  JSON of curent setting from the schema
     * @param $value   string Current value
     */
    protected function dropdown( $setting, $value ) {
        if ( ! isset( $setting['options'] ) ) {
            _e( '<code>select</code> fields require an <code>options</code> property.', $this->domain );
            return;
        }

        $domain = $this->domain;
        $id = $setting['id'];
        $menu_slug = $this->menu_slug;

        $attributes = "id='{$id}' name='{$id}'";

        $options = $this->option( -1, __( '— Select —', $domain ), $value );
        foreach ( $setting['options'] as $option ) {
            if ( ! isset( $option['value'] ) )
                return;

            $label = isset( $option['label'] ) ? $option['label'] : null;
            $options .= $this->option( $option['value'], $label, $value );
        }

        $output = sprintf( '<select %s>%s</select>', $attributes, $options );
        $output = apply_filters( "{$menu_slug}_dropdown", $output );
        $output = apply_filters( "{$menu_slug}_dropdown-{$id}", $output );
        echo $output;
    }

    /**
     * Generate a select dropdown of a taxonomy (categories by default).
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Current value
     */
    protected function dropdown_categories( $setting, $value ) {
        $domain = $this->domain;
        $id = $setting['id'];
        $menu_slug = $this->menu_slug;

        $dropdown_args = apply_filters( "{$menu_slug}_dropdown_category", [
            'show_option_none' => __( '— Select —', $domain ),
            'orderby' => 'name',
        ] );

        if ( isset( $setting['taxonomy'] ) )
            $dropdown_args['taxonomy'] = $setting['taxonomy'];

        $dropdown_args = apply_filters( "{$menu_slug}_dropdown_category-{$id}", $dropdown_args );

        // Don't allow the id, name, or selected to be overwritten in the filter.
        $dropdown_args = array_merge( $dropdown_args, [ 'id' => $id, 'name' => $id, 'selected' => $value ] );

        wp_dropdown_categories( $dropdown_args );
    }

    /**
     * Generate a select dropdown of nav menus.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Current value
     */
    protected function dropdown_nav_menus( $setting, $value ) {
        $menus = wp_get_nav_menus();

        $setting['options'] = [];
        foreach ( $menus as $menu ) {
            $option = [
                'value' => $menu->term_id,
                'label' => $menu->name,
            ];
            $setting['options'][] = $option;
        }

        $this->dropdown( $setting, $value );
        return;
    }

    /**
     * Generate a select dropdown of pages.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Current value
     */
    protected function dropdown_pages( $setting, $value ) {
        $domain = $this->domain;
        $id = $setting['id'];
        $menu_slug = $this->menu_slug;

        $dropdown_args = apply_filters( "{$menu_slug}_dropdown_category", [ 'show_option_none' => __( '— Select —', $domain ) ] );
        $dropdown_args = apply_filters( "{$menu_slug}_dropdown_category-{$id}", $dropdown_args );

        // Don't allow the id, name, or selected to be overwritten in the filter.
        $dropdown_args = array_merge( $dropdown_args, [ 'id' => $id, 'name' => $id, 'selected' => $value ] );

        wp_dropdown_pages( $dropdown_args );
    }

    /**
     * Generate a wysiwyg editor.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Current value
     */
    protected function editor( $setting, $value ) {
        $menu_slug = $this->menu_slug;

        $editor_settings = apply_filters( "{$menu_slug}_editor", [] );
        $editor_settings = apply_filters( "{$menu_slug}_editor-{$setting['id']}", $editor_settings );

        // Don't allow the name to be overwritten in the filter.
        $editor_settings = array_merge( $editor_settings, [ 'textarea_name' => $setting['id'] ] );

        wp_editor( $value, $setting['id'], $editor_settings );
    }

    /**
     * Generate an image picker.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Input value
     */

    /**
     * Generate a description paragraph.
     *
     * @param $setting array JSON of current setting from the schema
     */
    protected function info( $setting ) {
        printf( '<p class="description">%s</p>', $setting['info'] );
    }

    /**
     * Generate an input.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Input value
     * @param $checked string Value of checked input (radio and checkbox)
     *
     * @return string|void
     */
    protected function input( $setting, $value, $checked = null ) {
        $id = $setting['id'];
        $input_type = $setting['type'];
        $menu_slug = $this->menu_slug;
        $type = $setting['type'];

        $white_listed = [];
        $datalist = false;

        if ( in_array( $type, [ 'email', 'number', 'text', 'url' ] ) ) {
            array_push( $white_listed, 'placeholder' );
        }
        if ( in_array( $type, [ 'date', 'datetime-local', 'month', 'number', 'range', 'time', 'week' ] ) ) {
            array_push( $white_listed, 'max', 'min', 'step' );
        }
        if ( in_array( $type, [ 'email', 'text', 'url' ] ) ) {
            array_push( $white_listed, 'maxlength', 'minlength', 'pattern' );
        }

        // Attributes to be added to the input
        $attributes = '';
        if ( in_array( $type, [ 'checkbox', 'radio' ] ) ) {
            $attributes .= checked($value, $checked, false) . ' ';
        }
        if ( ! in_array( $type, [ 'checkbox', 'radio' ] ) ) {
            $attributes .= "id='{$id}' ";

            if ( isset( $setting['options'] ) ) {
                $datalist = true;
                $attributes .= "list='{$id}-list'";
            }
        }
        $attributes .= $type === 'checkbox' ? "name='{$id}[]' " : "name='{$id}' ";
        if ( $type === 'color' ) {
            $input_type = 'text';
            $attributes .= "class='color-picker' ";
        }
        if ( in_array( $type, [ 'email', 'text', 'url' ] ) ) {
            $attributes .= "class='regular-text' ";
        }

        $escaped_value = esc_attr( $value );
        $attributes .= "type='{$input_type}' value='{$escaped_value}'";
        foreach ( $white_listed as $attribute ) {
            if ( isset( $setting[$attribute] ) )
                $attributes .= " {$attribute}='{$setting[$attribute]}'";
        }

        $output = sprintf( '<input %s>', $attributes );
        $output = apply_filters( "{$menu_slug}_{$type}_input", $output );
        $output = apply_filters( "{$menu_slug}_{$type}_input-{$id}", $output );

        if ( in_array( $type, [ 'checkbox', 'radio' ] ) )
            return $output;

        echo $output;

        if ( $datalist ) {
            echo $this->datalist( $setting );
        }
    }

    /**
     * Generate an option. If no label given, generates an option for a datalist.
     *
     * @param $value    string Value of option
     * @param $label    string Label
     * @param $selected string Selected value
     *
     * @return string
     */
    protected function option( $value , $label = null, $selected = null ) {
        if ( $label ) {
            return sprintf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $value, $selected, false ), __( $label, $this->domain ) );
        }
        return sprintf( '<option value="%s">', esc_attr( $value ) );
    }

    /**
     * Generates a list of checkbox or radio inputs.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Current value
     */
    protected function selection_list( $setting, $value ) {
        if ( ! isset( $setting['options'] ) ) {
            _e( '<code>checkbox</code> and <code>radio</code> fields require an <code>options</code> property.', $this->domain );
            return;
        }

        $domain = $this->domain;
        $id = $setting['id'];
        $menu_slug = $this->menu_slug;

        $options = '';
        foreach( $setting['options'] as $option ) {
            if ( ! isset( $option['value'] ) || ! isset( $option['label'] ) )
                return;

            $input = $this->input( $setting, $option['value'], $value );
            $options .= sprintf( '<p><label>%s %s</label>', $input, __( $option['label'], $domain ) );
        }

        $output = sprintf( '<fieldset><legend class="screen-reader-text">%s</legend>%s</fieldset>', __( $setting['label'], $domain ), $options );
        $output = apply_filters( "{$menu_slug}_input_list", $output );
        $output = apply_filters( "{$menu_slug}_input_list-{$id}", $output );
        echo $output;
    }

    /**
     * Generate a textarea.
     *
     * @param $setting array  JSON of current setting from the schema
     * @param $value   string Current value
     */
    protected function textarea( $setting, $value ) {
        $id = $setting['id'];
        $menu_slug = $this->menu_slug;

        $white_listed = [ 'placeholder', 'maxlength', 'minlength' ];

        $attributes = "class='large-text' id='{$id}' name='{$id}' rows='10' cols='50'";

        foreach( $white_listed as $attribute ) {
            if ( isset( $setting[$attribute] ) )
                $attributes .= " {$attribute}='{$setting[$attribute]}'";
        }

        $output = sprintf( '<textarea %s>%s</textarea>', $attributes, $value );

        $output = apply_filters( "{$menu_slug}_textarea", $output );
        $output = apply_filters( "{$menu_slug}_textarea-{$id}", $output );

        echo $output;
    }
}
