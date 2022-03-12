<?php
/**
 * Cache Stats
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */

namespace JNews\PAY_WRITER\Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Cache Stats
 *
 * @package JNews\PAY_WRITER\Dashboard
 */
class Cache_Stats {
	public static function get_stats_incrementor( $refresh = false ) {
		$incrementor_key   = JNews_Pay_Writer()->settings['option_stats_cache_incrementor'];
		$incrementor_value = get_option( $incrementor_key );

		if ( $incrementor_value === false || $refresh === true ) {
			$incrementor_value = time();
			update_option( $incrementor_key, $incrementor_value );
		}

		return $incrementor_value;
	}

	static function get_post_stats( $post_id ) {
		$cache_salt = self::get_stats_incrementor();

		if ( JNews_Pay_Writer()->options['display']['enable_post_stats_caching'] ) {
			return wp_cache_get( 'jpwt_stats_post_ID-' . $post_id . '-' . $cache_salt, 'jpwt_stats' );
		} else {
			return false;
		}
	}

	static function set_post_stats( $post_id, $data ) {
		$cache_salt = self::get_stats_incrementor();

		wp_cache_set( 'jpwt_stats_post_ID-' . $post_id . '-' . $cache_salt, $data, 'jpwt_stats', 86400 );
	}

	static function clear_post_stats( $post_id ) {
		wp_cache_delete( 'jpwt_stats_post_ID-' . $post_id . '-' . self::get_stats_incrementor(), 'jpwt_stats' );
	}

	static function clear_stats() {
		self::get_stats_incrementor( true );
	}
}
