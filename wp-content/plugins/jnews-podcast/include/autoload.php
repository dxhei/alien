<?php
/**
 * @author Jegtheme
 */

spl_autoload_register(
	function ( $class ) {
		$prefix   = 'JNEWS_PODCAST\\';
		$base_dir = JNEWS_PODCAST_CLASSPATH;
		$len      = strlen( $prefix );

		if ( 0 !== strncmp( $prefix, $class, $len ) ) {
			return;
		}

		$relative_class = substr( $class, $len );

		$class_path     = explode( '\\', $relative_class );
		$relative_class = array_pop( $class_path );
		$class_path     = strtolower( implode( '/', $class_path ) );

		$class_name = 'class-' . $relative_class . '.php';
		$class_name = str_replace( '_', '-', $class_name );
		$file       = rtrim( $base_dir, '/' ) . '/' . $class_path . '/' . strtolower( $class_name );

		if ( is_link( $file ) ) {
			$file = readlink( $file );
		}

		if ( is_file( $file ) ) {
			require $file;
		}
	}
);
