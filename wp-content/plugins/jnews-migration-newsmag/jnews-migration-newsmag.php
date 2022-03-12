<?php
/*
	Plugin Name: JNews - Migration Newsmag
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from Newsmag Theme into JNews Theme
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_NEWSMAG' ) or define( 'JNEWS_MIGRATION_NEWSMAG', 'jnews-migration-newsmag' );
defined( 'JNEWS_MIGRATION_NEWSMAG_VERSION' ) or define( 'JNEWS_MIGRATION_NEWSMAG_VERSION', '10.0.0' );
defined( 'JNEWS_MIGRATION_NEWSMAG_URL' ) or define( 'JNEWS_MIGRATION_NEWSMAG_URL', plugins_url( 'jnews-migration-newsmag' ) );
defined( 'JNEWS_MIGRATION_NEWSMAG_FILE' ) or define( 'JNEWS_MIGRATION_NEWSMAG_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_NEWSMAG_DIR' ) or define( 'JNEWS_MIGRATION_NEWSMAG_DIR', plugin_dir_path( __FILE__ ) );

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
add_action( 'after_setup_theme', 'jnews_migration_newsmag_dashboard' );

if ( ! function_exists( 'jnews_migration_newsmag_dashboard' ) ) {
	function jnews_migration_newsmag_dashboard() {
		if ( is_admin() ) {
			require_once 'class.jnews-migration-newsmag-dashboard.php';
			JNews_Migration_Newsmag_Dashboard::getInstance();
		}
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_newsmag' );

if ( ! function_exists( 'jnews_migration_newsmag' ) ) {
	function jnews_migration_newsmag() {
		require_once 'class.jnews-migration-newsmag.php';
		JNews_Migration_Newsmag::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'jnews_migration_newsmag_shortcode' );

if ( ! function_exists( 'jnews_migration_newsmag_shortcode' ) ) {
	function jnews_migration_newsmag_shortcode() {
		require_once 'class.jnews-migration-newsmag-shortcode.php';
		JNews_Migration_Newsmag_Shortcode::getInstance();
	}
}

/**
 * Load Text Domain
 */
function jnews_migration_newsmag_load_textdomain() {
	load_plugin_textdomain( JNEWS_MIGRATION_NEWSMAG, false, basename( __DIR__ ) . '/languages/' );
}

jnews_migration_newsmag_load_textdomain();
