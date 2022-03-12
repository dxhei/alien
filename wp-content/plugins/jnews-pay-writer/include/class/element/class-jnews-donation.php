<?php
/**
 * @author Jegtheme
 */

namespace JNews\PAY_WRITER\Element;

use JNews\Module\ModuleManager;

use JNews\PAY_WRITER\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class JNews_Subscribe_Element
 *
 * @package JNEWS_SUBSCRIBE\Module
 */
class JNews_Donation_Element {

	private $type;
	private $button_text;
	private $button_text_color;
	private $button_color;
	private $donation_icon_color;
	private $paypal_account;
	private $widget_title;
	private $widget_desc;
	private $checkout_desc;
	private $currency;
	private $amount;
	private $cancel_return;

	public function __construct( $args ) {
		$this->type = $args['type'];

		$this->button_text = isset( $args['button_text'] ) ? $args['button_text'] : '';
		$this->button_text = ! empty( $this->button_text ) ? $this->button_text : esc_html__( 'Donate', 'jnews-pay-writer' );

		$this->button_text_color   = isset( $args['button_text_color'] ) && ! empty( $args['button_text_color'] ) ? $args['button_text_color'] : '';
		$this->button_color        = isset( $args['button_color'] ) && ! empty( $args['button_color'] ) ? $args['button_color'] : '';
		$this->donation_icon_color = isset( $args['donation_icon_color'] ) ? $args['donation_icon_color'] : '';
		$this->paypal_account      = esc_attr( $args['paypal_account'] );

		$this->widget_title = isset( $args['donation_widget_title'] ) ? $args['donation_widget_title'] : '';
		$this->widget_title = ! empty( $this->widget_title ) ? $this->widget_title : esc_html__( 'Donation', 'jnews-pay-writer' );

		$this->widget_desc = isset( $args['donation_widget_description'] ) ? $args['donation_widget_description'] : '';
		$this->widget_desc = ! empty( $this->widget_desc ) ? $this->widget_desc : esc_html__( 'Buy me a coffee', 'jnews-pay-writer' );

		$this->checkout_desc = isset( $args['donation_checkout_description'] ) ? $args['donation_checkout_description'] : '';
		$this->checkout_desc = ! empty( $this->checkout_desc ) ? $args['donation_checkout_description'] : esc_html__( 'Buy me a coffee', 'jnews-pay-writer' );

		$this->currency      = esc_attr( $args['donation_currency'] );
		$this->amount        = esc_attr( $args['donation_amount'] );
		$this->cancel_return = esc_attr( $args['cancel_return'] );
	}

	public function generate_element() {

		$column = ModuleManager::getInstance()->get_column_class();

		$query       = array(
			'cmd'           => '_donations',
			'business'      => $this->paypal_account,
			'item_name'     => $this->checkout_desc,
			'item_number'   => '',
			'curency_code'  => $this->currency,
			'amount'        => $this->amount,
			'no_note'       => '1',
			'no_shipping'   => '1',
			'lc'            => get_locale(),
			'cancel_return' => $this->cancel_return,
			'bn'            => 'PP-DonationsBF:btn_donateCC_LG.gif:NonHosted',
		);
		$query       = wp_json_encode( $query );
		$query       = \JNews\PAY_WRITER\Helper::encrypt_data( $query );
		$donate_link = add_query_arg(
			array(
				'donation_token' => rawurlencode( $query ),
				'jpwt-api'       => 'donation',
			),
			home_url()
		);

		if ( 'button' === $this->type ) {
			$style = ! empty( $this->donation_icon_color ) ? "color: {$this->donation_icon_color}" : '';
			return "<div class='jeg_meta_donation'>
				<a href='{$donate_link}' target='_blank'><i class='jpwt-icon jpwt-pay fa'></i> <span>{$this->button_text}</span></a>
			</div>";
		}
		$btn_color   = $this->button_color ? "background:{$this->button_color};" : '';
		$text_color  = $this->button_text_color ? "color:{$this->button_text_color};" : '';
		$style       = ! empty( $btn_color ) || ! empty( $text_color ) ? "style='{$btn_color}{$text_color}'" : '';
		$donate_link = "<a href='{$donate_link}' {$style}' class='btn jpwt-donation-submit' target='_blank'><span>{$this->button_text}</span></a>";
		return '<div class="jpwt-donation-' . $this->type . ' ' . $column . '">
						<div class="jpwt-donation-text-container">
							<div class="jpwt-donation-text-wrapper">
								<h3 class="jpwt-donation-title" >' . $this->widget_title . '</h3>
								<p class="jpwt-donation-description">' . $this->widget_desc . '</p>
							</div>
						</div>
						<div class="jpwt-donation-form-container">
							<div class="jpwt-donation-form-wrapper">
								' . $donate_link . '
							</div>
						</div>
				</div>';

	}
}


