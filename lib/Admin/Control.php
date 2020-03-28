<?php

namespace EZWPZ\Admin;

class Control
{
	/**
	 * @see register_setting()
	 */
	public $page = 'general'; // $option_group
	public $id; // $option_name;
	public $description = '';
	public $sanitize_callback;
	public $show_in_rest = false;
	public $default;

	/**
	 * Section to display the control.
	 * @var string
	 * @since 1.0.0
	 */
	public $section = 'default';

	/**
	 * Field to display the control.
	 * @var string
	 * @since 1.0.0
	 */
	public $field;

	/**
	 * Priority to add the control.
	 * @var int
	 * @since 1.0.0
	 */
	public $priority = 10;

	/**
	 * Control type.
	 * @var string
	 * @since 1.0.0
	 */
	public $type = 'text';

	/**
	 * Control label.
	 * @var string
	 * @since 1.0.0
	 */
	public $label = '';

	/**
	 * Array of choices. $value => $label.
	 * For 'radio', 'select', or 'text' type controls.
	 * @var array
	 * @since 1.0.0
	 */
	public $choices = [];

	/**
	 * Allow selection of multiple $choices.
	 * For '' type controls.
	 * @var array
	 * @since 1.0.0
	 */
	public $multiple = false;

	/**
	 * Additional input attributes. $attribute => $value.
	 * Not used for all control types.
	 * @var array
	 * @since 1.0.0
	 */
	public $input_attrs = [];

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

		if (!isset($args['type']) && count($this->choices))
			$this->type = $this->multiple ? 'checkboxes' : (count($this->choices) > 5 ? 'select' : 'radio');

		if ($this->type === 'checkboxes')
			$this->multiple = true;

		if (!isset($this->sanitize_callback) && !$this->multiple) {
			switch ($this->type) {
				case 'email':
					$this->sanitize_callback = 'sanitize_email';
					break;
				case 'number':
					$this->sanitize_callback = 'intval';
					break;
				case 'richtext':
					$this->sanitize_callback = 'wp_filter_post_kses';
					break;
				case 'textarea':
					$this->sanitize_callback = 'sanitize_textarea_field';
					break;
				case 'url':
					$this->sanitize_callback = 'esc_url_raw';
					break;
				default:
					$this->sanitize_callback = 'sanitize_text_field';
					break;
			}
		}

		$input_attrs = [];
		switch ($this->type) {
			case 'email':
			case 'text':
			case 'url':
				$input_attrs['class'] = 'regular-text';
				break;
			case 'textarea':
				$input_attrs['class'] = 'large-text';
				$input_attrs['rows'] = 10;
				$input_attrs['cols'] = 50;
				break;
		}
		$this->input_attrs = wp_parse_args($this->input_attrs, $input_attrs);

		add_action('admin_init', [$this, 'register_setting'], $this->priority);
		add_action("ezwpz_admin_field-{$this->page}-{$this->section}-{$this->field}", [$this, 'init'], $this->priority);

		if (isset($this->setting) && isset($this->sanitize_callback))
			add_filter("sanitize_option_{$this->setting}", [$this, 'sanitize']);

		if (isset($this->setting) && !empty($this->default))
			add_filter("default_option_{$this->setting}", [$this, 'set_default']);

		add_action("ezwpz_admin_page_enqueue-{$this->page}", [$this, 'enqueue']);
	}

	/**
	 * Destructor.
	 * @since 1.0.0
	 */
	public function __destruct()
	{
		remove_action('admin_init', [$this, 'init']);
		remove_action("ezwpz_admin_field-{$this->page}-{$this->section}-{$this->field}", [$this, 'init']);

		if (isset($this->setting) && isset($this->sanitize_callback))
			remove_filter("sanitize_option_{$this->setting}", [$this, 'sanitize']);

		if (isset($this->setting) && !empty($this->default))
			remove_filter("default_option_{$this->setting}", [$this, 'set_default']);
	}

	/**
	 * Register setting.
	 * @since 1.0.0
	 */
	public function register_setting()
	{
		register_setting($this->page, $this->id, [
			'description' => $this->description,
			'sanitize_callback' => $this->sanitize_callback,
			'show_in_rest' => $this->show_in_rest,
			'default' => $this->default,
		]);
	}

	/**
	 * Add control to field.
	 * @since 1.0.0
	 */
	public function init()
	{
		global $ezwpz_settings_controls;
		if (!isset($this->field))
			return;

		$ezwpz_settings_controls[$this->page][$this->section][$this->field][$this->id] = [
			'id' => $this->id,
			'callback' => [$this, 'render'],
			'args' => [
				'settings' => $this->id,
				'id' => $this->id,
				'type' => $this->type,
				'label' => $this->label,
				'description' => $this->description,
				'default' => $this->default,
				'choices' => $this->choices,
				'input_attrs' => $this->input_attrs,
			]
		];
	}

	/**
	 * Enqueue scripts and styles for the control if needed.
	 * @since 1.0.0
	 */
	public function enqueue()
	{
	}

	/**
	 * Render the attributes for the control's input element.
	 */
	public function input_attrs()
	{
		foreach ($this->input_attrs as $attr => $value) {
			echo $attr . '="' . esc_attr($value) . '" ';
		}
	}

	/**
	 * Get the control value.
	 * @since 1.0.0
	 */
	public function value()
	{
		$value = get_option($this->id, $this->default);
		$value = !$value && $this->multiple ? [] : $value;
		return $value;
	}

	/**
	 * Render the control.
	 * @since 1.0.0
	 */
	public function render()
	{
		$field_controls = Utilities::get_controls($this->page, $this->section, $this->field);

		$input_id = esc_attr($this->id);
		$input_name = esc_attr($this->id);
		$description_id = esc_attr("{$this->id}-description");
		$describedby_attr = !empty($this->description) ? " aria-describedby='{$description_id}' " : '';
		$value = $this->value();

		switch ($this->type) {
			case 'checkbox':
				?>
				<label for="<?php echo $input_id; ?>">
					<input
						id="<?php echo $input_id; ?>"
						type="checkbox"
						value="1"
						name="<?php echo $input_name; ?>"
						<?php echo $describedby_attr; ?>
						<?php checked($value); ?>
					/>
					<?php echo $this->label; ?>
				</label>
				<?php
				if (!empty($this->description)) : ?>
					<p id="<?php echo $description_id; ?>" class="description">
						<?php echo $this->description; ?>
					</p>
				<?php endif;
				break;
			case 'checkboxes':
				if (empty($this->choices)) return;
				?>
				<fieldset>
					<legend<?php if (count($field_controls) < 2) : ?> class="screen-reader-text"<?php endif; ?>>
						<?php echo $this->label; ?>
					</legend>
					<p>
						<?php for ($i = 0; $i < count($this->choices); $i++) : ?>
							<label>
								<input
									type="checkbox"
									value="<?php echo esc_attr(key($this->choices)); ?>"
									name="<?php echo $input_name; ?>[]"
									<?php if (in_array(key($this->choices), $value)) : ?> checked="checked"<?php endif; ?>
								/>
								<?php echo current($this->choices); ?>
							</label>
							<?php if (next($this->choices)) : ?>
								<br/>
							<?php endif; ?>
						<?php endfor; ?>
					</p>
					<?php if (!empty($this->description)) : ?>
						<p class="description">
							<?php echo $this->description; ?>
						</p>
					<?php endif; ?>
				</fieldset>
				<?php
				break;
			case 'radio':
				if (empty($this->choices)) return;
				?>
				<fieldset>
					<legend<?php if (count($field_controls) < 2) : ?> class="screen-reader-text"<?php endif; ?>>
						<?php echo $this->label; ?>
					</legend>
					<p>
						<?php for ($i = 0; $i < count($this->choices); $i++) : ?>
							<label>
								<input
									type="radio"
									value="<?php echo esc_attr(key($this->choices)); ?>"
									name="<?php echo $input_name; ?>"
									<?php checked($value, key($this->choices)); ?>
								/>
								<?php echo current($this->choices); ?>
							</label>
							<?php if (next($this->choices)) : ?>
								<br/>
							<?php endif; ?>
						<?php endfor; ?>
					</p>
					<?php if (!empty($this->description)) : ?>
						<p class="description">
							<?php echo $this->description; ?>
						</p>
					<?php endif; ?>
				</fieldset>
				<?php
				break;
			case 'richtext':
				?>
				<fieldset>
					<legend<?php if (count($field_controls) < 2) : ?> class="screen-reader-text"<?php endif; ?>><?php echo esc_html($this->label); ?></legend>
					<?php if (!empty($this->description)) : ?>
						<p><label for="<?php echo $input_id; ?>"><?php echo $this->description; ?></label></p>
					<?php endif;
					wp_editor($value, $input_id, [
						'textarea_name' => $input_name,
						'textarea_rows' => 10,
					]); ?>
				</fieldset>
				<?php
				break;
			case 'select':
				if (empty($this->choices)) return;
				if (count($field_controls) > 1 && !empty($this->label)) : ?>
					<p><label for="<?php echo $input_id; ?>"><?php echo esc_html($this->label); ?></label></p>
				<?php endif; ?>
				<select
					id="<?php echo $input_id; ?>"
					name="<?php echo $input_name; ?>"
					<?php echo $describedby_attr; ?>
					<?php echo $this->input_attrs(); ?>>
					<?php foreach ($this->choices as $val => $label) : ?>
						<option
							value="<?php echo $val; ?>"<?php selected($value, $val); ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
				<?php
				if (!empty($this->description)) : ?>
					<p id="<?php echo $description_id; ?>" class="description">
						<?php echo $this->description; ?>
					</p>
				<?php endif;
				break;
			case 'textarea':
				?>
				<fieldset>
					<legend<?php if (count($field_controls) < 2) : ?> class="screen-reader-text"<?php endif; ?>><?php echo esc_html($this->label); ?></legend>
					<?php if (!empty($this->description)) : ?>
						<p><label for="<?php echo $input_id; ?>"><?php echo $this->description; ?></label></p>
					<?php endif; ?>
					<p>
						<textarea
							id="<?php echo $input_id; ?>"
							name="<?php echo $input_name; ?>"
							<?php echo $this->input_attrs(); ?>><?php echo $value; ?></textarea>
					</p>
				</fieldset>
				<?php
				break;
			default:
				$datalist_id = esc_attr("{$this->id}-datalist");
				if (count($field_controls) > 1 && !empty($this->label)) : ?>
					<p><label for="<?php echo $input_id; ?>"><?php echo esc_html($this->label); ?></label></p>
				<?php endif; ?>
				<input
					id="<?php echo $input_id; ?>"
					type="<?php echo esc_attr($this->type); ?>"
					value="<?php echo esc_attr($value); ?>"
					name="<?php echo $input_name; ?>"
					<?php echo $describedby_attr; ?>
					<?php if (count($this->choices)) : ?>list="<?php echo $datalist_id; ?>"<?php endif; ?>
					<?php echo $this->input_attrs(); ?>
				/>
				<?php
				if (count($this->choices)) : ?>
					<datalist id="<?php echo $datalist_id; ?>">
						<?php foreach ($this->choices as $label) : ?>
							<option value="<?php echo esc_attr($label); ?>"/>
						<?php endforeach; ?>
					</datalist>
				<?php endif; ?>
				<?php
				if (!empty($this->description)) : ?>
					<p id="<?php echo $description_id; ?>" class="description">
						<?php echo $this->description; ?>
					</p>
				<?php endif;
				break;
		}
	}

	/**
	 * Sanitize the data to be saved in the setting.
	 * @param $data
	 * @return mixed
	 * @since 1.0.0
	 */
	public function sanitize($data)
	{
		$data = call_user_func($this->sanitize_callback, $data);
		return $data;
	}
}
