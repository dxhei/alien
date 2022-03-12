<?php
/**
 * Deactivator Class
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
 * Class Deactivator
 */
class Deactivator {

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @global  object  wpbd
	 * @param   bool    network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			// Run deactivation for each blog in the network
			if ( $network_wide ) {
				$original_blog_id = get_current_blog_id();
				$blogs_ids        = JNews_Pay_Writer()->database->get_blog_ids();

				foreach ( $blogs_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::plugin_deactivate();
				}

				// Switch back to current blog
				switch_to_blog( $original_blog_id );

				return;
			}
		}

		self::plugin_deactivate();
	}

	/**
	 * On plugin deactivation, disables the shortcode and removes the scheduled task.
	 */
	private static function plugin_deactivate() {
		Helper::update_general_option( 'version', false );
	}
}
