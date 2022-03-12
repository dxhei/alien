<?php
/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Util;

use JNews\PAY_WRITER\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * JNews Pay Writer Paypal
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */
class JPWT_API {
	/**
	 * @var JPWT_API
	 */
	private static $instance;

	/**
	 * @return JPWT_API
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __construct() {
		$this->setup_hook();
	}

	public function setup_hook() {
		add_action( 'init', array( $this, 'add_endpoint' ), 0 );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		add_action( 'parse_request', array( $this, 'handle_api_requests' ), 0 );
	}

	/**
	 * WC API for payment gateway IPNs, etc.
	 *
	 * @since 2.0
	 */
	public static function add_endpoint() {
		add_rewrite_endpoint( 'jpwt-api', EP_ALL );
	}

	/**
	 * Add new query vars.
	 *
	 * @since 2.0
	 * @param array $vars Query vars.
	 * @return string[]
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'jpwt-api';
		return $vars;
	}

	/**
	 * API request - Trigger any API requests.
	 *
	 * @since   2.0
	 * @version 2.4
	 */
	public function handle_api_requests() {
		global $wp;

		if ( ! empty( $_GET['jpwt-api'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$wp->query_vars['jpwt-api'] = sanitize_key( wp_unslash( $_GET['jpwt-api'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		// jpwt-api endpoint requests.
		if ( ! empty( $wp->query_vars['jpwt-api'] ) ) {

			// Buffer, we won't want any output here.
			ob_start();

			// No cache headers.
			Helper::nocache_headers();

			// Clean the API request.
			$api_request = strtolower( Helper::jpwt_clean( $wp->query_vars['jpwt-api'] ) );

			// Trigger generic action before request hook.
			do_action( 'jpwt_api_request', $api_request );

			// Is there actually something hooked into this API request? If not trigger 400 - Bad request.
			status_header( has_action( 'jpwt_api_' . $api_request ) ? 200 : 400 );

			// Trigger an action which plugins can hook into to fulfill the request.
			do_action( 'jpwt_api_' . $api_request );

			// Done, clear buffer and exit.
			ob_end_clean();
			die( '-1' );
		}
	}
}
