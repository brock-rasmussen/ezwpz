<?php

namespace EZWPZ\Admin\Control;

class Taxonomies extends Query {
	protected function setup_query() {
		$this->query = wp_parse_args( $this->query, [
			'public'  => true,
			'show_ui' => true,
		] );
	}

	protected function get_objects() {
		$this->objects = get_taxonomies( $this->query, 'objects' );
	}

	protected function get_choices() {
		foreach ( $this->objects as $taxonomy ) {
			$this->choices[ $taxonomy->name ] = $taxonomy->label;
		}
	}
}
