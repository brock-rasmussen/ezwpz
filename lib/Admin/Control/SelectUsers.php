<?php

namespace EZWPZ\Admin\Control;

use EZWPZ\Admin\Control;

class SelectUsers extends Control
{
	/**
	 * Query parameters passed to `get_posts()`, `get_terms()`, or `get_users()`.
	 * @var array
	 * @since 1.0.0
	 */
	public $query = [];

	/**
	 * Users retrieved from the $query.
	 * @var array
	 * @since 1.0.0
	 */
	public $users = [];

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
			'orderby' => 'display_name',
			'order' => 'ASC',
		]);
		unset($args['choices'], $args['users'], $args['query']);

		$this->users = get_users($this->query);
		foreach ($this->users as $user) {
			$this->choices[$user->ID] = sprintf(_x('%1$s (%2$s)', 'user select'), $user->display_name, $user->user_login);
		}

		parent::__construct($id, $args);
	}
}
