<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE;

/**
 * Class JNews_Subscribe_Module
 *
 * @package JNEWS_SUBSCRIBE
 */
class JNews_Subscribe_Module {

	/**
	 * Instance of JNews_Subscribe_Module
	 *
	 * @var JNews_Subscribe_Module
	 */
	private static $instance;

	/**
	 * JNews_Subscribe_Module constructor.
	 */
	public function __construct() {
		add_filter( 'jnews_module_list', array( $this, 'subscribe_module_element' ) );
		add_filter(
			'jnews_get_option_class_from_shortcode',
			array(
				$this,
				'subscribe_get_option_class_from_shortcode',
			),
			null,
			2
		);
		add_filter(
			'jnews_get_view_class_from_shortcode',
			array(
				$this,
				'subscribe_get_view_class_from_shortcode',
			),
			null,
			2
		);
		add_filter(
			'jnews_get_shortcode_name_from_option',
			array(
				$this,
				'subscribe_get_shortcode_name_from_option',
			),
			null,
			2
		);
		add_action(
			'jnews_build_shortcode_jnews_element_subscribe_view',
			array(
				$this,
				'subscribe_load_module_view',
			)
		);
		add_action( 'jnews_load_all_module_option', array( $this, 'subscribe_load_module_option' ) );
		add_filter(
			'jnews_module_elementor_get_option_class',
			array(
				$this,
				'module_elementor_get_option_class_subscribe',
			)
		);
		add_filter(
			'jnews_module_elementor_get_view_class',
			array(
				$this,
				'module_elementor_get_view_class_subscribe',
			)
		);
	}

	/**
	 * Singleton page of JNews_Subscribe_Module class
	 *
	 * @return JNews_Subscribe_Module
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register module element
	 *
	 * @param array $module List of element.
	 *
	 * @return mixed
	 */
	public function subscribe_module_element( $module ) {
		array_push(
			$module,
			array(
				'name'   => 'JNews_Element_Subscribe',
				'type'   => 'Module',
				'widget' => true,
			)
		);

		return $module;
	}

	/**
	 * Get option element
	 *
	 * @param $class
	 * @param $module
	 *
	 * @return string
	 */
	public function subscribe_get_option_class_from_shortcode( $class, $module ) {
		if ( $module === 'JNews_Element_Subscribe' ) {
			return 'JNews_Element_Subscribe_Option';
		}

		return $class;
	}

	/**
	 * Get view element
	 *
	 * @param $class
	 * @param $module
	 *
	 * @return string
	 */
	public function subscribe_get_view_class_from_shortcode( $class, $module ) {
		if ( $module === 'JNews_Element_Subscribe' ) {
			return 'JNews_Element_Subscribe_View';
		}

		return $class;
	}

	/**
	 * Get shortcode element
	 *
	 * @param $module
	 * @param $class
	 *
	 * @return string
	 */
	public function subscribe_get_shortcode_name_from_option( $module, $class ) {
		if ( $class === 'JNews_Element_Subscribe_Option' ) {
			return 'jnews_element_subscribe';
		}

		return $module;
	}

	/**
	 * Load view class element file
	 */
	public function subscribe_load_module_view() {
		$this->subscribe_load_module_option();
		require_once JNEWS_SUBSCRIBE_DIR . '/class/Module/Element/class.jnews-subscribe-form-view.php';
	}

	/**
	 * Load option class element file
	 */
	public function subscribe_load_module_option() {
		require_once JNEWS_SUBSCRIBE_DIR . '/class/Module/Element/class.jnews-subscribe-form-option.php';
	}

	/**
	 * Register option class element file to Elementor
	 *
	 * @param $option_class
	 *
	 * @return string
	 */
	public function module_elementor_get_option_class_subscribe( $option_class ) {
		if ( '\JNews\Module\Element\Element_Subscribe_Option' === $option_class ) {
			require_once JNEWS_SUBSCRIBE_DIR . '/class/Module/Element/class.jnews-subscribe-form-option.php';

			return 'JNews_Element_Subscribe_Option';
		}

		return $option_class;
	}

	/**
	 * Register view class element file to Elementor
	 *
	 * @param $view_class
	 *
	 * @return string
	 */
	public function module_elementor_get_view_class_subscribe( $view_class ) {
		if ( '\JNews\Module\Element\Element_Subscribe_View' === $view_class ) {
			require_once JNEWS_SUBSCRIBE_DIR . '/class/Module/Element/class.jnews-subscribe-form-view.php';

			return 'JNews_Element_Subscribe_View';
		}

		return $view_class;
	}

}

















