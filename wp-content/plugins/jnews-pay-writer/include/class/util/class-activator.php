<?php
/**
 * Activator Class
 *
 * @package jnews-pay-writer
 * @author Jegtheme
 * @since 10.0.0
 */

namespace JNews\PAY_WRITER\Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use JNews\PAY_WRITER\Helper;
/**
 * Class Activator
 */
class Activator {

	/**
	 * Fired when the plugin is activated and check if plugin uses Network Activate.
	 *
	 * @param    bool $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 * @global   object  $wpdb
	 */
	public static function activate( $network_wide ) {
		register_uninstall_hook( JNEWS_PAY_WRITER_FILE, array( 'JNews\PAY_WRITER\Util\Deactivator', 'deactivate' ) );
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			// run activation for each blog in the network
			if ( $network_wide ) {
				$original_blog_id = get_current_blog_id();
				$blogs_ids        = JNews_Pay_Writer()->database->get_blog_ids();

				foreach ( $blogs_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::plugin_activate();
				}

				// switch back to current blog
				switch_to_blog( $original_blog_id );

				return;
			}
		}

		self::plugin_activate();
	}

	/**
	 * On plugin deactivation, do create db tables
	 */
	private static function plugin_activate() {
		if ( wp_get_theme() == 'JNews' || wp_get_theme()->parent() == 'JNews' ) {
			$version = Helper::get_general_option( 'version' );

			if ( ! $version || version_compare( $version, JNEWS_PAY_WRITER_VERSION, '<' ) ) {
				self::do_db_tables();
			}
		}
	}

	/**
	 * Do create db tables
	 */
	private static function do_db_tables() {
		JNews_Pay_Writer()->database->create_table();
		Helper::update_general_option( 'version', JNEWS_PAY_WRITER_VERSION );
	}
}
