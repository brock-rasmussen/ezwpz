<?php

namespace EZWPZ;

use EZWPZ\Admin\HelpTab;
use EZWPZ\Admin\Page;
use EZWPZ\Admin\Section;
use EZWPZ\Admin\Field;
use EZWPZ\Admin\Control;

class Admin
{
	/**
	 * Instances of Page.
	 * @since 1.0.0
	 * @var array
	 */
	protected $pages = [];

	/**
	 * Instances of HelpTab.
	 * @since 1.0.0
	 * @var array
	 */
	protected $help_tabs = [];

	/**
	 * Instances of Setting.
	 * @since 1.0.0
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Instances of Section.
	 * @since 1.0.0
	 * @var array
	 */
	protected $sections = [];

	/**
	 * Instances of Field.
	 * @since 1.0.0
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Instances of Controls.
	 * @since 1.0.0
	 * @var array
	 */
	protected $controls = [];

	/**
	 *
	 */

	/**
	 * Constructor.
	 * @since 1.0.0
	 */
	public function __construct()
	{
		add_action('init', [$this, 'init']);
	}

	/**
	 * Add action that plugins and themes can tie into to access the class.
	 * @since 1.0.0
	 */
	public function init()
	{
		do_action('ezwpz_admin', $this);
	}

	/**
	 * Add a page.
	 * @param string $id
	 * @param array $args
	 * @return Page
	 */
	public function add_page($id, $args = [])
	{
		$page = $id instanceof Page ? $id : new Page($id, $args);
		$this->pages[$page->id] = $page;
		return $page;
	}

	/**
	 * Get a page.
	 * @param string $id
	 * @return bool|Page
	 */
	public function get_page($id)
	{
		return isset($this->pages[$id]) ? $this->pages[$id] : false;
	}

	/**
	 * Remove a page.
	 * @param string $id
	 * @return bool
	 */
	public function remove_page($id)
	{
		$page = $this->get_page($id);
		if ($page) {
			$page->__destruct();
			unset($this->pages[$id]);
			return true;
		}
		return false;
	}

	/**
	 * Add a help tab.
	 * @param string $id
	 * @param array $args
	 * @return HelpTab
	 */
	public function add_help_tab($id, $args = [])
	{
		$help_tab = $id instanceof HelpTab ? $id : new HelpTab($id, $args);
		$this->help_tabs[$help_tab->id] = $help_tab;
		return $help_tab;
	}

	/**
	 * Get a help tab.
	 * @param string $id
	 * @return bool|HelpTab
	 */
	public function get_help_tab($id)
	{
		return isset($this->help_tabs[$id]) ? $this->help_tabs[$id] : false;
	}

	/**
	 * Remove a help tab.
	 * @param string $id
	 * @return bool
	 */
	public function remove_help_tab($id)
	{
		$help_tab = $this->get_help_tab($id);
		if ($help_tab) {
			$help_tab->__destruct();
			unset($this->help_tabs[$id]);
			return true;
		}
		return false;
	}

	/**
	 * Add a section.
	 * @param string $id
	 * @param array $args
	 * @return Section
	 */
	public function add_section($id, $args = [])
	{
		$section = $id instanceof Section ? $id : new Section($id, $args);
		$this->sections[$section->id] = $section;
		return $section;
	}

	/**
	 * Get a section.
	 * @param string $id
	 * @return bool|Section
	 */
	public function get_section($id)
	{
		return isset($this->sections[$id]) ? $this->sections[$id] : false;
	}

	/**
	 * Remove a section.
	 * @param string $id
	 * @return bool
	 */
	public function remove_section($id)
	{
		$section = $this->get_section($id);
		if ($section) {
			$section->__destruct();
			unset($this->sections[$id]);
			return true;
		}
		return false;
	}

	/**
	 * Add a field.
	 * @param string $id
	 * @param array $args
	 * @return Field
	 */
	public function add_field($id, $args = [])
	{
		$field = $id instanceof Field ? $id : new Field($id, $args);
		$this->fields[$field->id] = $field;

		return $field;
	}

	/**
	 * Get a field.
	 * @param string $id
	 * @return bool|Field
	 */
	public function get_field($id)
	{
		return isset($this->fields[$id]) ? $this->fields[$id] : false;
	}

	/**
	 * Remove a field.
	 * @param string $id
	 * @return bool
	 */
	public function remove_field($id)
	{
		$field = $this->get_field($id);
		if ($field) {
			$field->__destruct();
			unset($this->fields[$id]);
			return true;
		}
		return false;
	}

	/**
	 * Add a control.
	 * @param string $id
	 * @param array $args
	 * @return Control
	 */
	public function add_control($id, $args = [])
	{
		$control = $id instanceof Control ? $id : new Control($id, $args);
		$this->controls[$control->id] = $control;
		return $control;
	}

	/**
	 * Get a control.
	 * @param string $id
	 * @return bool|Control
	 */
	public function get_control($id)
	{
		return isset($this->controls[$id]) ? $this->controls[$id] : false;
	}

	/**
	 * Remove a control.
	 * @param string $id
	 * @return bool
	 */
	public function remove_control($id)
	{
		$control = $this->get_control($id);
		if ($control) {
			$control->__destruct();
			unset($this->controls[$id]);
			return true;
		}
		return false;
	}
}
