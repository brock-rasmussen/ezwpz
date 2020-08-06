<?php

namespace EZWPZ\Admin;

use EZWPZ\Admin;

class HelpTab {
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
	public $pages = [];

	/**
	 * Constructor.
	 *
	 * @param string $id
	 * @param array $args ;
	 *
	 * @since 1.0.0
	 */
	public function __construct( $id, $args = [] ) {
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}
		$this->id    = $id;
		$this->pages = (array) $this->pages;

		add_action( 'admin_menu', [ $this, 'init' ], PHP_INT_MAX );
	}

	/**
	 * Destructor.
	 * @since 1.0.0
	 */
	public function __destruct() {
		remove_action( 'admin_menu', [ $this, 'init' ], PHP_INT_MAX );
	}

	/**
	 * Init help tab on page hooks.
	 * @since 1.0.0
	 */
	public function init() {
		foreach ( $this->pages as $page_id ) {
			$page     = Admin::get_instance()->get_page( $page_id );
			$hookname = $page ? get_plugin_page_hookname( $page->id, $page->parent_slug ) : $page_id;
			add_action( "load-{$hookname}", [ $this, 'load' ] );
		}
	}

	/**
	 * Add help tab to page.
	 * @since 1.0.0
	 */
	public function load() {
		$screen = get_current_screen();
		$screen->add_help_tab( [
			'title'    => $this->title,
			'id'       => $this->id,
			'content'  => apply_filters( 'the_content', $this->content ),
			'callback' => $this->callback,
			'priority' => $this->priority,
		] );
	}
}
