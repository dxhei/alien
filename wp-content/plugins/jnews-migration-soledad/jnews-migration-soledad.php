<?php
/*
	Plugin Name: JNews - Migration Soledad
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from Soledad Theme into JNews Theme
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_SOLEDAD' ) or define( 'JNEWS_MIGRATION_SOLEDAD', 'jnews-migration-soledad' );
defined( 'JNEWS_MIGRATION_SOLEDAD_VERSION' ) or define( 'JNEWS_MIGRATION_SOLEDAD_VERSION', '10.0.0' );
defined( 'JNEWS_MIGRATION_SOLEDAD_URL' ) or define( 'JNEWS_MIGRATION_SOLEDAD_URL', plugins_url( 'jnews-migration-soledad' ) );
defined( 'JNEWS_MIGRATION_SOLEDAD_FILE' ) or define( 'JNEWS_MIGRATION_SOLEDAD_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_SOLEDAD_DIR' ) or define( 'JNEWS_MIGRATION_SOLEDAD_DIR', plugin_dir_path( __FILE__ ) );

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
add_action( 'after_setup_theme', 'jnews_migration_soledad_dashboard' );

if ( ! function_exists( 'jnews_migration_soledad_dashboard' ) ) {
	function jnews_migration_soledad_dashboard() {
		if ( is_admin() ) {
			require_once 'class.jnews-migration-soledad-dashboard.php';
			JNews_Migration_Soledad_Dashboard::getInstance();
		}
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_soledad' );

if ( ! function_exists( 'jnews_migration_soledad' ) ) {
	function jnews_migration_soledad() {
		require_once 'class.jnews-migration-soledad.php';
		JNews_Migration_Soledad::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'JNews_Migration_Soledad_shortcode' );

if ( ! function_exists( 'JNews_Migration_Soledad_shortcode' ) ) {
	function JNews_Migration_Soledad_shortcode() {
		require_once 'class.jnews-migration-soledad-shortcode.php';
		JNews_Migration_Soledad_Shortcode::getInstance();
	}
}

/**
 * Load Text Domain
 */
function JNews_Migration_Soledad_load_textdomain() {
	load_plugin_textdomain( JNEWS_MIGRATION_SOLEDAD, false, basename( __DIR__ ) . '/languages/' );
}

JNews_Migration_Soledad_load_textdomain();
