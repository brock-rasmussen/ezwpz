<?php

namespace EZWPZ\Admin\Control;

class Posts extends Query {
	protected function setup_query() {
		$this->query = wp_parse_args( $this->query, [
			'numberposts' => - 1,
			'orderby'     => 'title',
			'order'       => 'ASC',
		] );
	}

	protected function get_objects() {
		$this->objects = get_posts( $this->query );
	}

	protected function get_choices() {
		foreach ( $this->objects as $post ) {
			$this->choices[ $post->ID ] = $post->post_title;
		}
	}
}
