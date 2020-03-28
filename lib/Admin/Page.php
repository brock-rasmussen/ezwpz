<?php

namespace EZWPZ\Admin;

class Page
{
	/**
	 * @see add_menu_page()
	 */
	public $page_title = '';
	public $menu_title = '';
	public $capability = 'manage_options';
	public $id; // $menu_slug
	public $icon_url = '';
	public $position = null;

	/**
	 * @see add_submenu_page()
	 */
	public $parent_slug = '';

	/**
	 * Customize the submenu title of the toplevel page if it exists.
	 * @var string
	 * @since 1.0.0
	 */
	public $submenu_title = '';

	/**
	 * Save the hook suffix of the page returned when it is registered
	 * @var string
	 * @since 1.0.0
	 */
	public $hook_suffix = '';

	/**
	 * Constructor.
	 * @param string $id
	 * @param array $args ;
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
		add_action('admin_menu', [$this, 'init']);
	}

	/**
	 * Destructor.
	 * @since 1.0.0
	 */
	public function __destruct()
	{
		remove_action('admin_menu', [$this, 'init']);
	}

	/**
	 * Add page to menu.
	 * @since 1.0.0
	 */
	public function init()
	{
		if (!empty($this->parent_slug))
			$this->hook_suffix = add_submenu_page($this->parent_slug, $this->page_title, $this->menu_title, $this->capability, $this->id, [$this, 'render']);
		else
			$this->hook_suffix = add_menu_page($this->page_title, $this->menu_title, $this->capability, $this->id, [$this, 'render'], $this->icon_url, $this->position);

		if (!empty($this->submenu_title) && empty($this->parent_slug))
			add_submenu_page($this->id, $this->page_title, $this->submenu_title, $this->capability, $this->id, [$this, 'render']);

		add_action("load-{$this->hook_suffix}", [$this, 'load']);
	}

	/**
	 * Add action that can be tied into for page load.
	 * @since 1.0.0
	 */
	public function load()
	{
		do_action("ezwpz_admin_page-{$this->id}");
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	/**
	 * Add action that can be tied into to add scripts/styles for this page.
	 * @since 1.0.0
	 */
	public function enqueue_scripts($hook_suffix)
	{
		do_action("ezwpz_admin_page_enqueue-{$this->id}");
	}

	/**
	 * Render the page.
	 * @since 1.0.0
	 */
	public function render()
	{
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form method="post" action="/wp-admin/options.php">
				<?php
				settings_fields($this->id);
				do_settings_sections($this->id);

				ob_start();
				do_settings_fields($this->id, 'default');
				$default_fields = ob_get_clean();
				?>
				<?php if (!empty($default_fields)) : ?>
					<table class="form-table">
						<?php echo $default_fields; ?>
					</table>
				<?php endif; ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
