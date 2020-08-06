<?php

namespace EZWPZ\Customizer;

class Control {
	/**
	 * @see WP_Customize_Manager::add_setting()
	 */
	public $setting_type = 'theme_mod'; // $type
	public $capability = 'edit_theme_options';
	public $theme_supports = '';
	public $default = '';
	public $transport = 'refresh';
	public $validate_callback = '';
	public $sanitize_callback = '';
	public $sanitize_js_callback = '';
	public $dirty = false;

	/**
	 * @see WP_Customize_Manager::add_control()
	 */
	public $priority = 10;
	public $section = '';
	public $label = '';
	public $description = '';
	public $choices = [];
	public $input_attrs = [];
	public $allow_addition = false;
	public $json = [];
	public $type = 'text';
	public $active_callback = '';

	/**
	 * @var WP_Customize_Manager
	 * @since 1.0.0
	 */
	public $manager;

	public function __construct($manager, $id, $args = []) {
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
		$this->id = $id;

		if ( ! isset( $this->sanitize_callback )) {
			switch ( $this->type ) {
				case 'email':
					$this->sanitize_callback = 'sanitize_email';
					break;
				case 'number':
					$this->sanitize_callback = 'intval';
					break;
				case 'textarea':
					$this->sanitize_callback = 'sanitize_textarea_field';
					break;
				case 'url':
					$this->sanitize_callback = 'esc_url_raw';
					break;
				default:
					$this->sanitize_callback = 'sanitize_text_field';
					break;
			}
		}

		$this->add_setting();
		$this->add_control();
	}

	public function __destruct() {
		$this->manager->remove_setting($this->id);
		$this->manager->remove_control($this->id);
	}

	public function add_setting() {
		$this->manager->add_setting($this->id, [
			'type' => $this->setting_type,
			'capability' => $this->capability,
			'theme_supports' => $this->theme_supports,
			'default' => $this->default,
			'transport' => $this->transport,
			'validate_callback' => $this->validate_callback,
			'sanitize_callback' => $this->sanitize_callback,
			'sanitize_js_callback' => $this->sanitize_js_callback,
			'dirty' => $this->dirty,
		]);
	}

	public function add_control() {
		$this->manager->add_control($this->id, [
			'priority' => $this->priority,
			'section' => $this->section,
			'label' => $this->label,
			'description' => $this->description,
			'choices' => $this->choices,
			'input_attrs' => $this->input_attrs,
			'allow_addition' => $this->allow_addition,
			'json' => $this->json,
			'type' => $this->type,
			'active_callback' => $this->active_callback,
		]);
	}
}
