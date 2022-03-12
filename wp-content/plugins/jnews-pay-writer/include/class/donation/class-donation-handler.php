<?php
/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Donation;

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
class Donation_Handler {
	/**
	 * @var Donation_Handler
	 */
	private static $instance;

	/**
	 * @return Donation_Handler
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Donation_Handler constructor.
	 */
	public function __construct() {
		add_action( 'jpwt_api_donation', array( $this, 'check_response' ) );
		add_filter( 'jnews_post_meta_element_options', array( $this, 'post_meta_element_option' ) );

		add_filter( 'jnews_post_meta_element_render_meta', array( $this, 'post_meta_element_render_meta' ), 11, 3 );
		add_filter( 'jnews_post_meta_element_render_meta_back', array( $this, 'post_meta_element_render_meta_back' ), 11, 4 );
	}

	/**
	 * Render post meta element
	 *
	 * @param string $data
	 * @param class  $class
	 * @param string $func
	 *
	 * @return string
	 */
	public function post_meta_element_render_meta( $data, $class, $func ) {
		if ( ! method_exists( $class, $func ) && method_exists( $this, $func ) ) {
			return $this->$func();
		}
		return $data;
	}

	/**
	 * Render post meta back element
	 *
	 * @param string $data
	 * @param class  $class
	 * @param string $func
	 * @param array  $attr
	 *
	 * @return string
	 */
	public function post_meta_element_render_meta_back( $data, $class, $func, $attr ) {
		if ( ! method_exists( $class, $func ) && method_exists( $this, $func ) ) {
			return $this->$func( $attr );
		}
		return $data;
	}

	/**
	 * Generate donation button
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function generate_donation_button( $args ) {
		global $wp, $post;
		if ( ! class_exists( 'JNews_Donation_Element' ) ) {
			require_once JNEWS_PAY_WRITER_DIR . 'include/class/element/class-jnews-donation.php';
		}
		$args     = wp_parse_args(
			$args,
			array(
				'donation_icon_color'           => JNews_Pay_Writer()->options['donation']['override_icon_color'] ? JNews_Pay_Writer()->options['donation']['donation_icon_color'] : '',
				'button_text'                   => JNews_Pay_Writer()->options['donation']['button_text'],
				'type'                          => 'button',
				'donation_widget_title'         => '',
				'donation_widget_description'   => '',
				'paypal_account'                => get_user_option( 'paypal_account', $post->post_author ),
				'donation_checkout_description' => JNews_Pay_Writer()->options['donation']['checkout_description'],
				'donation_currency'             => JNews_Pay_Writer()->options['donation']['currency'],
				'donation_amount'               => JNews_Pay_Writer()->options['donation']['enable_fix_amount'] ? JNews_Pay_Writer()->options['donation']['fix_amount'] : '',
				'cancel_return'                 => add_query_arg( $wp->query_vars, home_url( $wp->request ) ),
			)
		);
		$donation = new \JNews\PAY_WRITER\Element\JNews_Donation_Element( $args );
		return $donation->generate_element();
	}

	/**
	 * Render donation button on frontend
	 *
	 * @return string
	 */
	public function render_donation_button() {
		global $post;
		if ( ! vp_metabox( 'jnews_single_post.jpwt_disable_post_donation', null, $post->ID ) && 'post' === $post->post_type ) {
			$args = array(
				'paypal_account' => get_user_option( 'paypal_account', $post->post_author ),
			);
			return $this->generate_donation_button( $args );
		}
		return '';
	}

	/**
	 * Render donation button on backend editor
	 *
	 * @param array $attr
	 * @return string
	 */
	public function render_donation_button_back( $attr ) {
		$args = array(
			'paypal_account' => '',
		);
		return $this->generate_donation_button( $args );
	}

	/**
	 * Add donation button to post meta element option
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function post_meta_element_option( $options ) {
		foreach ( $options as $idx => $option ) {
			if ( 'meta_right' === $option['param_name'] || 'meta_left' === $option['param_name'] ) {
				$options[ $idx ]['value'][ esc_html__( 'Donation Button', 'jnews-pay-writer' ) ] = 'donation_button';
			}
		}
		return $options;
	}

	/**
	 * Check for Donation Request.
	 */
	public function check_response() {
		// phpcs:disable WordPress.Security
		if ( ! empty( $_REQUEST ) ) {
			if ( isset( $_REQUEST['donation_token'] ) ) {
				$donation_token = sanitize_text_field( $_REQUEST['donation_token'] );
				$query          = \JNews\PAY_WRITER\Helper::decrypt_data( $donation_token );
				$query          = json_decode( $query, true );
				$donation_url   = add_query_arg( $query, ( JNews_Pay_Writer()->options['paypal']['sandbox'] ? 'https://www.sandbox.paypal.com/' : 'https://www.paypal.com/' ) . 'cgi-bin/webscr' );
				wp_redirect( $donation_url );
				exit;
			}
		}
		// phpcs:enable WordPress.Security
	}

}
