<?php
/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Donation;

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
class Frontend_Option {
	 /**
	  * @var Frontend_Option
	  */
	private static $instance;

	 /**
	  * @var Donation element type
	  */
	private $is_widget_donation;

	  /**
	   * @return Frontend_Option
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
		$this->is_widget_donation = JNews_Pay_Writer()->options['donation']['element_type'] === 'widget';

		add_action( 'jnews_frontend_submit_insert_after_subtitle', array( $this, 'enable_donation_toggle' ) );
		add_action( 'jnews_insert_after_display_name', array( $this, 'paypal_account_textbox' ) );
		add_action( 'jnews_frontend_submit_save_post_handler', array( $this, 'save_enable_donation_handler' ) );
		add_action( 'jnews_account_page_on_save', array( $this, 'save_paypal_account' ) );
		if ( $this->is_widget_donation ) {
			add_filter( 'the_content', array( $this, 'render_donation_post_widget' ), 10 );
		} else {
			add_action( 'jnews_render_before_meta_right', array( $this, 'render_donation_post_button' ), 99 );
		}
	}

	public function save_paypal_account() {
		if ( isset( $_POST['user_id'] ) && isset( $_POST['paypal_account'] ) ) {
			update_user_option( $_POST['user_id'], 'paypal_account', $_POST['paypal_account'] );
		}
	}

	public function enable_donation_toggle( $post_id ) {
		if ( JNews_Pay_Writer()->options['donation']['enable_writers'] && ! JNews_Pay_Writer()->options['donation']['enable_all_post'] ) {
			$value   = vp_metabox( 'jnews_single_post.jpwt_enable_post_donation', null, $post_id );
			$checked = $value === '1' ? 'checked' : '';
			echo '<div class="jeg_metabox_body">
                        <div class="widget-wrapper type-checkbox" data-field="jpwt_enable_post_donation">
                            <div class="widget-left">
                                <label>' . esc_html__( 'Enable Donation', 'jnews-pay-writer' ) . '</label>
                            </div>
                            <div class="widget-right">
                                <label class="checkbox-container" for="jpwt_enable_post_donation">
                                    <input type="hidden" value="0" name="jpwt_enable_post_donation">
                                    <input type="checkbox" class="checkbox" name="jpwt_enable_post_donation" id="jpwt_enable_post_donation" hidden="" value="1" ' . $checked . '>
                                    <span class="switch"></span>
                                </label>
                                <i>' . esc_html__( 'Check this option to enable donation to this post', 'jnews-pay-writer' ) . '</i>
                            </div>
                        </div>
                    </div>';
		}
	}

	public function paypal_account_textbox( $user_id ) {
		$account = isset( $user_id ) ? get_user_option( 'paypal_account', $user_id ) : '';
		echo '<div class="col-md-6 paypal-account-field">
                    <div class="form-group paypal_account_text">
                        <label for="paypal_account">' . esc_html__( 'Paypal Account', 'jnews-pay-writer' ) . '</label>
                        <input id="paypal_account" name="paypal_account" placeholder="' . esc_html__( 'Insert Your Paypal Account', 'jnews-pay-writer' ) . '"  type="text" class="form-control" value="' . $account . '">
                        <i class="fa " aria-hidden="true"></i>   
                    </div>
                </div>';
	}

	public function donation_args() {
		global $wp, $post;
		if ( ( JNews_Pay_Writer()->options['donation']['enable_all_post'] || vp_metabox( 'jnews_single_post.jpwt_enable_post_donation' ) ) && ! vp_metabox( 'jnews_single_post.jpwt_disable_post_donation', null, $post->ID ) && 'post' === $post->post_type ) {
			if ( ! class_exists( 'JNews_Donation_Element' ) ) {
				require_once JNEWS_PAY_WRITER_DIR . 'include/class/element/class-jnews-donation.php';
			}
			$args     = array(
				'donation_icon_color'           => JNews_Pay_Writer()->options['donation']['override_icon_color'] ? JNews_Pay_Writer()->options['donation']['donation_icon_color'] : '',
				'button_text'                   => JNews_Pay_Writer()->options['donation']['button_text'],
				'type'                          => JNews_Pay_Writer()->options['donation']['element_type'],
				'donation_widget_title'         => $this->is_widget_donation ? JNews_Pay_Writer()->options['donation']['widget_title'] : '',
				'donation_widget_description'   => $this->is_widget_donation ? JNews_Pay_Writer()->options['donation']['widget_description'] : '',
				'paypal_account'                => get_user_option( 'paypal_account', $post->post_author ),
				'donation_checkout_description' => JNews_Pay_Writer()->options['donation']['checkout_description'],
				'donation_currency'             => JNews_Pay_Writer()->options['donation']['currency'],
				'donation_amount'               => JNews_Pay_Writer()->options['donation']['enable_fix_amount'] ? JNews_Pay_Writer()->options['donation']['fix_amount'] : '',
				'cancel_return'                 => add_query_arg( $wp->query_vars, home_url( $wp->request ) ),
			);
			$donation = new \JNews\PAY_WRITER\Element\JNews_Donation_Element( $args );
			return $donation->generate_element( $args );
		}
		return '';
	}

	public function render_donation_post_widget( $content ) {
		return $content . $this->donation_args();
	}

	public function render_donation_post_button( $post_id ) {
		echo $this->donation_args();
	}

	public function save_enable_donation_handler() {
		$post_meta                         = get_post_meta( $_POST['post-id'] );
		$serializedData                    = $post_meta['jnews_single_post'][0];
		$data                              = unserialize( $serializedData );
		$data['jpwt_enable_post_donation'] = $_POST['jpwt_enable_post_donation'];
		update_post_meta( $_POST['post-id'], 'jnews_single_post', $data );
	}
}
