<?php

namespace EZWPZ\Admin\Control;

use EZWPZ\Admin\Control;

class SelectTerms extends Control
{
	/**
	 * Query parameters passed to `get_posts()`, `get_terms()`, or `get_users()`.
	 * @var array
	 * @since 1.0.0
	 */
	public $query = [];

	/**
	 * Terms retrieved from the $query.
	 * @var array
	 * @since 1.0.0
	 */
	public $terms = [];

	/**
	 * Constructor.
	 * @param string $id
	 * @param array $args
	 * @since 1.0.0
	 */
	public function __construct($id, $args = [])
	{
		$args = wp_parse_args($args, [
			'query' => [],
		]);

		if (isset($args['type']) && !in_array($args['type'], ['checkboxes', 'radio', 'select']))
			unset($args['type']);

		$this->query = wp_parse_args($args['query'], [
			'orderby' => 'name',
			'order' => 'ASC',
		]);
		unset($args['choices'], $args['query'], $args['terms']);

		$this->terms = get_terms($this->query);
		foreach ($this->terms as $term) {
			$this->choices[$term->ID] = $term->name;
		}

		parent::__construct($id, $args);
	}
}
