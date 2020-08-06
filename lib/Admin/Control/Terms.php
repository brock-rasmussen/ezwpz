<?php

namespace EZWPZ\Admin\Control;

class Terms extends Query {
	protected function setup_query() {
		$this->query = wp_parse_args($this->query, [
			'orderby' => 'name',
			'order' => 'ASC',
		]);
	}

	protected function get_objects() {
		$this->objects = get_terms($this->query);
	}

	protected function get_choices() {
		foreach ($this->objects as $term) {
			$this->choices[$term->ID] = $term->name;
		}
	}
}
