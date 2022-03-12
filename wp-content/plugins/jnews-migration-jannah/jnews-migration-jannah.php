<?php
/*
	Plugin Name: JNews - Migration Jannah
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from Jannah Theme into JNews Theme
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_JANNAH' ) or define( 'JNEWS_MIGRATION_JANNAH', 'jnews-migration-jannah' );
defined( 'JNEWS_MIGRATION_JANNAH_VERSION' ) or define( 'JNEWS_MIGRATION_JANNAH_VERSION', '10.0.0' );
defined( 'JNEWS_MIGRATION_JANNAH_URL' ) or define( 'JNEWS_MIGRATION_JANNAH_URL', plugins_url( 'jnews-migration-jannah' ) );
defined( 'JNEWS_MIGRATION_JANNAH_FILE' ) or define( 'JNEWS_MIGRATION_JANNAH_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_JANNAH_DIR' ) or define( 'JNEWS_MIGRATION_JANNAH_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Get jnews option
 *
 * @param $setting
 * @param $default
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
 * Load Plugin Dashboard
 */
add_action( 'after_setup_theme', 'jnews_migration_jannah_dashboard' );

if ( ! function_exists( 'jnews_migration_jannah_dashboard' ) ) {
	function jnews_migration_jannah_dashboard() {
		require_once 'class.jnews-migration-jannah-dashboard.php';
		JNews_Migration_Jannah_Dashboard::getInstance();
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_jannah' );

if ( ! function_exists( 'jnews_migration_jannah' ) ) {
	function jnews_migration_jannah() {
		require_once 'class.jnews-migration-jannah.php';
		JNews_Migration_Jannah::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'jnews_migration_jannah_shortcode' );

if ( ! function_exists( 'jnews_migration_jannah_shortcode' ) ) {
	function jnews_migration_jannah_shortcode() {
		require_once 'class.jnews-migration-jannah-shortcode.php';
		JNews_Migration_Jannah_Shortcode::getInstance();
	}
}

/**
 * Load Text Domain
 */
function jnews_migration_jannah_load_textdomain() {
	 load_plugin_textdomain( JNEWS_MIGRATION_JANNAH, false, basename( __DIR__ ) . '/languages/' );
}

jnews_migration_jannah_load_textdomain();
