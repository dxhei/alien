<?php
/**
 * @author : Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use JNews\Module\ModuleOptionAbstract;

use JNews\PAY_WRITER\Helper;

/**
 * Class Element_Userlist_Option
 *
 * @package JNews\Module\Element
 */
class JNews_Element_Donation_Option extends ModuleOptionAbstract {
	public function compatible_column() {
		return array( 4, 8, 12 );
	}

	public function set_options() {
		$this->set_general_option();
	}

	public function get_module_name() {
		return esc_html__( 'JNews - Donation Element', 'jnews' );
	}

	public function get_category() {
		return esc_html__( 'JNews - Element', 'jnews' );
	}

	public function set_general_option() {
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'paypal_account',
			'std'         => '',
			'heading'     => esc_html__( 'Paypal account', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Insert paypal account here', 'jnews-pay-writer' ),
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'donation_currency',
			'std'         => JNews_Pay_Writer()->options['donation']['currency'],
			'heading'     => esc_html__( 'Donation Currency', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Currency allowed for donation', 'jnews-pay-writer' ),
			'value'       => Helper::get_all_currencies( true ),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'button_text',
			'heading'     => esc_html__( 'Donation Button Text', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Configure text displayed in the donation element', 'jnews-pay-writer' ),
			'std'         => JNews_Pay_Writer()->options['donation']['button_text'],
		);

		$this->options[] = array(
			'type'       => 'checkbox',
			'param_name' => 'override_button_color',
			'heading'    => esc_html__( 'Override Donation Button Color', 'jnews' ),
			'value'      => array( esc_html__( 'Overrides post donation button color.', 'jnews' ) => 'true' ),
		);

		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'button_text_color',
			'std'         => '#FFFFFF',
			'heading'     => esc_html__( 'Button Text Color', 'jnews' ),
			'description' => esc_html__( 'Donation Button Text color', 'jnews' ),
			'dependency'  => array(
				'element' => 'override_button_color',
				'value'   => 'true',
			),
		);

		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'button_color',
			'std'         => JNews_Pay_Writer()->options['donation']['donation_icon_color'],
			'heading'     => esc_html__( 'Button Color', 'jnews' ),
			'description' => esc_html__( 'Donation button color', 'jnews' ),
			'dependency'  => array(
				'element' => 'override_button_color',
				'value'   => 'true',
			),

		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'donation_widget_title',
			'std'         => JNews_Pay_Writer()->options['donation']['widget_title'],
			'heading'     => esc_html__( 'Donation Widget title', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Configure title displayed in the donation widget', 'jnews-pay-writer' ),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'donation_widget_description',
			'std'         => JNews_Pay_Writer()->options['donation']['widget_description'],
			'heading'     => esc_html__( 'Donation Widget Description', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Displays description text in the donation widget', 'jnews-pay-writer' ),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'donation_checkout_description',
			'std'         => JNews_Pay_Writer()->options['donation']['checkout_description'],
			'heading'     => esc_html__( 'Checkout Description', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Displays description text at the donation checkout page', 'jnews-pay-writer' ),
		);

		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'enable_fix_amount',
			'std'         => JNews_Pay_Writer()->options['donation']['enable_fix_amount'],
			'heading'     => esc_html__( 'Enable fix amount donation', 'jnews-pay-writer' ),
			'description' => esc_html__( 'By enabling this option, readers can only make a donation of a predetermined amount', 'jnews-pay-writer' ),
		);

		$this->options[] = array(
			'type'        => 'number',
			'param_name'  => 'fix_amount_donation',
			'heading'     => esc_html__( 'Fix amount donation', 'jnews-pay-writer' ),
			'description' => esc_html__( 'Determine the fix donation amount', 'jnews' ),
			'max'         => PHP_INT_MAX,
			'min'         => 1,
			'step'        => 1,
			'std'         => JNews_Pay_Writer()->options['donation']['fix_amount'],
			'dependency'  => array(
				'element' => 'enable_fix_amount',
				'value'   => 'true',
			),
		);

	}
}
