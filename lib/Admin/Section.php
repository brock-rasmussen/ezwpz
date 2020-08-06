<?php

namespace EZWPZ\Admin;

class Section {
	/**
	 * @see add_settings_section()
	 */
	public $id;
	public $title = '';
	public $page = 'general';

	/**
	 * Section display priority.
	 * @var int
	 * @since 1.0.0
	 */
	public $priority = 10;

	/**
	 * Section description.
	 * @var string
	 * @since 1.0.0
	 */
	public $description;

	/**
	 * Constructor
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public function __construct( $id, $args = [] ) {
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}
		$this->id = $id;
		add_action( 'admin_init', [ $this, 'init' ], $this->priority );
	}

	/**
	 * Destructor.
	 * @since 1.0.0
	 */
	public function __destruct() {
		remove_action( 'admin_init', [ $this, 'init' ], $this->priority );
	}

	/**
	 * Add section.
	 * @since 1.0.0
	 */
	public function init() {
		add_settings_section( $this->id, $this->title, [ $this, 'render' ], $this->page );
	}

	/**
	 * Render the section description.
	 *
	 * @param array $section
	 */
	public function render( $section ) {
		if ( ! empty( $this->description ) ) {
			echo apply_filters( 'the_content', $this->description );
		}
	}
}
