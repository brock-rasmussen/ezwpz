<?php

namespace EZWPZ\Admin;

class Utilities
{
	/**
	 * Get the controls for a field.
	 * @param $page
	 * @param $section
	 * @param $field
	 * @return array
	 */
	public static function get_controls($page, $section, $field)
	{
		global $ezwpz_settings_controls;
		return isset($ezwpz_settings_controls[$page][$section][$field]) ? $ezwpz_settings_controls[$page][$section][$field] : [];
	}
}
