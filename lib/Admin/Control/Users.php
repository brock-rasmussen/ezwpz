<?php

namespace EZWPZ\Admin\Control;

class Users extends Query {
	protected function setup_query() {
		$this->query = wp_parse_args( $this->query, [
			'orderby' => 'display_name',
			'order'   => 'ASC',
		] );
	}

	protected function get_objects() {
		$this->objects = get_users( $this->query );
	}

	protected function get_choices() {
		foreach ( $this->objects as $user ) {
			$this->choices[ $user->ID ] = sprintf( _x( '%1$s (%2$s)', 'user select' ), $user->display_name, $user->user_login );
		}
	}
}
