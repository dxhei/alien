<?php
/**
 * Jeg_Paypal_Api_Http_Client
 *
 * @author jegtheme
 * @since 10.0.0
 */

namespace JNews\PAY_WRITER\Util\Paypal;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Paypal_IPN
 */
class Paypal_IPN {

	/**
	 * Transaction types this class can handle.
	 * TODO: Check another transcation type ex Donation
	 *
	 * @var array transaction types
	 */
	protected $transaction_types = array(
		'masspay', // Batch payout success.
	);

	/**
	 * Paypal_IPN constructor.
	 *
	 * @param string $sandbox Use sandbox or not.
	 * @param string $receiver_email Email to receive IPN from.
	 */
	public function __construct( $sandbox = null, $receiver_email = null ) {
		add_action( 'jpwt_api_paypal', array( $this, 'check_response' ) );
		add_action( 'jpwt-valid-paypal-ipn-request', array( $this, 'valid_response' ) );
		add_action( 'jpwt-forward-paypal-ipn-request', array( $this, 'forward_response' ) );

		$this->receiver_email = $receiver_email;
		$this->sandbox        = $sandbox;
	}

	/**
	 * Check for PayPal IPN Response.
	 */
	public function check_response() {
		if ( ! empty( $_POST ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( $this->validate_ipn() ) {
				$posted = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

				// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
				do_action( 'jpwt-valid-paypal-ipn-request', $posted );
				exit;
			}
			do_action( 'jpwt-forward-paypal-ipn-request', $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			exit;
		}
	}

	/**
	 * Forward IPN Response
	 *
	 * @param array $posted
	 */
	public function forward_response( $posted ) {
		if ( JNews_Pay_Writer()->options['paypal']['forward_ipn_response'] ) {
			$urls = JNews_Pay_Writer()->options['paypal']['forward_ipn_response_urls'];
			if ( ! empty( $urls ) ) {
				foreach ( $urls as $url ) {
					// Send back post vars to paypal.
					$params   = array(
						'body'        => $posted,
						'timeout'     => 1500,
						'httpversion' => '1.1',
						'compress'    => false,
						'decompress'  => false,
					);
					$response = wp_remote_post( $url['url'], $params );
					if ( is_wp_error( $response ) ) {
						error_log( 'forward ipn failed' );
					}
				}
			}
		}
	}

	/**
	 * Check Paypal IPN Validity
	 *
	 * @return boolean
	 */
	public function validate_ipn() {
		if ( isset( $_POST['txn_type'] ) && $this->validate_transaction_type( $_POST['txn_type'] ) ) {
			$validate_ipn        = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$validate_ipn['cmd'] = '_notify-validate';

			// Send back post vars to paypal.
			$params = array(
				'body'        => $validate_ipn,
				'timeout'     => 60,
				'httpversion' => '1.1',
				'compress'    => false,
				'decompress'  => false,
				'user-agent'  => 'JNews - Pay Writer/' . JNEWS_PAY_WRITER_VERSION,
			);

			// Post back to get a response.
			$response = wp_safe_remote_post( $this->sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr', $params );
			if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' ) ) {
				return true;
			}
			return false;
		}
	}

	public function validate_receiver_email( $receiver_email ) {
		if ( strcasecmp( trim( $receiver_email ), trim( $this->receiver_email ) ) !== 0 ) {
			// Email is not the same
		}
	}

	/**
	 * There was a valid response.
	 *
	 * @param array $transaction_details
	 */
	public function valid_response( $transaction_details = null ) {
		$id_list     = array();
		$will_update = array();
		$date        = current_time( 'Y-m-d' );
		$date_time   = current_time( 'Y-m-d H:i:s' );
		foreach ( $transaction_details as $key => $value ) {
			if ( strpos( $key, 'unique_id' ) !== false ) {
				$id = explode( '_', $key );
				array_push( $id_list, $id[2] );
			}
		}

		foreach ( $id_list as $id ) {
			if ( isset( $will_update[ $transaction_details[ 'status_' . $id ] ] ) ) {
				array_push( $will_update[ $transaction_details[ 'status_' . $id ] ], $transaction_details[ 'unique_id_' . $id ] );
			} else {
				$will_update[ $transaction_details[ 'status_' . $id ] ] = array(
					$transaction_details[ 'unique_id_' . $id ],
				);
			}
		}
		$args               = array(
			'payment_date'     => $date,
			'payment_datetime' => $date_time,
		);
		$async_request_args = array(
			'action_type' => 'split_payment_status',
			'args'        => $args,
			'will_update' => $will_update,
		);
		JNews_Pay_Writer()->database->async_request( $async_request_args );
	}

	/**
	 * Return valid transaction types
	 *
	 * @return array
	 * @since 10.0.0
	 */
	public function get_transaction_types() {
		return $this->transaction_types;
	}

	/**
	 * Check for a valid transaction type
	 *
	 * @param string $txn_type
	 *
	 * @return bool
	 * @since 10.0.0
	 */
	public function validate_transaction_type( $txn_type = null ) {
		$flag = false;
		if ( null !== $txn_type ) {
			$txn_type = strtolower( $txn_type );
			if ( in_array( $txn_type, $this->get_transaction_types(), true ) ) {
				$flag = true;
			}
		}

		return $flag;
	}

}
