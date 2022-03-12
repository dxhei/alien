<?php
/**
 * JNews  Class
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */

namespace JNews\PAY_WRITER\Customizer;

/**
 * Class Customizer
 *
 * @package JNews\Paywall\Customizer
 */
class Customizer {
	/**
	 * @var Customizer
	 */
	private static $instance;

	/**
	 * @var
	 */
	private $customizer;

	/**
	 * Customizer constructor.
	 */
	private function __construct() {

		// actions.
		add_action( 'jeg_register_customizer_option', array( $this, 'customizer_option' ) );

		// filters.
		add_filter( 'jeg_register_lazy_section', array( $this, 'autoload_section' ) );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_control_css' ) );
	}

	/**
	 * @return Customizer
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function customizer_control_css() {
		wp_enqueue_style( 'jnews-pay-writer-icon', JNEWS_PAY_WRITER_URL . '/assets/css/icon.css', null, JNEWS_PAY_WRITER_VERSION );
	}

	/**
	 * Register Customizer using jeg-framework
	 */
	public function customizer_option() {
		if ( class_exists( 'Jeg\Customizer\Customizer' ) ) {
			$this->customizer = \Jeg\Customizer\Customizer::get_instance();

			$this->set_panel();
			$this->set_section();
		}
	}

	/**
	 * Add new panel
	 */
	public function set_panel() {
		$this->customizer->add_panel(
			array(
				'id'          => 'jnews_pay_writer_panel',
				'title'       => esc_html__( 'JNews : Pay Writer Option', 'jnews-pay-writer' ),
				'description' => esc_html__( 'Pay Writer Options', 'jnews-pay-writer' ),
				'priority'    => 200,
			)
		);
	}

	/**
	 * Add new section in the panel
	 */
	public function set_section() {
		$donation_section = array(
			'id'       => 'jnews_donation_section',
			'title'    => esc_html__( 'Donation Setting', 'jnews-pay-writer' ),
			'panel'    => 'jnews_pay_writer_panel',
			'priority' => 263,
			'type'     => 'jnews-lazy-section',
		);

		$payment_section = array(
			'id'       => 'jnews_payment_section',
			'title'    => esc_html__( 'Payment Setting', 'jnews-pay-writer' ),
			'panel'    => 'jnews_pay_writer_panel',
			'priority' => 264,
			'type'     => 'jnews-lazy-section',
		);

		$this->customizer->add_section( $donation_section );
		$this->customizer->add_section( $payment_section );
	}

	/**
	 * Load Customizer Option
	 *
	 * @param $result
	 *
	 * @return mixed
	 */
	public function autoload_section( $result ) {
		$result['jnews_donation_section'][] = JNEWS_PAY_WRITER_DIR . 'include/class/customizer/options/donation-option.php';
		$result['jnews_payment_section'][]  = JNEWS_PAY_WRITER_DIR . 'include/class/customizer/options/payment-option.php';
		return $result;
	}


}
