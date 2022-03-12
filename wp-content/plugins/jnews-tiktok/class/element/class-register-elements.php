<?php

/**
 * JNews Tiktok Class
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */

namespace JNews\Tiktok\Element;

/**
 * Class Element
 *
 * @package JNews\Tiktok\Element
 */
class Register_Elements {
	/**
	 * @var Element
	 */
	private static $instance;

	/**
	 * Register_Elements constructor.
	 */
	private function __construct() {
		add_filter( 'jnews_module_list', array( $this, 'tiktok_element' ) );
		add_filter( 'jnews_get_option_class_from_shortcode', array( $this, 'get_element_option' ), null, 2 );
		add_filter( 'jnews_get_view_class_from_shortcode', array( $this, 'get_element_view' ), null, 2 );
		add_filter( 'jnews_get_shortcode_name_from_option', array( $this, 'get_shortcode_name' ), null, 2 );
		add_action( 'jnews_build_shortcode_jnews_footer_tiktok_view', array( $this, 'load_element_view' ) );
		add_action( 'jnews_load_all_module_option', array( $this, 'load_element_option' ) );
		add_filter( 'jnews_module_elementor_get_option_class', array( $this, 'get_option_class' ) );
		add_filter( 'jnews_module_elementor_get_view_class', array( $this, 'get_view_class' ) );
	}

	/**
	 * @return Element
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function tiktok_element( $module ) {
		array_push(
			$module,
			array(
				'name'   => 'JNews_Footer_Tiktok',
				'type'   => 'footer',
				'widget' => false,
			)
		);

		return $module;
	}

	public function get_element_option( $class, $module ) {
		if ( $module === 'JNews_Footer_Tiktok' ) {
			return 'JNews_Footer_Tiktok_Option';
		}

		return $class;
	}

	public function get_element_view( $class, $module ) {
		if ( $module === 'JNews_Footer_Tiktok' ) {
			return 'JNews_Footer_Tiktok_View';
		}

		return $class;
	}

	public function get_shortcode_name( $module, $class ) {
		if ( $class === 'JNews_Footer_Tiktok_Option' ) {
			return 'jnews_footer_tiktok';
		}

		return $module;
	}

	public function load_element_view() {
		$this->load_element_option();
		require_once JNEWS_TIKTOK_DIR . 'class/element/class-jnews-footer-tiktok-view.php';
	}

	public function load_element_option() {
		require_once JNEWS_TIKTOK_DIR . 'class/element/class-jnews-footer-tiktok-option.php';
	}

	public function get_option_class( $option_class ) {
		if ( $option_class === '\JNews\Module\Footer\Footer_Tiktok_Option' ) {
			require_once JNEWS_TIKTOK_DIR . 'class/element/class-jnews-footer-tiktok-option.php';

			return 'JNews_Footer_Tiktok_Option';
		}

		return $option_class;
	}

	public function get_view_class( $view_class ) {
		if ( $view_class === '\JNews\Module\Footer\Footer_Tiktok_View' ) {
			require_once JNEWS_TIKTOK_DIR . 'class/element/class-jnews-footer-tiktok-view.php';

			return 'JNews_Footer_Tiktok_View';
		}

		return $view_class;
	}
}
