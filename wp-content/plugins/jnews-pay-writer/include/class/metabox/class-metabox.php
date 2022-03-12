<?php
/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Metabox;

use JNews\PAY_WRITER\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * JNews Pay Writer Metabox
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */
class Metabox {
	/**
	 * @var Metabox
	 */
	private static $instance;

	/**
	 * @return Metabox
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Metabox constructor.
	 */
	public function __construct() {
		global $pagenow;
		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow || 'admin-ajax.php' === $pagenow ) {
			add_filter( 'jnews_add_post_general_metabox_field', array( $this, 'donation_metabox_option' ) );
		}

		add_action( 'save_post', array( $this, 'save_post_donation' ), 99 );
	}

	public function donation_metabox_option( $options ) {
		if ( JNews_Pay_Writer()->options['donation']['enable_all_post'] ) {
			$field = array(
				'type'        => 'toggle',
				'name'        => 'jpwt_disable_post_donation',
				'label'       => esc_html__( 'Disable post donation', 'jnews-pay-writer' ),
				'description' => esc_html__( 'Disable donation on this post', 'jnews-pay-writer' ),
			);
		} else {
			$field = array(
				'type'        => 'toggle',
				'name'        => 'jpwt_enable_post_donation',
				'label'       => esc_html__( 'Enable post donation setting', 'jnews-pay-writer' ),
				'description' => esc_html__( 'Check this option to let your viewer donate to this post', 'jnews-pay-writer' ),
			);
		}
		array_push( $options, $field );
		return $options;
	}

	public function save_post_donation() {
		global $post;
		if ( isset( $_REQUEST['jnews_single_post']['jpwt_enable_post_donation'] ) ) {
			update_post_meta( $post->ID, 'jpwt_enable_post_donation', (bool) $_REQUEST['jnews_single_post']['jpwt_enable_post_donation'] );
		} elseif ( isset( $_REQUEST['jnews_single_post']['jpwt_disable_post_donation'] ) ) {
			update_post_meta( $post->ID, 'jpwt_disable_post_donation', (bool) $_REQUEST['jnews_single_post']['jpwt_disable_post_donation'] );
		}
	}

}
