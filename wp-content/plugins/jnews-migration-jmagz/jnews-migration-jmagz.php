<?php
/*
	Plugin Name: JNews - Migration JMagz
	Plugin URI: http://jegtheme.com/
	Description: Content migration plugin from JMagz Theme into JNews Theme
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_MIGRATION_JMAGZ' ) or define( 'JNEWS_MIGRATION_JMAGZ', 'jnews-migration-jmagz' );
defined( 'JNEWS_MIGRATION_JMAGZ_VERSION' ) or define( 'JNEWS_MIGRATION_JMAGZ_VERSION', '10.0.0' );
defined( 'JNEWS_MIGRATION_JMAGZ_URL' ) or define( 'JNEWS_MIGRATION_JMAGZ_URL', plugins_url( 'jnews-migration-jmagz' ) );
defined( 'JNEWS_MIGRATION_JMAGZ_FILE' ) or define( 'JNEWS_MIGRATION_JMAGZ_FILE', __FILE__ );
defined( 'JNEWS_MIGRATION_JMAGZ_DIR' ) or define( 'JNEWS_MIGRATION_JMAGZ_DIR', plugin_dir_path( __FILE__ ) );

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
add_action( 'after_setup_theme', 'jnews_migration_jmagz_dashboard' );

if ( ! function_exists( 'jnews_migration_jmagz_dashboard' ) ) {
	function jnews_migration_jmagz_dashboard() {
		if ( is_admin() ) {
			require_once 'class.jnews-migration-jmagz-dashboard.php';
			JNews_Migration_JMagz_Dashboard::getInstance();
		}
	}
}

/**
 * Load Migration Class
 */
add_action( 'after_setup_theme', 'jnews_migration_jmagz' );

if ( ! function_exists( 'jnews_migration_jmagz' ) ) {
	function jnews_migration_jmagz() {
		require_once 'class.jnews-migration-jmagz.php';
		JNews_Migration_JMagz::getInstance();
	}
}

/**
 * Load Shortcode Class
 */
add_action( 'plugins_loaded', 'jnews_migration_jmagz_shortcode' );

if ( ! function_exists( 'jnews_migration_jmagz_shortcode' ) ) {
	function jnews_migration_jmagz_shortcode() {
		require_once 'class.jnews-migration-jmagz-shortcode.php';
		JNews_Migration_JMagz_Shortcode::getInstance();
	}
}

/**
 * Register review category
 */
if ( ! function_exists( 'jnews_review_taxonomy' ) ) {
	add_action( 'init', 'jnews_review_taxonomy' );

	function jnews_review_taxonomy() {
		register_taxonomy(
			'review-category',
			array(),
			array(
				'hierarchical'   => true,
				'label'          => 'Product Categories',
				'singular_label' => 'Product Category',
				'rewrite'        => true,
				'query_var'      => true,
			)
		);

		register_taxonomy(
			'review-brand',
			array( 'post' ),
			array(
				'hierarchical'   => true,
				'label'          => 'Product Brands',
				'singular_label' => 'Product Brand',
				'rewrite'        => true,
				'query_var'      => true,
			)
		);
	}
}

/**
 * Load Text Domain
 */
function jnews_migration_jmagz_load_textdomain() {
	load_plugin_textdomain( JNEWS_MIGRATION_JMAGZ, false, basename( __DIR__ ) . '/languages/' );
}

jnews_migration_jmagz_load_textdomain();
