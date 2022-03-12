<?php

/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Util\Paypal;

use JNews\PAY_WRITER\Dashboard\Generate_Stats;
use JNews\PAY_WRITER\Util\Paypal\Core\Http_Exception;
use JNews\PAY_WRITER\Util\Paypal\Core\Jeg_Encoder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Paypal_Payout {

	/**
	 * Instance of Paypal_Payout
	 *
	 * @var Paypal_Payout
	 */
	private static $instance;

	/**
	 * @var string
	 */
	private $client_id;

	/**
	 * @var string
	 */
	private $client_secret;

	/**
	 * @var string
	 */
	private $paypal_endpoint;

	/**
	 * @var array
	 */
	private $items = array();

	/**
	 * @var array
	 */
	private $manual_items = array();

	/**
	 * @var string
	 */
	private $currency = '';

	/**
	 * @var Jeg_Encoder
	 */
	private $encoder;

	/**
	 * Singleton page of Paypal_Payout class
	 *
	 * @return Paypal_Payout
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Paypal_Payout Constructor
	 */
	private function __construct() {
		$this->encoder                 = new Jeg_Encoder();
		$this->client_id               = JNews_Pay_Writer()->options['paypal']['clientid'];
		$this->client_secret           = JNews_Pay_Writer()->options['paypal']['clientsecret'];
		$this->paypal_endpoint         = JNews_Pay_Writer()->options['paypal']['sandbox'] ? 'https://api-m.sandbox.paypal.com/' : 'https://api-m.paypal.com/';
		JNews_Pay_Writer()->paypal_ipn = new Paypal_IPN( JNews_Pay_Writer()->options['paypal']['sandbox'], JNews_Pay_Writer()->options['paypal']['receiveremail'] );
		add_action( 'wp_ajax_create_bulk_payout', array( $this, 'create_bulk_payout' ) );

		add_action( 'jnews_ajax_validate_paypal_account', array( $this, 'validate_paypal_account' ) );
		add_action( 'wp_ajax_validate_paypal_account', array( $this, 'validate_paypal_account' ) );
	}

	/**
	 * Validate Paypal Account
	 *
	 * @return mixed
	 */
	public function validate_paypal_account() {

		if ( isset( $_POST['data'] ) ) {

			$validate = wp_remote_post(
				( JNews_Pay_Writer()->options['paypal']['sandbox'] ? 'https://www.sandbox.paypal.com/' : 'https://www.paypal.com/' ) . 'cgi-bin/webscr',
				array(
					'User-Agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)',
					'body'       => array(
						'cmd'           => '_donations',
						'business'      => $_POST['data'],
						'currency_code' => 'USD',
						'amount'        => '1',
						'lc'            => 'en_US',
					),
				)
			);

			if ( ! is_wp_error( $validate ) ) {
				$result = strpos( $validate['body'], 'og:title' ) !== false ? true : false;
				if ( ! empty( $result ) ) {
					wp_send_json( $result );
				}
			}
		}
		wp_send_json( false );
		die();
	}

	/**
	 * Handle create bulk payout
	 *
	 * @return mixed
	 */
	public function create_bulk_payout() {
		$stats = null;
		if ( isset( $_POST['data'] ) ) {
			$this->currency = JNews_Pay_Writer()->options['payment']['payment_currency'];
			$data           = $_POST['data'];
			if ( isset( $data['data'] ) && isset( $data['tend'] ) && isset( $data['tstart'] ) ) {
				$users        = array();
				$users_detail = array();
				foreach ( $data['data'] as $data_key => $data_value ) {
					if ( floatval( $data_value['total'] ) > 0 ) {
						if ( ! empty( $data_value['address'] ) || $data['payout_type'] === 'manual' ) {
							$users[] = $data['data'][ $data_key ]['id'];
							$users_detail[ $data['data'][ $data_key ]['id'] ] = array(
								'total'          => $data_value['total'],
								'paypal_account' => $data_value['address'],
							);
						}
					}
				}
				JNews_Pay_Writer()->settings['current_page'] = 'stats_general';
				$stats                                       = Generate_Stats::produce_stats( strtotime( $data['tstart'] ), strtotime( $data['tend'] ), $users, true, false );
				if ( ! is_wp_error( $this->stats ) ) {
					foreach ( $users_detail as $user_id => $user_data ) {
						$item = array();
						if ( isset( $stats['formatted_stats']['stats'][ (int) $user_id ] ) ) {
							$item = $stats['formatted_stats']['stats'][ (int) $user_id ];
							if ( floatval( $item['author_unpaid'] ) === floatval( $user_data['total'] ) ) {
								if ( $item['author_paypal_account'] === $user_data['paypal_account'] && $data['payout_type'] === 'paypal' ) {
									$this->add_item(
										array(
											'id'      => $item['author_id'],
											'address' => $item['author_paypal_account'],
											'total'   => floatval( $item['author_unpaid'] ),
										)
									);
								} else {
									$this->add_manual_item(
										array(
											'id'      => $item['author_id'],
											'address' => $item['author_name'],
											'total'   => floatval( $item['author_unpaid'] ),
										)
									);
								}
							}
						}
					}
				}
			}
		}
		$processed_payout = $this->process_payout();
		if ( ! $processed_payout['error'] && null !== $stats ) {
			$payments           = $this->parse_payment_data( $stats['raw_stats'], $processed_payout );
			$async_request_args = array(
				'action_type' => 'update_payments',
				'payments'    => wp_slash( $payments ),
			);
			JNews_Pay_Writer()->database->async_request( $async_request_args );
		}
		wp_send_json( $processed_payout );
	}

	/**
	 * Parse raw payment data
	 *
	 * @param array $raw_stats
	 * @param array $processed_payout
	 *
	 * @return array
	 */
	private function parse_payment_data( $raw_stats, $processed_payout ) {
		$payments         = array();
		$transaction_data = array();
		if ( isset( $processed_payout['response'] ) ) {
			// process paypal transaction data
			$response = $processed_payout['response']->result;
			foreach ( $response->items as $item ) {
				$user_id = explode( '_', $item->payout_item->sender_item_id );
				// make $transaction_data array with key of user_id and value of sender item id and transaction status
				$transaction_data[ $user_id[2] ] = array(
					'sender_item_id'     => $item->payout_item->sender_item_id,
					'transaction_status' => $item->transaction_status,
				);
			}
		} else {
			// process manual transaction data
			foreach ( $processed_payout['payout_list'] as $item ) {
				$transaction_data[ $item['user_id'] ] = array(
					'transaction_status' => $item['transaction_status'],
					'sender_item_id'     => $item['sender_item_id'],
				);
			}
		}
		foreach ( $raw_stats as $raw_id => $raw_data ) {
			foreach ( $raw_data as $post_id => $post_data ) {
				if ( isset( $post_data->ID ) ) {
					if ( isset( $transaction_data[ $post_data->post_author ] ) && $post_data->jpwt_payment['post_unpaid'] > 0 ) {
						array_push(
							$payments,
							array(
								'postid'         => intval( $post_data->ID ),
								'authorid'       => intval( $post_data->post_author ),
								'sender_item_id' => sanitize_text_field( $transaction_data[ $post_data->post_author ]['sender_item_id'] ),
								'payment_status' => strtoupper( sanitize_key( $transaction_data[ $post_data->post_author ]['transaction_status'] ) ),
								'amount'         => $post_data->jpwt_payment['post_unpaid'],
							)
						);
					}
				}
			}
		}
		return $payments;
	}

	/**
	 * Add item of manual payout
	 *
	 * @param array $user_item
	 */
	private function add_manual_item( $user_item ) {
		array_push(
			$this->manual_items,
			array(
				'user_id'            => $user_item['id'],
				'sender_item_id'     => 'jpwt_item_' . $user_item['id'] . '_' . current_time( 'timestamp' ),
				'transaction_status' => 'COMPLETED',
				'receiver'           => $user_item['address'],
				'amount'             => $user_item['total'],
				'amount_currency'    => $this->currency,
				'fee'                => 0,
				'fee_currency'       => $this->currency,
				'item_error'         => '',
			)
		);
	}

	/**
	 * Add item
	 *
	 * @param array $user_item
	 */
	private function add_item( $user_item ) {
		array_push(
			$this->items,
			array(
				'recipient_type'        => 'EMAIL',
				'amount'                => array(
					'value'    => $user_item['total'],
					'currency' => $this->currency,
				),
				'sender_item_id'        => 'jpwt_item_' . $user_item['id'] . '_' . current_time( 'timestamp' ),
				'receiver'              => $user_item['address'],
				'notification_language' => str_replace( '_', '-', get_locale() ),
			)
		);
	}

	/**
	 * Prepare headers request
	 *
	 * @param array $headers
	 *
	 * @return array
	 */
	private function prepare_headers( $headers ) {
		return array_change_key_case( $headers );
	}

	/**
	 * Parse Response
	 *
	 * @param \WP_Error|array $response
	 *
	 * @return object
	 * @throws Http_Exception
	 */
	private function parse_response( $response ) {
		$headers = array();
		if ( ! is_wp_error( $response ) ) {
			$status_code   = $response['response']['code'];
			$body          = $response['body'];
			$headers       = $response['headers']->getAll();
			$http_response = (object) array(
				'status_code' => $status_code,
				'headers'     => $headers,
			);
			if ( $status_code >= 200 && $status_code < 300 ) {
				$http_response->result = null;

				if ( ! empty( $body ) ) {
					try {
						$http_response->result = $this->encoder->deserialize_response( $body, $this->prepare_headers( $headers ) );
					} catch ( \Exception $e ) {
						throw new Http_Exception( $e->getMessage(), $status_code, $headers );
					}
				}

				return $http_response;

			}

			throw new Http_Exception( $body, $status_code, $headers );
		}

		throw new Http_Exception( $response->get_error_message(), 404, $headers );
	}

	/**
	 * Response handler
	 *
	 * @param \WP_Error|array $request
	 *
	 * @return array
	 */
	private function response( $request ) {
		$error    = false;
		$response = array(
			'status_code' => '',
			'headers'     => '',
			'message'     => '',
		);
		try {
			$response = $this->parse_response( $request );
		} catch ( Http_Exception $http_exception ) { // @codingStandardsIgnoreLine.
			$error                   = true;
			$response['status_code'] = $http_exception->status_code;
			$response['headers']     = $http_exception->headers;
			$response['message']     = $http_exception->getMessage();
		} catch ( \Exception $exception ) { // @codingStandardsIgnoreLine.
			$error               = true;
			$response['message'] = $exception->getMessage();
		}
		if ( ! is_object( $response ) ) {
			if ( function_exists( 'jeg_is_json' ) && jeg_is_json( $response['message'] ) ) {
				$response['message'] = json_decode( $response['message'] );
			}
		}

		return array(
			'error'    => $error,
			'response' => $response,
		);
	}

	/**
	 * Handler process payout
	 *
	 * @return array\object
	 */
	private function process_payout() {
		$response = array(
			'error'    => true,
			'response' => array(
				'message' => esc_html__( 'Items is null, Please make sure user has been set their paypal account or they have unpaid payment', 'jnews-pay-writer' ),
			),
		);
		if ( ! empty( $this->items ) ) {
			$data     = array(
				'sender_batch_header' => array(
					'sender_batch_id' => 'jpwt_batch_' . current_time( 'timestamp' ),
					'email_subject'   => JNews_Pay_Writer()->options['paypal']['payout_msg_subject'],
					'email_message'   => JNews_Pay_Writer()->options['paypal']['payout_msg'],
				),
				'items'               => $this->items,
			);
			$request  = wp_remote_request(
				$this->paypal_endpoint . 'v1/payments/payouts',
				array(
					'method'  => 'POST',
					'headers' => array(
						'content-type'  => 'application/json',
						'Authorization' => 'Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret ),
					),
					'timeout' => 60,
					'body'    => json_encode( $data, true ),
				)
			);
			$response = $this->response( $request );
			$response = $this->get_payout_detail( $response );
		} elseif ( ! empty( $this->manual_items ) ) {
			$response = array(
				'error'       => false,
				'payout_list' => $this->manual_items,
			);
		}
		return $response;
	}

	/**
	 * @param array|object $payout_response
	 *
	 * @return array|object
	 */
	public function get_payout_detail( $payout_response ) {
		if ( ! $payout_response['error'] ) {
			$batch_id = $payout_response['response']->result->batch_header->payout_batch_id;
			$request  = wp_remote_request(
				$this->paypal_endpoint . 'v1/payments/payouts/' . $batch_id,
				array(
					'method'  => 'GET',
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret ),
					),
				)
			);
			$response = $this->response( $request );
			if ( ! $response['error'] ) {
				$response['payout_list'] = array();
				foreach ( $response['response']->result->items as $item ) {
					$detail           = array(
						'transaction_status' => $item->transaction_status,
						'receiver'           => $item->payout_item->receiver,
						'amount'             => $item->payout_item->amount->value,
						'amount_currency'    => $item->payout_item->amount->currency,
						'fee'                => $item->payout_item_fee->value,
						'fee_currency'       => $item->payout_item_fee->currency,
						'item_error'         => isset( $item->errors ) && isset( $item->errors->message ) ? $item->errors->message : '',
					);
					$detail['amount'] = Generate_Stats::format_payment( $detail['amount'], $detail['amount_currency'] );
					$detail['fee']    = Generate_Stats::format_payment( $detail['fee'], $detail['fee_currency'] );
					array_push(
						$response['payout_list'],
						$detail
					);
				}
			}
		} else {
			$payout_response['error']       = false;
			$payout_response['payout_list'] = array();
			foreach ( $this->items as $item ) {
				$detail = array(
					'transaction_status' => 'FAILED',
					'receiver'           => $item['receiver'],
					'amount'             => $item['amount']['value'],
					'amount_currency'    => $item['amount']['currency'],
					'fee'                => 0,
					'fee_currency'       => $item['amount']['currency'],
					'item_error'         => $payout_response['response']['message'],
				);
				if ( isset( $detail['item_error']->message ) ) {
					$detail['item_error'] = $detail['item_error']->message;
				}
				if ( isset( $detail['item_error']->error_description ) ) {
					$detail['item_error'] = $detail['item_error']->error_description;
				}
				$detail['amount'] = Generate_Stats::format_payment( $detail['amount'], $detail['amount_currency'] );
				$detail['fee']    = Generate_Stats::format_payment( $detail['fee'], $detail['fee_currency'] );
				array_push(
					$payout_response['payout_list'],
					$detail
				);
			}
			$response = $payout_response;
		}
		return $response;
	}
}
