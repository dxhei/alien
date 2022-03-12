<?php
/*
	Plugin Name: JNews - AMP Extender
	Plugin URI: http://jegtheme.com/
	Description: Extend WordPress AMP to fit with JNews Style
	Version: 10.0.1
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_AMP' ) or define( 'JNEWS_AMP', 'jnews-amp' );
defined( 'JNEWS_AMP_URL' ) or define( 'JNEWS_AMP_URL', plugins_url( JNEWS_AMP ) );
defined( 'JNEWS_AMP_FILE' ) or define( 'JNEWS_AMP_FILE', __FILE__ );
defined( 'JNEWS_AMP_DIR' ) or define( 'JNEWS_AMP_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Get jnews option
 *
 * @param $setting
 * @param $default
 *
 * @return mixed
 */
if ( ! function_exists( 'jnews_get_option' ) ) {
	function jnews_get_option( $setting, $default = null ) {
		$options = get_option( 'jnews_option', array() );
		$value   = $default;

		if ( isset( $options[ $setting ] ) ) {
			$value = $options[ $setting ];
		}

		return $value;
	}
}

/**
 * Load Plugin Class
 */
add_action( 'plugins_loaded', 'jnews_amp' );

if ( ! function_exists( 'jnews_amp' ) ) {
	function jnews_amp() {
		require_once 'class.jnews-amp.php';
		JNews_AMP::getInstance();
	}
}

/**
 * Sanitize AMP Tag
 */
add_filter( 'amp_content_sanitizers', 'jnews_amp_content_sanitize' );

if ( ! function_exists( 'jnews_amp_content_sanitize' ) ) {
	function jnews_amp_content_sanitize( $sanitize_array ) {
		unset( $sanitize_array['AMP_Video_Sanitizer'] );
		unset( $sanitize_array['AMP_Audio_Sanitizer'] );

		require_once 'class.jnews-amp-sanitize-audio.php';
		require_once 'class.jnews-amp-sanitize-video.php';

		$sanitize_array['JNews_AMP_Sanitize_Audio'] = array();
		$sanitize_array['JNews_AMP_Sanitize_Video'] = array();

		return $sanitize_array;
	}
}

/** Print Translation */

if ( ! function_exists( 'jnews_print_translation' ) ) {
	function jnews_print_translation( $string, $domain, $name ) {
		do_action( 'jnews_print_translation', $string, $domain, $name );
	}
}

if ( ! function_exists( 'jnews_print_main_translation' ) ) {
	add_action( 'jnews_print_translation', 'jnews_print_main_translation', 10, 2 );

	function jnews_print_main_translation( $string, $domain ) {
		call_user_func_array( 'esc_html_e', array( $string, $domain ) );
	}
}

/** Return Translation */

if ( ! function_exists( 'jnews_return_translation' ) ) {
	function jnews_return_translation( $string, $domain, $name, $escape = true ) {
		return apply_filters( 'jnews_return_translation', $string, $domain, $name, $escape );
	}
}

if ( ! function_exists( 'jnews_return_main_translation' ) ) {
	add_filter( 'jnews_return_translation', 'jnews_return_main_translation', 10, 4 );

	function jnews_return_main_translation( $string, $domain, $name, $escape = true ) {
		if ( $escape ) {
			return call_user_func_array( 'esc_html__', array( $string, $domain ) );
		} else {
			return call_user_func_array( '__', array( $string, $domain ) );
		}
	}
}

/**
 * Load Text Domain
 */

function jnews_amp_load_textdomain() {
	load_plugin_textdomain( JNEWS_AMP, false, basename( __DIR__ ) . '/languages/' );
}

jnews_amp_load_textdomain();
