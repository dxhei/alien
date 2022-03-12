<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE\Actions;

/**
 * Class Mailchimp
 *
 * @package JNEWS_SUBSCRIBE\Actions
 */
class Mailchimp {

	private $api_base_url     = '';
	private $api_key          = '';
	private $api_request_args = array();

	/**
	 * Mailchimp constructor.
	 *
	 * @param $api_key
	 *
	 * @throws \Exception
	 */
	public function __construct( $api_key ) {
		if ( empty( $api_key ) ) {
			throw new \Exception( 'Invalid API key' );
		}

		// The API key is in format XXXXXXXXXXXXXXXXXXXX-us2 where us2 is the server sub domain for this account.
		$key_parts = explode( '-', $api_key );
		if ( empty( $key_parts[1] ) || 0 !== strpos( $key_parts[1], 'us' ) ) {
			throw new \Exception( 'Invalid API key' );
		}

		$this->api_key          = $api_key;
		$this->api_base_url     = 'https://' . $key_parts[1] . '.api.mailchimp.com/3.0/';
		$this->api_request_args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'user:' . $this->api_key ),
			),
		);
	}

	/**
	 * @param $end_point
	 * @param $data
	 * @param array     $request_args
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function post( $end_point, $data, $request_args = array() ) {
		$this->api_request_args                           += $request_args;
		$this->api_request_args['headers']['Content-Type'] = 'application/json; charset=utf-8';
		$this->api_request_args['body']                    = wp_json_encode( $data );
		$response = wp_remote_post( $this->api_base_url . $end_point, $this->api_request_args );

		if ( is_wp_error( $response ) ) {
			throw new \Exception( 'Mailchimp Error' );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			throw new \Exception( 'Mailchimp Error' );
		}

		return array(
			'code' => (int) wp_remote_retrieve_response_code( $response ),
			'body' => $body,
		);
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function get_lists() {
		$results = $this->query( 'lists?count=999' );

		$lists = array(
			'' => 'Select...',
		);

		if ( ! empty( $results['lists'] ) ) {
			foreach ( $results['lists'] as $list ) {
				$lists[ $list['id'] ] = $list['name'];
			}
		}

		$return_array = array(
			'lists' => $lists,
		);

		return $return_array;
	}

	/**
	 * @param $end_point
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	private function query( $end_point ) {
		$response = wp_remote_get( $this->api_base_url . $end_point, $this->api_request_args );

		if ( is_wp_error( $response ) || 200 != (int) wp_remote_retrieve_response_code( $response ) ) {
			throw new \Exception( 'Mailchimp Error' );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			throw new \Exception( 'Mailchimp Error' );
		}

		return $body;
	}

}
