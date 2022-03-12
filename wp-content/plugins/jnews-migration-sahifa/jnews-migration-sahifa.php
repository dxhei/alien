<?php
/*
	Plugin Name: JNews - Migration Sahifa
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from Sahifa Theme into JNews Theme
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_SAHIFA' ) or define( 'JNEWS_MIGRATION_SAHIFA', 'jnews-migration-sahifa' );
defined( 'JNEWS_MIGRATION_SAHIFA_VERSION' ) or define( 'JNEWS_MIGRATION_SAHIFA_VERSION', '10.0.0' );
defined( 'JNEWS_MIGRATION_SAHIFA_URL' ) or define( 'JNEWS_MIGRATION_SAHIFA_URL', plugins_url( 'jnews-migration-sahifa' ) );
defined( 'JNEWS_MIGRATION_SAHIFA_FILE' ) or define( 'JNEWS_MIGRATION_SAHIFA_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_SAHIFA_DIR' ) or define( 'JNEWS_MIGRATION_SAHIFA_DIR', plugin_dir_path( __FILE__ ) );

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
add_action( 'after_setup_theme', 'jnews_migration_sahifa_dashboard' );

if ( ! function_exists( 'jnews_migration_sahifa_dashboard' ) ) {
	function jnews_migration_sahifa_dashboard() {
		require_once 'class.jnews-migration-sahifa-dashboard.php';
		JNews_Migration_Sahifa_Dashboard::getInstance();
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_sahifa' );

if ( ! function_exists( 'jnews_migration_sahifa' ) ) {
	function jnews_migration_sahifa() {
		require_once 'class.jnews-migration-sahifa.php';
		JNews_Migration_Sahifa::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'jnews_migration_sahifa_shortcode' );

if ( ! function_exists( 'jnews_migration_sahifa_shortcode' ) ) {
	function jnews_migration_sahifa_shortcode() {
		require_once 'class.jnews-migration-sahifa-shortcode.php';
		JNews_Migration_Sahifa_Shortcode::getInstance();
	}
}

/**
 * Load Text Domain
 */
function jnews_migration_sahifa_load_textdomain() {
	 load_plugin_textdomain( JNEWS_MIGRATION_SAHIFA, false, basename( __DIR__ ) . '/languages/' );
}

jnews_migration_sahifa_load_textdomain();
