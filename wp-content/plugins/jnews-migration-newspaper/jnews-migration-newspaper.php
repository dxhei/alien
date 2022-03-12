<?php
/*
	Plugin Name: JNews - Migration Newspaper
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from Newspaper Theme into JNews Theme
	Version: 10.0.1
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_NEWSPAPER' ) or define( 'JNEWS_MIGRATION_NEWSPAPER', 'jnews-migration-newspaper' );
defined( 'JNEWS_MIGRATION_NEWSPAPER_VERSION' ) or define( 'JNEWS_MIGRATION_NEWSPAPER_VERSION', '10.0.1' );
defined( 'JNEWS_MIGRATION_NEWSPAPER_URL' ) or define( 'JNEWS_MIGRATION_NEWSPAPER_URL', plugins_url( 'jnews-migration-newspaper' ) );
defined( 'JNEWS_MIGRATION_NEWSPAPER_FILE' ) or define( 'JNEWS_MIGRATION_NEWSPAPER_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_NEWSPAPER_DIR' ) or define( 'JNEWS_MIGRATION_NEWSPAPER_DIR', plugin_dir_path( __FILE__ ) );

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
add_action( 'after_setup_theme', 'jnews_migration_newspaper_dashboard' );

if ( ! function_exists( 'jnews_migration_newspaper_dashboard' ) ) {
	function jnews_migration_newspaper_dashboard() {
		if ( is_admin() ) {
			require_once 'class.jnews-migration-newspaper-dashboard.php';
			JNews_Migration_Newspaper_Dashboard::getInstance();
		}
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_newspaper' );

if ( ! function_exists( 'jnews_migration_newspaper' ) ) {
	function jnews_migration_newspaper() {
		require_once 'class.jnews-migration-newspaper.php';
		JNews_Migration_Newspaper::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'jnews_migration_newspaper_shortcode' );

if ( ! function_exists( 'jnews_migration_newspaper_shortcode' ) ) {
	function jnews_migration_newspaper_shortcode() {
		require_once 'class.jnews-migration-newspaper-shortcode.php';
		JNews_Migration_Newspaper_Shortcode::getInstance();
	}
}

/**
 * Load Text Domain
 */
function jnews_migration_newspaper_load_textdomain() {
	load_plugin_textdomain( JNEWS_MIGRATION_NEWSPAPER, false, basename( __DIR__ ) . '/languages/' );
}

jnews_migration_newspaper_load_textdomain();
