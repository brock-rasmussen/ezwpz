<?php

namespace EZWPZ\Admin\Control;

use EZWPZ\Admin\Control;

class Color extends Control
{
	/**
	 * Constructor.
	 * @param string $id
	 * @param array $args
	 * @since 1.0.0
	 */
	public function __construct($id, $args = [])
	{
		$args['multiple'] = false;
		$args['type'] = 'text';

		if (!isset($args['input_attrs']))
			$args['input_attrs'] = ['class' => 'ezwpz-color-picker'];
		elseif (!isset($args['input_attrs']['class']))
			$args['input_attrs']['class'] = 'ezwpz-color-picker';
		else
			$args['input_attrs']['class'] .= ' ezwpz-color-picker';

		parent::__construct($id, $args);
	}

	/**
	 *
	 */
	public function enqueue()
	{
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('ezwpz-color-picker', plugins_url('assets/ezwpz-color-picker.js', EZWPZ_PLUGIN_PATH), ['wp-color-picker'], false, true);
	}
}
