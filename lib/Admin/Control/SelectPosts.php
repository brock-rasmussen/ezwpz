<?php

namespace EZWPZ\Admin\Control;

use EZWPZ\Admin\Control;

class SelectPosts extends Control
{
	/**
	 * Query parameters passed to `get_posts()`, `get_terms()`, or `get_users()`.
	 * @var array
	 * @since 1.0.0
	 */
	public $query = [];

	/**
	 * Posts retrieved from the $query.
	 * @var array
	 * @since 1.0.0
	 */
	public $posts = [];

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
			'numberposts' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		]);
		unset($args['choices'], $args['posts'], $args['query']);

		$this->posts = get_posts($this->query);
		foreach ($this->posts as $post) {
			$this->choices[$post->ID] = $post->post_title;
		}

		parent::__construct($id, $args);
	}
}
