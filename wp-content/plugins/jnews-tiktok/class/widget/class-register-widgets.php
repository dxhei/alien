<?php

/**
 * JNews Tiktok Class
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */

namespace JNews\Tiktok\Widget;

/**
 * Class Widget
 *
 * @package JNews\Tiktok\Widget
 */
class Register_Widgets {
	/**
	 * @var Widget
	 */
	private static $instance;

	/**
	 * Register_Widgets constructor.
	 */
	private function __construct() {
		add_action( 'jnews_load_all_module_option', array( $this, 'load_class_option' ) );
		add_filter( 'jnews_module_list', array( $this, 'tiktok_widget' ) );
		add_filter( 'jnews_get_normal_widget_list', array( $this, 'register_normal_widget' ) );
		add_filter( 'jnews_get_view_class_from_shortcode', array( $this, 'get_element_view' ), null, 2 );
		add_action( 'jnews_build_shortcode_jnews_widget_tiktok_view', array( $this, 'load_widget_view' ) );
		add_action( 'jnews_load_all_module_option', array( $this, 'load_widget_option' ) );
		add_filter( 'jnews_widget_class_instance', array( $this, 'load_widget_class' ), null, 2 );
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

	public function load_class_option() {
		require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok.php';
	}

	public function tiktok_widget( $module ) {
		array_push(
			$module,
			array(
				'name'   => 'JNews_Widget_Tiktok',
				'type'   => 'widget',
				'widget' => false,
			)
		);

		return $module;
	}

	public function get_element_view( $class, $module ) {
		if ( $module === 'JNews_Widget_Tiktok' ) {
			require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok.php';
			require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok-option.php';
			require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok-view.php';

			return '\JNews\Module\Widget\Widget_Tiktok_View';
		}

		return $class;
	}

	public function register_normal_widget( $modules ) {
		return array_merge( $modules, array( 'Tiktok_Widget' ) );
	}

	public function load_widget_view() {
		$this->load_widget_option();
		require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok-view.php';
	}

	public function load_widget_option() {
		require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok-option.php';
	}

	public function load_widget_class( $instance, $class ) {
		if ( 'TiktokWidget' === $class ) {
			require_once JNEWS_TIKTOK_DIR . 'class/widget/class-jnews-widget-tiktok.php';

			return new \JNews\Widget\Normal\Element\TiktokWidget();
		}

		return $instance;
	}
}
