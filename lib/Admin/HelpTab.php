<?php

namespace EZWPZ\Admin;

class HelpTab
{
	/**
	 * @see WP_Screen::add_help_tab()
	 */
	public $title = '';
	public $id;
	public $content = '';
	public $callback = '';
	public $priority = 10;

	/**
	 * Pages for help tab.
	 * @var string|array
	 * @since 1.0.0
	 */
	public $page = [];

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
		if (is_array($this->page)) {
			foreach ($this->page as $page) {
				add_action("ezwpz_admin_page-{$page}", [$this, 'init'], $this->priority);
			}
		} elseif (is_string($this->page)) {
			add_action("ezwpz_admin_page-{$this->page}", [$this, 'init'], $this->priority);
		}
	}

	/**
	 * Destructor.
	 * @since 1.0.0
	 */
	public function __destruct()
	{
		if (is_array($this->page)) {
			foreach ($this->page as $page) {
				remove_action("ezwpz_admin_page-{$page}", [$this, 'init'], $this->priority);
			}
		} elseif (is_string($this->page)) {
			remove_action("ezwpz_admin_page-{$this->page}", [$this, 'init'], $this->priority);
		}
	}

	/**
	 * Add help tab to page.
	 * @param string $page_id
	 * @since 1.0.0
	 */
	public function init($page_id)
	{
		if ((is_array($this->page) && in_array($page_id, $this->page)) || (is_string($this->page) && $page_id === $this->page)) {
			$screen = get_current_screen();
			$screen->add_help_tab([
				'title' => $this->title,
				'id' => $this->id,
				'content' => apply_filters('the_content', $this->content),
				'callback' => $this->callback,
				'priority' => $this->priority,
			]);
		}
	}
}
