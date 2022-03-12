<?php

/**
 * JNews Paywall Class
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-paywall
 */

namespace JNews\Paywall\Woocommerce;

/**
 * Class Product
 *
 * @package JNews\Paywall\Woocommerce
 */
class Product {
	/**
	 * @var Product
	 */
	private static $instance;

	/**
	 * Product constructor.
	 */
	private function __construct() {
		// actions.
		add_action( 'init', array( $this, 'product_register_term' ) );
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'paywall_product_data' ) );
		add_action( 'woocommerce_product_data_tabs', array( $this, 'paywall_data_tabs' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_data' ) );

		// filters.
		add_filter( 'product_type_selector', array( $this, 'product_type_selector' ) );
	}

	/**
	 * @return Product
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Get All Paywall Package List
	 *
	 * @return array
	 */
	public function get_product_list() {
		$result   = array();
		$packages = get_posts(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 10,
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => array( 'paywall_subscribe', 'paywall_unlock' ),
					),
				),
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			)
		);

		if ( $packages ) {
			foreach ( $packages as $value ) {
				$result[ $value->post_title ] = $value->ID;
			}
		}

		return $result;
	}

	/**
	 * Register New Product Type
	 */
	public function product_register_term() {
		if ( ! get_term_by( 'slug', sanitize_title( 'paywall_subscribe' ), 'product_type' ) ) {
			wp_insert_term(
				'paywall_subscribe',
				'product_type',
				array( 'description' => 'JNews Post Subscribe' )
			);
		}

		if ( ! get_term_by( 'slug', sanitize_title( 'paywall_unlock' ), 'product_type' ) ) {
			wp_insert_term(
				'paywall_unlock',
				'product_type',
				array( 'description' => 'JNews Post Unlock' )
			);
		}
	}

	/**
	 * Add Product Type Selector
	 *
	 * @param $types
	 *
	 * @return mixed
	 */
	public function product_type_selector( $types ) {
		$types['paywall_subscribe'] = esc_html__( 'JNews Post Subscribe', 'jnews-paywall' );
		$types['paywall_unlock']    = esc_html__( 'JNews Post Unlock', 'jnews-paywall' );

		return $types;
	}

	/**
	 * Add Product Data General Option
	 */
	public function paywall_product_data() {
		include JNEWS_PAYWALL_DIR . 'class/woocommerce/options/subscribe-option.php';
		include JNEWS_PAYWALL_DIR . 'class/woocommerce/options/unlock-option.php';
	}

	/**
	 * Hide Woocommerce Product Data Tabs
	 *
	 * @param $product_data_tabs
	 *
	 * @return bool
	 */
	public function paywall_data_tabs( $product_data_tabs ) {
		if ( empty( $product_data_tabs ) ) {
			return false;
		}

		// product data - hide some tabs.
		if ( isset( $product_data_tabs['shipping'] ) && isset( $product_data_tabs['shipping']['class'] ) ) {
			$product_data_tabs['shipping']['class'][] = 'hide_if_paywall_subscribe hide_if_paywall_unlock';
		}
		if ( isset( $product_data_tabs['linked_product'] ) && isset( $product_data_tabs['linked_product']['class'] ) ) {
			$product_data_tabs['linked_product']['class'][] = 'hide_if_paywall_subscribe hide_if_paywall_unlock';
		}
		if ( isset( $product_data_tabs['attribute'] ) && isset( $product_data_tabs['attribute']['class'] ) ) {
			$product_data_tabs['attribute']['class'][] = 'hide_if_paywall_subscribe hide_if_paywall_unlock';
		}

		return $product_data_tabs;
	}

	/**
	 * Save Product Data
	 *
	 * @param $post_id
	 */
	public function save_product_data( $post_id ) {
		$fields = array(
			'_jpw_total'         => 'int',
			'_jpw_duration'      => '',
			'_jpw_total_unlock'  => 'int',
			'_jeg_post_featured' => '',
		);

		foreach ( $fields as $key => $value ) {
			$value = ! empty( $_POST[ $key ] ) ? $_POST[ $key ] : '';

			switch ( $value ) {
				case 'int':
					$value = absint( $value );
					break;
				default:
					$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $key, $value );
		}
	}
}
