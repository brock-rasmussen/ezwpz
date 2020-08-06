<?php

namespace EZWPZ\Admin\Control;

use EZWPZ\Admin\Control;

abstract class Query extends Control {
	/**
	 * Arguments passed to callback.
	 * @var mixed
	 * @since 1.0.0
	 */
	public $query = [];

	/**
	 * Objects retrieved from callback.
	 * @var array
	 * @since 1.0.0
	 */
	public $objects = [];

	/**
	 * Constructor.
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public function __construct( $id, $args = [] ) {
		if ( isset( $args['type'] ) && ! in_array( $args['type'], [ 'checkboxes', 'radio', 'select' ] ) ) {
			unset( $args['type'] );
		}

		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->id = $id;

		$this->setup_query();
		unset ( $args['choices'], $args['objects'], $args['query'] );

		$this->get_objects();
		$this->get_choices();

		if ( ! isset( $args['type'] ) ) {
			$this->type = isset( $args['multiple'] ) && $args['multiple'] ? 'checkboxes' : ( count( $this->choices ) > 5 ? 'select' : 'radio' );
		}

		parent::__construct( $id, $args );
	}

	/**
	 * Setup query or args to be used in `get_objects`.
	 */
	abstract protected function setup_query();

	/**
	 * Get objects to be used for the choices.
	 */
	abstract protected function get_objects();

	/**
	 * Process objects to return choices.
	 */
	abstract protected function get_choices();
}
