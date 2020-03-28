<?php

namespace EZWPZ\Admin;

class Field
{
	/**
	 * @see add_settings_field()
	 */
	public $id;
	public $title;
	public $page = 'general';
	public $section = 'default';
	public $class = '';

	/**
	 * Field display priority.
	 * @var int
	 * @since 1.0.0
	 */
	public $priority = 10;

	/**
	 * Constructor.
	 * @param string $id
	 * @param array $args
	 * @since 1.0.0
	 */
	public function __construct($id, $args = [])
	{
		$keys = array_keys(get_object_vars($this));
		foreach ($keys as $key) {
			if (isset($args[$key])) {
				$this->$key = $args[$key];
			}
		}
		$this->id = $id;
		add_action('admin_init', [$this, 'init'], $this->priority);
	}

	/**
	 * Destructor.
	 * @since 1.0.0
	 */
	public function __destruct()
	{
		remove_action('admin_init', [$this, 'init'], $this->priority);
	}

	/**
	 * Add section.
	 * @since 1.0.0
	 */
	public function init()
	{
		do_action("ezwpz_admin_field-{$this->page}-{$this->section}-{$this->id}");

		$controls = Utilities::get_controls($this->page, $this->section, $this->id);
		$first_control_id = array_key_first($controls);
		$label_for = $first_control_id && count($controls) < 2 && !in_array($controls[$first_control_id]['args']['type'], ['radio', 'checkboxes']) ? $first_control_id : '';

		add_settings_field($this->id, $this->title, [$this, 'render'], $this->page, $this->section, [
			'label_for' => $label_for,
			'class' => $this->class,
		]);
	}

	/**
	 * Render the field.
	 * @since 1.0.0
	 */
	public function render()
	{
		$controls = Utilities::get_controls($this->page, $this->section, $this->id);
		$has_one_control = count($controls) < 2;
		ob_start();
		foreach ($controls as $control) {
			if (is_callable($control['callback']))
				call_user_func($control['callback'], $control['args'], $has_one_control);
		}
		$output = ob_get_clean();
		if (!$has_one_control)
			$output = sprintf('<fieldset><legend class="screen-reader-text">%s</legend>%s</fieldset>', $this->title, $output);
		echo $output;
	}
}
