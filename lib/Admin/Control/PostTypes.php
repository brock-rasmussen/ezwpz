<?php

namespace EZWPZ\Admin\Control;

class PostTypes extends Query {
	protected function setup_query() {
		$this->query = wp_parse_args( $this->query, [
			'public'  => true,
			'show_ui' => true,
		] );
	}

	protected function get_objects() {
		$this->objects = get_post_types( $this->query, 'objects' );
	}

	protected function get_choices() {
		foreach ( $this->objects as $post_type ) {
			$this->choices[ $post_type->name ] = $post_type->label;
		}
	}
}
