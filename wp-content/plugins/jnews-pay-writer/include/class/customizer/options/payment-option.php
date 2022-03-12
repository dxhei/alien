<?php
/**
 * Payment Rate Setting - Customizer Option
 */

use JNews\PAY_WRITER\Helper;

$count_type = array(
	'standard_payment' => esc_attr__( 'Standard Payout', 'jnews-pay-writer' ),
	'view_payment'     => esc_attr__( 'View Payout ( Need JNews - View Counter )', 'jnews-pay-writer' ),
	'word_payment'     => esc_attr__( 'Word Payout', 'jnews-pay-writer' ),
);
if ( function_exists( 'jnews_view_counter_query' ) ) {
	$count_type['view_payment'] = esc_attr__( 'View Payout', 'jnews-pay-writer' );
}

$options[] = array(
	'id'    => 'jnews_option[pay_writer][paypal][setting_header]',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'PayPal Settings', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][sandbox]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['sandbox'],
	'type'        => 'jeg-toggle',
	'label'       => esc_html__( 'Sandbox Mode', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Check this option if you are using Sandbox APP credentials', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][clientid]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['clientid'],
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'PayPal APP Client ID', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Your PayPal app Client ID', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][clientsecret]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['clientsecret'],
	'type'        => 'jeg-password',
	'label'       => esc_html__( 'PayPal APP Client Secret', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Your PayPal app Client Secret', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][receiveremail]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['receiveremail'],
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'Receiver Email', 'jnews-pay-writer' ),
	'description' => sprintf( __( 'Submit the PayPal/Sandbox email address. Add the following webhook endpoint <strong style="background-color:#ddd;">&nbsp;%s&nbsp;</strong> to your <a href="https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-ipn-notify" target="_blank">Instant Payment Notifications Settings</a>', 'jnews-pay-writer' ), home_url( '?jpwt-api=paypal' ) ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][forward_ipn_response]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['forward_ipn_response'],
	'type'        => 'jeg-toggle',
	'label'       => esc_html__( 'Forward IPN Response', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Enable it if there are other plugins that need IPN response from PayPal', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][paypal][forward_ipn_response_urls]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => JNews_Pay_Writer()->options['paypal']['forward_ipn_response_urls'],
	'type'            => 'jeg-repeater',
	'label'           => esc_html__( 'Forward IPN Urls', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Please provide the valid IPN request URL', 'jnews-pay-writer' ),
	'default'         => array(),
	'row_label'       => array(
		'type'  => 'text',
		'value' => esc_attr__( 'IPN URL', 'jnews-pay-writer' ),
		'field' => false,
	),
	'fields'          => array(
		'url' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'URL', 'jnews' ),
			'description' => esc_attr__( 'Please provide your validate IPN request URL.', 'jnews-pay-writer' ),
			'default'     => home_url( '?wc-api=WC_Gateway_Paypal' ),
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][paypal][forward_ipn_response]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][payout_msg_subject]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['payout_msg_subject'],
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'Payout Subject', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Subject for the payout message', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][paypal][payout_msg]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['paypal']['payout_msg'],
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'Payout Message', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Message for the content of the payout message', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'    => 'jnews_option[pay_writer][payment][general_setting_header]',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'General Settings', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][payment][payment_currency]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['payment']['payment_currency'],
	'type'        => 'jeg-select',
	'label'       => esc_html__( 'Payout Currency', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Choose the currency of the payout', 'jnews-pay-writer' ),
	'choices'     => Helper::get_all_currencies(),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][payment][type]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['payment']['type'],
	'type'        => 'jnews-select',
	'label'       => esc_html__( 'Payout Parameter', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Select payout parameter to be calculated in payout', 'jnews-pay-writer' ),
	'multiple'    => 5,
	'choices'     => $count_type,
);

$standard_payment = array(
	array(
		'setting'  => 'jnews_option[pay_writer][payment][type]',
		'operator' => 'in',
		'value'    => array( 'standard_payment' ),
	),
);

$view_payment = array(
	array(
		'setting'  => 'jnews_option[pay_writer][payment][type]',
		'operator' => 'in',
		'value'    => array( 'view_payment' ),
	),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][payment][max_amount]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'type'        => 'jeg-number',
	'label'       => esc_html__( 'Payout Limit', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Set the maximum payout amount for the receiver (0 means unlimited)', 'jnews-pay-writer' ),
	'default'     => JNews_Pay_Writer()->options['payment']['max_amount'],
	'choices'     => array(
		'min'  => '0',
		'step' => '1',
	),
);

$word_dependency = array(
	array(
		'setting'  => 'jnews_option[pay_writer][payment][type]',
		'operator' => 'in',
		'value'    => array( 'word_payment' ),
	),
);

// standard payment option
$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][standard_payment_header]',
	'type'            => 'jnews-header',
	'label'           => esc_html__( 'Standard Payout Option', 'jnews-pay-writer' ),
	'active_callback' => $standard_payment,
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][standard_amount]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-number',
	'label'           => esc_html__( 'Minimum Post Payout', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Adjust minimum payout amount for each post. Each post will cost at least the value configured.', 'jnews-pay-writer' ),
	'default'         => JNews_Pay_Writer()->options['payment']['standard_amount'],
	'choices'         => array(
		'min'  => '0',
		'step' => '1',
	),
	'active_callback' => $standard_payment,
);

// view payment option

// view counter payment option
$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][view_payment_header]',
	'type'            => 'jnews-header',
	'label'           => esc_html__( 'View Count Payout Option', 'jnews-pay-writer' ),
	'active_callback' => $view_payment,
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][view_rate]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-number',
	'label'           => esc_html__( 'View Payout', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Adjust the payout amount for each view', 'jnews-pay-writer' ),
	'default'         => JNews_Pay_Writer()->options['payment']['view_rate'],
	'choices'         => array(
		'min'  => '0.001',
		'step' => '0.001',
	),
	'active_callback' => $view_payment,
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][min_view]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Donation',
	'type'            => 'jeg-number',
	'label'           => esc_html__( 'Minimum View Count', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Set the minimum views needed to be eligible for view payout', 'jnews-pay-writer' ),
	'default'         => JNews_Pay_Writer()->options['payment']['min_view'],
	'choices'         => array(
		'min'  => '1',
		'step' => '1',
	),
	'active_callback' => $view_payment,
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][word_payment_header]',
	'type'            => 'jnews-header',
	'label'           => esc_html__( 'Word Payout Option', 'jnews-pay-writer' ),
	'active_callback' => $word_dependency,
);


$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][word_rate]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-number',
	'label'           => esc_html__( 'Word Payout Rate', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Set the word rate. The rate would be applied if the if the minimum word requirement is achieved.', 'jnews-pay-writer' ),
	'default'         => JNews_Pay_Writer()->options['payment']['word_rate'],
	'choices'         => array(
		'min'  => '0.01',
		'step' => '0.01',
	),
	'active_callback' => $word_dependency,
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][payment][min_word]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-number',
	'label'           => esc_html__( 'Minimum Word Count', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Set the minimum word count that need to be achieved before the rate can be applied', 'jnews-pay-writer' ),
	'default'         => JNews_Pay_Writer()->options['payment']['min_word'],
	'choices'         => array(
		'min'  => '1',
		'step' => '1',
	),
	'active_callback' => $word_dependency,
);

return $options;

