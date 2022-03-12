<?php
/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Util;

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
class Payment {
	/**
	 * @var Payment
	 */
	private static $instance;

	private static $payment_args = array();

	public $status;

	/**
	 * @return Payment
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __construct() {
		$this->set_status_args();
		$this->status = new Payment_Status( self::$payment_args['status'] );
	}

	private function set_status_args() {
		if ( empty( self::$payment_args ) ) {
			self::$payment_args = array(
				'status' => array(),
			);
		}
		self::$payment_args['status'] = array(
			'type'            => JNews_Pay_Writer()->options['payment']['type'],
			'standard_amount' => floatval( JNews_Pay_Writer()->options['payment']['standard_amount'] ),
			'max_amount'      => floatval( JNews_Pay_Writer()->options['payment']['max_amount'] ),
			'min_view'        => JNews_Pay_Writer()->options['payment']['min_view'],
			'view_rate'       => floatval( JNews_Pay_Writer()->options['payment']['view_rate'] ),
			'min_word'        => JNews_Pay_Writer()->options['payment']['min_word'],
			'word_rate'       => floatval( JNews_Pay_Writer()->options['payment']['word_rate'] ),
		);
	}
}
