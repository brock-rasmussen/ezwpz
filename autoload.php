<?php
/**
 * Register autoloader to load plugin dependencies.
 *
 * @since 1.0.0
 */
spl_autoload_register( function ( $class ) {
	$prefix = 'EZWPZ';

	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len !== 0 ) ) {
		return;
	}

	$library        = EZWPZ_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
	$relative_class = substr( $class, $len );
	$file           = $library . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require( $file );
	}

	return;
} );
