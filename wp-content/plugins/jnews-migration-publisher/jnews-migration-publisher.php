<?php
/*
	Plugin Name: JNews - Migration Publisher
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from Publisher Theme into JNews Theme
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_PUBLISHER' ) or define( 'JNEWS_MIGRATION_PUBLISHER', 'jnews-migration-publisher' );
defined( 'JNEWS_MIGRATION_PUBLISHER_VERSION' ) or define( 'JNEWS_MIGRATION_PUBLISHER_VERSION', '10.0.0' );
defined( 'JNEWS_MIGRATION_PUBLISHER_URL' ) or define( 'JNEWS_MIGRATION_PUBLISHER_URL', plugins_url( 'jnews-migration-publisher' ) );
defined( 'JNEWS_MIGRATION_PUBLISHER_FILE' ) or define( 'JNEWS_MIGRATION_PUBLISHER_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_PUBLISHER_DIR' ) or define( 'JNEWS_MIGRATION_PUBLISHER_DIR', plugin_dir_path( __FILE__ ) );

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
add_action( 'after_setup_theme', 'jnews_migration_publisher_dashboard' );

if ( ! function_exists( 'jnews_migration_publisher_dashboard' ) ) {
	function jnews_migration_publisher_dashboard() {
		if ( is_admin() ) {
			require_once 'class.jnews-migration-publisher-dashboard.php';
			JNews_Migration_Publisher_Dashboard::getInstance();
		}
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_publisher' );

if ( ! function_exists( 'jnews_migration_publisher' ) ) {
	function jnews_migration_publisher() {
		require_once 'class.jnews-migration-publisher.php';
		JNews_Migration_Publisher::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'jnews_migration_publisher_shortcode' );

if ( ! function_exists( 'jnews_migration_publisher_shortcode' ) ) {
	function jnews_migration_publisher_shortcode() {
		require_once 'class.jnews-migration-publisher-shortcode.php';
		JNews_Migration_Publisher_Shortcode::getInstance();
	}
}

/**
 * Load Text Domain
 */
function jnews_migration_publisher_load_textdomain() {
	load_plugin_textdomain( JNEWS_MIGRATION_PUBLISHER, false, basename( __DIR__ ) . '/languages/' );
}

jnews_migration_publisher_load_textdomain();
