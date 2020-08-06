<?php

/**
 * Get the controls for a field.
 *
 * @param $page
 * @param $section
 * @param $field
 *
 * @return array
 *
 * @since 1.0.0
 */
function ezwpz_admin_get_controls( $page, $section, $field ) {
	global $ezwpz_settings_controls;

	return isset( $ezwpz_settings_controls[ $page ][ $section ][ $field ] ) ? $ezwpz_settings_controls[ $page ][ $section ][ $field ] : [];
}

/**
 * Set up an admin page using a config object.
 *
 * @param $admin
 * @param $config
 *
 * @since 1.0.0
 */
function ezwpz_admin_config( $admin, $config ) {
	if ( ! is_array( $config ) ) {
		return;
	}

	foreach ( $config as $page_id => $page_args ) {
		if ( ! is_array( $page_args ) ) {
			continue;
		}

		$admin->add_page( $page_id, $page_args );

		if ( array_key_exists( 'sections', $page_args ) ) {
			foreach ( $page_args['sections'] as $section_id => $section_args ) {
				if ( ! is_array( $section_args ) ) {
					continue;
				}

				$section_args['page'] = $page_id;
				$admin->add_section( $section_id, $section_args );

				if ( array_key_exists( 'fields', $section_args ) ) {
					foreach ( $section_args['fields'] as $field_id => $field_args ) {
						if ( ! is_array( $field_args ) ) {
							continue;
						}

						$field_args['page']    = $page_id;
						$field_args['section'] = $section_id;
						$admin->add_field( $field_id, $field_args );

						if ( array_key_exists( 'controls', $field_args ) ) {
							foreach ( $field_args['controls'] as $control_id => $control_args ) {
								if ( ! is_array( $field_args ) ) {
									continue;
								}

								$control_args['page']    = $page_id;
								$control_args['section'] = $section_id;
								$control_args['field']   = $field_id;
								$admin->add_control( $control_id, $control_args );
							}
						}
					}
				}
			}
		}

		if ( array_key_exists( 'help_tabs', $page_args ) ) {
			foreach ( $page_args['help_tabs'] as $help_tab_id => $help_tab_args ) {
				if ( ! is_array( $help_tab_args ) ) {
					continue;
				}

				$help_tab_args['pages'] = [ $page_id ];
				$admin->add_help_tab( $help_tab_id, $help_tab_args );
			}
		}
	}
}
