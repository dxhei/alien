<?php

namespace JNews\PAY_WRITER;

/**
 * @author Jegtheme
 */

use JNews\PAY_WRITER\Customizer\Customizer;
use JNews\PAY_WRITER\Donation\Donation_Handler;
use JNews\PAY_WRITER\Module\Donation_Module;
use JNews\PAY_WRITER\Util\Database;
use JNews\PAY_WRITER\Util\JPWT_API;
use JNews\PAY_WRITER\Widget\Register_Widgets;
use JNews\PAY_WRITER\Util\Paypal\Paypal_Payout;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Init
 *
 * @package JNEWS_PAY_WRITER
 */
class Init {
	/**
	 * Instance of Init
	 *
	 * @var Init
	 */
	private static $instance;

	/**
	 * View counter options
	 *
	 * @var array
	 */
	public $options;

	/**
	 * View counter options
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * View counter options
	 *
	 * @var array
	 */
	public $defaults = array(
		'config'   => array(
			'dates'         => '',
			'range'         => 'last7days',
			'time_quantity' => 24,
			'time_unit'     => 'hours',
		),
		'general'  => array(),
		'donation' => array(
			'enable_all_post'      => false,
			'enable_writers'       => false,
			'currency'             => 'USD',
			'button_text'          => 'Donate',
			'element_type'         => 'button',
			'override_icon_color'  => false,
			'donation_icon_color'  => '#1eb277',
			'widget_title'         => 'Donation',
			'widget_description'   => 'Buy author a coffee',
			'checkout_description' => 'Buy author a coffee',
			'enable_fix_amount'    => false,
			'fix_amount'           => '5',
		),
		'paypal'   => array(
			'sandbox'                   => false,
			'clientid'                  => '',
			'clientsecret'              => '',
			'receiveremail'             => '',
			'forward_ipn_response'      => false,
			'forward_ipn_response_urls' => array(),
			'payout_msg_subject'        => 'Payout',
			'payout_msg'                => 'You received payout',
		),
		'payment'  => array(
			'payment_currency' => 'USD',
			'type'             => array( 'standard_payment' ),
			'max_amount'       => '0',
			'word_rate'        => '0.001',
			'min_word'         => '5',
			'standard_amount'  => '0',
			'view_rate'        => '0.001',
			'min_view'         => '5',
		),
		'display'  => array(
			'save_stats_order'             => false,
			'enable_post_stats_caching'    => true,
			'payment_display_round_digits' => 2,
			'first_available_post_time'    => array(
				'exp'  => null,
				'time' => null,
			),
			'last_available_post_time'     => array(
				'exp'  => null,
				'time' => null,
			),
		),
	);

	/**
	 * Check if JNews active
	 *
	 * @var boolean
	 */
	public $is_jnews;

	/**
	 * @var Customizer
	 */
	public $customizer;

	/**
	 * @var Donation_Handler
	 */
	public $donation_handler;

	/**
	 * @var \JNews\PAY_WRITER\Util\Database
	 */
	public $database;

	/**
	 * @var \JNews\PAY_WRITER\Util\JPWT_API
	 */
	public $jpwt_api;

	/**
	 * @var Register_Widgets
	 */
	public $register_widgets;

	/**
	 * @var Paypal_Payout
	 */
	public $paypal_payout;

	/**
	 * @var \JNews\PAY_WRITER\Util\Payment
	 */
	public $payment;

	/**
	 * @var \JNews\PAY_WRITER\Metabox\Metabox
	 */
	public $metabox;

	/**
	 * @var \JNews\PAY_WRITER\Dashboard\Dashboard
	 */
	public $dashboard;

	/**
	 * @var \JNews\PAY_WRITER\Donation\Frontend_Option
	 */
	public $frontend_option;

	/**
	 * @var \JNews\PAY_WRITER\Module\Donation_Module
	 */
	public $donation_module;

	/**
	 * Singleton page of Init class
	 *
	 * @return Init
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Init ) ) {
			self::$instance                   = new Init();
			self::$instance->database         = Database::instance();
			self::$instance->customizer       = Customizer::instance();
			self::$instance->donation_handler = Donation_Handler::instance();
			self::$instance->paypal_payout    = Paypal_Payout::instance();
			self::$instance->jpwt_api         = JPWT_API::instance();
			self::$instance->donation_module  = Donation_Module::instance();

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], 'jpwt' ) !== false || defined( 'DOING_AJAX' ) ) {
				self::$instance->payment = \JNews\PAY_WRITER\Util\Payment::instance();
			}
			if ( is_admin() || defined( 'DOING_AJAX' ) ) {
				self::$instance->metabox   = \JNews\PAY_WRITER\Metabox\Metabox::instance();
				self::$instance->dashboard = \JNews\PAY_WRITER\Dashboard\Dashboard::instance();
			} else {
				self::$instance->template        = new \JNews\PAY_WRITER\Template();
				self::$instance->frontend_option = \JNews\PAY_WRITER\Donation\Frontend_Option::instance();
			}
		}

		return self::$instance;
	}

	/**
	 * Init constructor.
	 */
	private function __construct() {
		$themes         = wp_get_theme();
		$this->is_jnews = ( ( $themes->parent() && $themes->parent()->get( 'TextDomain' ) === 'jnews' ) || $themes->get( 'TextDomain' ) === 'jnews' );
		$this->settings = array(
			'current_page'                   => '',
			'option_stats_cache_incrementor' => 'jpwt_stats_cache_incrementor',
			'temp'                           => array( 'settings' => array() ),
			'stats_menu_link'                => 'admin.php?page=jpwt-post-stats',
		);
		$this->defaults['display']['first_available_post_time']['exp']  = time();
		$this->defaults['display']['last_available_post_time']['exp']   = time();
		$this->defaults['display']['first_available_post_time']['time'] = current_time( 'timestamp' );
		$this->defaults['display']['last_available_post_time']['time']  = current_time( 'timestamp' );
		$this->options = array(
			'config'   => array_merge( $this->defaults['config'], Helper::get_pay_writer_option( 'config', $this->defaults['config'] ) ),
			'general'  => array_merge( $this->defaults['general'], Helper::get_pay_writer_option( 'general', $this->defaults['general'] ) ),
			'donation' => array_merge( $this->defaults['donation'], Helper::get_pay_writer_option( 'donation', $this->defaults['donation'] ) ),
			'paypal'   => array_merge( $this->defaults['paypal'], Helper::get_pay_writer_option( 'paypal', $this->defaults['paypal'] ) ),
			'payment'  => array_merge( $this->defaults['payment'], Helper::get_pay_writer_option( 'payment', $this->defaults['payment'] ) ),
			'display'  => array_merge( $this->defaults['display'], Helper::get_pay_writer_option( 'display', $this->defaults['display'] ) ),
		);
		$this->setup_hook();
		$this->load_plugin_text_domain();
	}

	public function dispatch_config() {
		$this->options['config'] = array_merge( $this->defaults['config'], Helper::get_pay_writer_option( 'config', $this->defaults['config'] ) );
	}

	private function setup_hook() {
		add_action( 'wp_enqueue_scripts', array( 'JNews\PAY_WRITER\Helper', 'wp_localize_vanillajs_datepicker' ), 1000 );
		add_action( 'admin_enqueue_scripts', array( 'JNews\PAY_WRITER\Helper', 'wp_localize_vanillajs_datepicker' ), 1000 );
		register_activation_hook( JNEWS_PAY_WRITER_FILE, array( 'JNews\PAY_WRITER\Util\Activator', 'activate' ) );
		register_deactivation_hook( JNEWS_PAY_WRITER_FILE, array( 'JNews\PAY_WRITER\Util\Deactivator', 'deactivate' ) );
		if ( is_admin() || Helper::is_elementor_editor() ) {
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_admin_style' ), 99 );
			add_action( 'after_setup_theme', array( $this, 'backend_option_load' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_script' ), 99 );
		} else {
			add_action( 'init', array( $this, 'dispatch_config' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_script' ), 98 );
		}
	}

	/**
	 * Load Admin Style CSS
	 */
	public function load_admin_style() {
		wp_enqueue_style( 'jnews-pay-writer-admin', JNEWS_PAY_WRITER_URL . '/assets/css/admin/admin-style.css', null, JNEWS_PAY_WRITER_VERSION );
	}

	/**
	 * Load Admin CSS
	 */
	public function load_admin_script() {
		$this->load_admin_style();
		wp_enqueue_style( 'jnews-pay-writer', JNEWS_PAY_WRITER_URL . '/assets/css/admin/plugin.css', null, JNEWS_PAY_WRITER_VERSION );
		wp_enqueue_script( 'jnews-pay-writer', JNEWS_PAY_WRITER_URL . '/assets/js/admin/jpwt-admin.js', array( 'jquery' ), JNEWS_PAY_WRITER_VERSION, true );
		wp_enqueue_style( 'jnews-pay-writer-icon', JNEWS_PAY_WRITER_URL . '/assets/css/icon.css', null, JNEWS_PAY_WRITER_VERSION );
		wp_localize_script(
			'jnews-pay-writer',
			'jpwt_stats_vars',
			array(
				'datepicker_mindate'    => isset( JNews_Pay_Writer()->settings['first_available_post_time'] ) ? date( 'Y-m-d', JNews_Pay_Writer()->settings['first_available_post_time'] ) : date( 'Y-m-d', current_time( 'timestamp' ) ),
				'datepicker_maxdate'    => isset( JNews_Pay_Writer()->settings['last_available_post_time'] ) ? date( 'Y-m-d', JNews_Pay_Writer()->settings['last_available_post_time'] ) : date( 'Y-m-d', strtotime( '23:59:59' ) ),
				'time_start_this_month' => date( 'Y-m-d', strtotime( 'first day of this month' ) ),
				'time_end_this_month'   => isset( JNews_Pay_Writer()->settings['time_end_now'] ) ? JNews_Pay_Writer()->settings['time_end_now'] : date( 'Y-m-d', strtotime( '23:59:59' ) ),
				'time_start_this_year'  => date( 'Y-m-d', strtotime( 'first day of january this year' ) ),
				'time_end_this_year'    => isset( JNews_Pay_Writer()->settings['time_end_now'] ) ? JNews_Pay_Writer()->settings['time_end_now'] : date( 'Y-m-d', strtotime( '23:59:59' ) ),
				'time_start_this_week'  => isset( JNews_Pay_Writer()->settings['time_start_end_week'] ) ? date( 'Y-m-d', JNews_Pay_Writer()->settings['time_start_end_week']['start'] ) : date( 'Y-m-d', get_weekstartend( current_time( 'mysql' ) )['start'] ),
				'time_end_this_week'    => isset( JNews_Pay_Writer()->settings['time_end_now'] ) ? JNews_Pay_Writer()->settings['time_end_now'] : date( 'Y-m-d', strtotime( '23:59:59' ) ),
				'time_start_last_month' => date( 'Y-m-d', strtotime( 'first day of last month' ) ),
				'time_end_last_month'   => date( 'Y-m-d', strtotime( 'first day of this month' ) - 86400 ), // go to first day of current month and back of one day
				'time_start_all_time'   => isset( JNews_Pay_Writer()->settings['first_available_post_time'] ) ? JNews_Pay_Writer()->settings['first_available_post_time'] : current_time( 'timestamp' ),
				'time_end_all_time'     => isset( JNews_Pay_Writer()->settings['time_end_now'] ) ? JNews_Pay_Writer()->settings['time_end_now'] : date( 'Y-m-d', strtotime( '23:59:59' ) ),
			)
		);
	}

	public function load_frontend_script() {
		wp_register_style( 'jnews-pay-writer-icon', JNEWS_PAY_WRITER_URL . '/assets/css/icon.css', null, JNEWS_PAY_WRITER_VERSION );
		wp_register_style( 'jnews-pay-writer', JNEWS_PAY_WRITER_URL . '/assets/css/frontend.css', array(), JNEWS_PAY_WRITER_VERSION );
		wp_register_style( 'jnews-pay-writer-darkmode', JNEWS_PAY_WRITER_URL . '/assets/css/darkmode.css', array( 'jnews-pay-writer-icon', 'jnews-pay-writer' ), JNEWS_PAY_WRITER_VERSION );
		wp_register_script( 'jnews-pay-writer', JNEWS_PAY_WRITER_URL . '/assets/js/plugin.js', array( 'jquery' ), JNEWS_PAY_WRITER_VERSION, true );

		wp_enqueue_style( 'jnews-pay-writer-darkmode' );
		wp_enqueue_script( 'jnews-pay-writer' );
	}

	public function backend_option_load() {
		if ( class_exists( 'JNews\Archive\Builder\OptionAbstract' ) ) {
			\JNews\PAY_WRITER\Author\Backend_Option::getInstance();
		}
	}

	/**
	 * Return the JPWT API URL for a given request.
	 *
	 * @param string    $request Requested endpoint.
	 * @param bool|null $ssl     If should use SSL, null if should auto detect. Default: null.
	 *
	 * @return string
	 */
	public function api_request_url( $request, $ssl = null ) {
		if ( is_null( $ssl ) ) {
			$scheme = wp_parse_url( home_url(), PHP_URL_SCHEME );
		} elseif ( $ssl ) {
			$scheme = 'https';
		} else {
			$scheme = 'http';
		}

		if ( strstr( get_option( 'permalink_structure' ), '/index.php/' ) ) {
			$api_request_url = trailingslashit( home_url( '/index.php/jpwt-api/' . $request, $scheme ) );
		} elseif ( get_option( 'permalink_structure' ) ) {
			$api_request_url = trailingslashit( home_url( '/jpwt-api/' . $request, $scheme ) );
		} else {
			$api_request_url = add_query_arg( 'jpwt-api', $request, trailingslashit( home_url( '', $scheme ) ) );
		}

		return esc_url_raw( apply_filters( 'jpwt_api_request_url', $api_request_url, $request, $ssl ) );
	}

	/**
	 * Load plugin text domain
	 */
	private function load_plugin_text_domain() {
		load_plugin_textdomain( JNEWS_PAY_WRITER, false, basename( JNEWS_PAY_WRITER_DIR ) . '/languages/' );
	}
}
