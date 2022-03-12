<?php
/**
 * JNews Pay Writer Option - Customizer Option
 */

use JNews\PAY_WRITER\Helper;

$options = array();

$options[] = array(
	'id'          => 'jnews_option[pay_writer][donation][enable_all_post]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'type'        => 'jeg-toggle',
	'default'     => JNews_Pay_Writer()->options['donation']['enable_all_post'],
	'label'       => esc_html__( 'Donation on All Posts', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Enable donation on all posts', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][donation][enable_writers]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-toggle',
	'default'         => JNews_Pay_Writer()->options['donation']['enable_writers'],
	'label'           => esc_html__( 'Author Donation', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Enabling this option will grant the author to choose wheter to enable the donation option on their posts. Overrides `Donation on All Post` option', 'jnews-pay-writer' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][donation][enable_all_post]',

			'operator' => '==',
			'value'    => false,
		),
	),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][donation][currency]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['donation']['currency'],
	'type'        => 'jeg-select',
	'label'       => esc_html__( 'Donation Currency', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Currency allowed for post donation', 'jnews-pay-writer' ),
	'choices'     => Helper::get_all_currencies(),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][donation][button_text]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['donation']['button_text'],
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'Donation Element Text', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Configure text displayed in the donation element', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][donation][element_type]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'type'        => 'jeg-select',
	'default'     => JNews_Pay_Writer()->options['donation']['element_type'],
	'label'       => esc_html__( 'Donation Element Type', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Choose donation element type', 'jnews-pay-writer' ),
	'choices'     => array(
		'button' => 'Button',
		'widget' => 'Widget',
	),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][donation][override_icon_color]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-toggle',
	'default'         => JNews_Pay_Writer()->options['donation']['override_icon_color'],
	'label'           => esc_html__( 'Override Donation Icon Color', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Overrides post donation meta icon color', 'jnews-pay-writer' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][donation][element_type]',
			'operator' => '==',
			'value'    => 'button',
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][donation][donation_icon_color]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => JNews_Pay_Writer()->options['donation']['donation_icon_color'],
	'type'            => 'jnews-color',
	'disable_color'   => true,
	'label'           => esc_html__( 'Donation Icon Color', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Choose color for donation icon color', 'jnews-pay-writer' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][donation][element_type]',
			'operator' => '==',
			'value'    => 'button',
		),
		array(
			'setting'  => 'jnews_option[pay_writer][donation][override_icon_color]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][donation][widget_title]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => JNews_Pay_Writer()->options['donation']['widget_title'],
	'type'            => 'jeg-text',
	'label'           => esc_html__( 'Donation Widget Title', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Configure title displayed in the donation widget', 'jnews-pay-writer' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][donation][element_type]',
			'operator' => '==',
			'value'    => 'widget',
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][donation][widget_description]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => JNews_Pay_Writer()->options['donation']['widget_description'],
	'type'            => 'jeg-text',
	'label'           => esc_html__( 'Donation Widget Description', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Displays description text at the donation widget', 'jnews-pay-writer' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][donation][element_type]',
			'operator' => '==',
			'value'    => 'widget',
		),
	),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][donation][checkout_description]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => JNews_Pay_Writer()->options['donation']['checkout_description'],
	'type'        => 'jeg-text',
	'label'       => esc_html__( 'Checkout Description', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Displays description text at the donation checkout page', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'          => 'jnews_option[pay_writer][donation][enable_fix_amount]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'type'        => 'jeg-toggle',
	'default'     => JNews_Pay_Writer()->options['donation']['enable_fix_amount'],
	'label'       => esc_html__( 'Fixed Donation', 'jnews-pay-writer' ),
	'description' => esc_html__( 'Enabling this option will strict donors to donate the predetermined amount', 'jnews-pay-writer' ),
);

$options[] = array(
	'id'              => 'jnews_option[pay_writer][donation][fix_amount]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jeg-number',
	'label'           => esc_html__( 'Fixed Donation amount', 'jnews-pay-writer' ),
	'description'     => esc_html__( 'Determine donation amount ( ' . JNews_Pay_Writer()->options['payment']['payment_currency'] . ' )', 'jnews-pay-writer' ),
	'default'         => JNews_Pay_Writer()->options['donation']['fix_amount'],
	'choices'         => array(
		'max'  => 0,
		'min'  => 1,
		'step' => 1,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[pay_writer][donation][enable_fix_amount]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

return $options;


