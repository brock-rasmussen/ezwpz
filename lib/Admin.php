<?php

namespace EZWPZ;

use EZWPZ\Core\Singleton;
use EZWPZ\Admin\HelpTab;
use EZWPZ\Admin\Page;
use EZWPZ\Admin\Section;
use EZWPZ\Admin\Field;
use EZWPZ\Admin\Control;
use EZWPZ\Admin\Control\Color;
use EZWPZ\Admin\Control\Posts;
use EZWPZ\Admin\Control\PostTypes;
use EZWPZ\Admin\Control\Taxonomies;
use EZWPZ\Admin\Control\Terms;
use EZWPZ\Admin\Control\Users;

class Admin {
	use Singleton;

	/**
	 * Instances of Page.
	 * @since 1.0.0
	 * @var array
	 */
	protected $pages = [];

	/**
	 * Page types.
	 * @since 1.0.0
	 * @var array
	 */
	protected $page_types = [];

	/**
	 * Instances of HelpTab.
	 * @since 1.0.0
	 * @var array
	 */
	protected $help_tabs = [];

	/**
	 * Instances of Section.
	 * @since 1.0.0
	 * @var array
	 */
	protected $sections = [];

	/**
	 * Section types.
	 * @since 1.0.0
	 * @var array
	 */
	protected $section_types = [];

	/**
	 * Instances of Field.
	 * @since 1.0.0
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Field types.
	 * @since 1.0.0
	 * @var array
	 */
	protected $field_types = [];

	/**
	 * Instances of Controls.
	 * @since 1.0.0
	 * @var array
	 */
	protected $controls = [];

	/**
	 * Control types.
	 * @since 1.0.0
	 * @var array
	 */
	protected $control_types = [];

	/**
	 * Constructor.
	 * @since 1.0.0
	 */
	public function __construct() {
		require_once 'Admin/functions.php';

		$this->page_types    = apply_filters( 'ezwpz_admin_page_types', [] );
		$this->section_types = apply_filters( 'ezwpz_admin_section_types', [] );
		$this->field_types   = apply_filters( 'ezwpz_admin_field_types', [] );
		$this->control_types = apply_filters( 'ezwpz_admin_control_types', [
			'color'      => Color::class,
			'posts'      => Posts::class,
			'post_types' => PostTypes::class,
			'taxonomies' => Taxonomies::class,
			'terms'      => Terms::class,
			'users'      => Users::class,
		] );

		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Add action that plugins and themes can tie into to access the class.
	 * @since 1.0.0
	 */
	public function init() {
		do_action( 'ezwpz_admin', $this );
	}

	/**
	 * Register a page type.
	 *
	 * @param $type
	 * @param $class
	 *
	 * @since 1.0.0
	 */
	public function register_page_type( $type, $class ) {
		$this->page_types[ $type ] = $class;
	}

	/**
	 * Get Page type class.
	 *
	 * @param $type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_page_type_class( $type ) {
		return isset( $this->page_types[ $type ] ) ? $this->page_types[ $type ] : Page::class;
	}

	/**
	 * Add a page.
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @return Page
	 */
	public function add_page( $id, $args = [] ) {
		if ( $id instanceof Page ) {
			$page = $id;
		} else {
			$type  = isset( $args['type'] ) ? $args['type'] : null;
			$class = $this->get_page_type_class( $type );
			$page  = new $class( $id, $args );
		}
		$this->pages[ $page->id ] = $page;

		return $page;
	}

	/**
	 * Get a page.
	 *
	 * @param string $id
	 *
	 * @return bool|Page
	 */
	public function get_page( $id ) {
		return isset( $this->pages[ $id ] ) ? $this->pages[ $id ] : false;
	}

	/**
	 * Remove a page.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function remove_page( $id ) {
		$page = $this->get_page( $id );
		if ( $page ) {
			$page->__destruct();
			unset( $this->pages[ $id ] );

			return true;
		}

		return false;
	}

	/**
	 * Add a help tab.
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @return HelpTab
	 */
	public function add_help_tab( $id, $args = [] ) {
		$help_tab                         = $id instanceof HelpTab ? $id : new HelpTab( $id, $args );
		$this->help_tabs[ $help_tab->id ] = $help_tab;

		return $help_tab;
	}

	/**
	 * Get a help tab.
	 *
	 * @param string $id
	 *
	 * @return bool|HelpTab
	 */
	public function get_help_tab( $id ) {
		return isset( $this->help_tabs[ $id ] ) ? $this->help_tabs[ $id ] : false;
	}

	/**
	 * Remove a help tab.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function remove_help_tab( $id ) {
		$help_tab = $this->get_help_tab( $id );
		if ( $help_tab ) {
			$help_tab->__destruct();
			unset( $this->help_tabs[ $id ] );

			return true;
		}

		return false;
	}

	/**
	 * Register a section type.
	 *
	 * @param $type
	 * @param $class
	 */
	public function register_section_type( $type, $class ) {
		$this->section_types[ $type ] = $class;
	}

	/**
	 * Get Section type class.
	 *
	 * @param $type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_section_type_class( $type ) {
		return isset( $this->section_types[ $type ] ) ? $this->section_types[ $type ] : Section::class;
	}

	/**
	 * Add a section.
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @return Section
	 */
	public function add_section( $id, $args = [] ) {
		if ( $id instanceof Section ) {
			$section = $id;
		} else {
			$class   = isset( $args['type'] ) && isset( $this->section_types[ $args['type'] ] ) ? $this->section_types[ $args['type'] ] : Section::class;
			$section = new $class( $id, $args );
		}
		$this->sections[ $section->id ] = $section;

		return $section;
	}

	/**
	 * Get a section.
	 *
	 * @param string $id
	 *
	 * @return bool|Section
	 */
	public function get_section( $id ) {
		return isset( $this->sections[ $id ] ) ? $this->sections[ $id ] : false;
	}

	/**
	 * Remove a section.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function remove_section( $id ) {
		$section = $this->get_section( $id );
		if ( $section ) {
			$section->__destruct();
			unset( $this->sections[ $id ] );

			return true;
		}

		return false;
	}

	/**
	 * Register a field type.
	 *
	 * @param $type
	 * @param $class
	 */
	public function register_field_type( $type, $class ) {
		$this->field_types[ $type ] = $class;
	}

	/**
	 * Get Field type class.
	 *
	 * @param $type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_field_type_class( $type ) {
		return isset( $this->field_types[ $type ] ) ? $this->field_types[ $type ] : Field::class;
	}

	/**
	 * Add a field.
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @return Field
	 */
	public function add_field( $id, $args = [] ) {
		if ( $id instanceof Field ) {
			$field = $id;
		} else {
			$class = isset( $args['type'] ) && isset( $this->field_types[ $args['type'] ] ) ? $this->field_types[ $args['type'] ] : Field::class;
			$field = new $class( $id, $args );
		}
		$this->fields[ $field->id ] = $field;

		return $field;
	}

	/**
	 * Get a field.
	 *
	 * @param string $id
	 *
	 * @return bool|Field
	 */
	public function get_field( $id ) {
		return isset( $this->fields[ $id ] ) ? $this->fields[ $id ] : false;
	}

	/**
	 * Remove a field.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function remove_field( $id ) {
		$field = $this->get_field( $id );
		if ( $field ) {
			$field->__destruct();
			unset( $this->fields[ $id ] );

			return true;
		}

		return false;
	}

	/**
	 * Register a control type.
	 *
	 * @param $type
	 * @param $class
	 */
	public function register_control_type( $type, $class ) {
		$this->control_types[ $type ] = $class;
	}

	/**
	 * Get Control type class.
	 *
	 * @param $type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_control_type_class( $type ) {
		return isset( $this->control_types[ $type ] ) ? $this->control_types[ $type ] : Control::class;
	}

	/**
	 * Add a control.
	 *
	 * @param string $id
	 * @param array $args
	 *
	 * @return Control
	 */
	public function add_control( $id, $args = [] ) {
		if ( $id instanceof Control ) {
			$control = $id;
		} else {
			$class   = $this->get_control_type_class($args['type']);
			$control = new $class( $id, $args );
		}
		$this->controls[ $control->id ] = $control;

		return $control;
	}

	/**
	 * Get a control.
	 *
	 * @param string $id
	 *
	 * @return bool|Control
	 */
	public function get_control( $id ) {
		return isset( $this->controls[ $id ] ) ? $this->controls[ $id ] : false;
	}

	/**
	 * Remove a control.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function remove_control( $id ) {
		$control = $this->get_control( $id );
		if ( $control ) {
			$control->__destruct();
			unset( $this->controls[ $id ] );

			return true;
		}

		return false;
	}
}
