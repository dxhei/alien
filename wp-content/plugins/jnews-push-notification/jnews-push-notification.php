<?php
/*
	Plugin Name: JNews - Push Notification
	Plugin URI: http://jegtheme.com/
	Description: Desktop push notification plugin for JNews Themes
	Version: 10.0.1
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_PUSH_NOTIFICATION' ) or define( 'JNEWS_PUSH_NOTIFICATION', 'jnews-push-notification' );
defined( 'JNEWS_PUSH_NOTIFICATION_VERSION' ) or define( 'JNEWS_PUSH_NOTIFICATION_VERSION', '10.0.1' );
defined( 'JNEWS_PUSH_NOTIFICATION_URL' ) or define( 'JNEWS_PUSH_NOTIFICATION_URL', plugins_url( 'jnews-push-notification' ) );
defined( 'JNEWS_PUSH_NOTIFICATION_FILE' ) or define( 'JNEWS_PUSH_NOTIFICATION_FILE', __FILE__ );
defined( 'JNEWS_PUSH_NOTIFICATION_DIR' ) or define( 'JNEWS_PUSH_NOTIFICATION_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Get JNews option
 *
 * @param array $setting
 * @param mixed $default
 *
 * @return mixed
 */
if ( ! function_exists( 'jnews_get_option' ) ) {
	function jnews_get_option( $setting, $default = null ) {
		$options = get_option( 'jnews_option', array() );
		$value   = $default;

		if ( isset( $options[ $setting ] ) ) {
			$value = $options[ $setting ];
		}
		return $value;
	}
}

/**
 * Register Push Notification Widget
 */
add_action( 'widgets_init', 'register_push_notification_widget' );

if ( ! function_exists( 'register_push_notification_widget' ) ) {
	function register_push_notification_widget() {
		if ( ! defined( 'JNEWS_THEME_URL' ) ) {
			return;
		}

		require_once 'class.jnews-push-notification-widget.php';
		register_widget( 'JNews_Push_Notification_Widget' );
	}
}

/**
 * Register Push Notification Option
 */
add_action( 'jeg_register_customizer_option', 'jnews_push_notification_customizer_option' );

if ( ! function_exists( 'jnews_push_notification_customizer_option' ) ) {
	function jnews_push_notification_customizer_option() {
		require_once 'class.jnews-push-notification-option.php';
		JNews_Push_Notification_Option::getInstance();
	}
}


add_filter( 'jeg_register_lazy_section', 'jnews_notification_lazy_section' );

if ( ! function_exists( 'jnews_notification_lazy_section' ) ) {
	function jnews_notification_lazy_section( $result ) {
		$result['jnews_push_notification_section'][] = JNEWS_PUSH_NOTIFICATION_DIR . 'push-notification-option.php';
		return $result;
	}
}

/**
 * Register Push Notification Class
 */
add_action( 'after_setup_theme', 'jnews_push_notification' );

if ( ! function_exists( 'jnews_push_notification' ) ) {
	function jnews_push_notification() {
		require_once 'class.jnews-push-notification.php';
		JNews_Push_Notification::getInstance();
	}
}

/**
 * Register Push Notification Shortcode
 */
add_filter( 'jnews_module_list', 'jnews_push_notification_module_element' );

if ( ! function_exists( 'jnews_push_notification_module_element' ) ) {
	function jnews_push_notification_module_element( $module ) {
		array_push(
			$module,
			array(
				'name'   => 'JNews_Element_Push_Notification',
				'type'   => 'element',
				'widget' => false,
			)
		);

		return $module;
	}
}

add_filter( 'jnews_get_option_class_from_shortcode', 'jnews_get_option_class_from_shortcode_push_notification', null, 2 );

if ( ! function_exists( 'jnews_get_option_class_from_shortcode_push_notification' ) ) {
	function jnews_get_option_class_from_shortcode_push_notification( $class, $module ) {
		if ( $module === 'JNews_Element_Push_Notification' ) {
			return 'JNews_Element_Push_Notification_Option';
		}

		return $class;
	}
}

add_filter( 'jnews_get_view_class_from_shortcode', 'jnews_get_view_class_from_shortcode_push_notification', null, 2 );

if ( ! function_exists( 'jnews_get_view_class_from_shortcode_push_notification' ) ) {
	function jnews_get_view_class_from_shortcode_push_notification( $class, $module ) {
		if ( $module === 'JNews_Element_Push_Notification' ) {
			return 'JNews_Element_Push_Notification_View';
		}

		return $class;
	}
}

if ( ! function_exists( 'jnews_get_shortcode_name_from_option_push_notification' ) ) {
	add_filter( 'jnews_get_shortcode_name_from_option', 'jnews_get_shortcode_name_from_option_push_notification', null, 2 );

	function jnews_get_shortcode_name_from_option_push_notification( $module, $class ) {
		if ( $class === 'JNews_Element_Push_Notification_Option' ) {
			return 'jnews_element_push_notification';
		}

		return $module;
	}
}

add_action( 'jnews_build_shortcode_jnews_element_push_notification_view', 'jnews_push_notification_load_module_view' );

if ( ! function_exists( 'jnews_push_notification_load_module_view' ) ) {
	function jnews_push_notification_load_module_view() {
		jnews_push_notification_load_module_option();
		require_once 'class.jnews-push-notification-module-view.php';
	}
}

add_action( 'jnews_load_all_module_option', 'jnews_push_notification_load_module_option' );

if ( ! function_exists( 'jnews_push_notification_load_module_option' ) ) {
	function jnews_push_notification_load_module_option() {
		 require_once 'class.jnews-push-notification-module-option.php';
	}
}

if ( ! function_exists( 'jnews_module_elementor_get_option_class_push_notification' ) ) {
	add_filter( 'jnews_module_elementor_get_option_class', 'jnews_module_elementor_get_option_class_push_notification' );

	function jnews_module_elementor_get_option_class_push_notification( $option_class ) {
		if ( $option_class === '\JNews\Module\Element\Element_Push_Option' ) {
			require_once 'class.jnews-push-notification-module-option.php';
			return 'JNews_Element_Push_Notification_Option';
		}

		return $option_class;
	}
}

if ( ! function_exists( 'jnews_module_elementor_get_view_class_push_notification' ) ) {
	add_filter( 'jnews_module_elementor_get_view_class', 'jnews_module_elementor_get_view_class_push_notification' );

	function jnews_module_elementor_get_view_class_push_notification( $view_class ) {
		if ( $view_class === '\JNews\Module\Element\Element_Push_View' ) {
			require_once 'class.jnews-push-notification-module-view.php';
			return 'JNews_Element_Push_Notification_View';
		}

		return $view_class;
	}
}


/**
 * Print Translation
 */
if ( ! function_exists( 'jnews_print_translation' ) ) {
	function jnews_print_translation( $string, $domain, $name ) {
		do_action( 'jnews_print_translation', $string, $domain, $name );
	}
}

if ( ! function_exists( 'jnews_print_main_translation' ) ) {
	add_action( 'jnews_print_translation', 'jnews_print_main_translation', 10, 2 );

	function jnews_print_main_translation( $string, $domain ) {
		call_user_func_array( 'esc_html_e', array( $string, $domain ) );
	}
}

/**
 * Return Translation
 */
if ( ! function_exists( 'jnews_return_translation' ) ) {
	function jnews_return_translation( $string, $domain, $name, $escape = true ) {
		return apply_filters( 'jnews_return_translation', $string, $domain, $name, $escape );
	}
}

if ( ! function_exists( 'jnews_return_main_translation' ) ) {
	add_filter( 'jnews_return_translation', 'jnews_return_main_translation', 10, 4 );

	function jnews_return_main_translation( $string, $domain, $name, $escape = true ) {
		if ( $escape ) {
			return call_user_func_array( 'esc_html__', array( $string, $domain ) );
		} else {
			return call_user_func_array( '__', array( $string, $domain ) );
		}

	}
}

/**
 * Load Text Domain
 */
function jnews_push_notification_load_textdomain() {
	load_plugin_textdomain( JNEWS_PUSH_NOTIFICATION, false, basename( __DIR__ ) . '/languages/' );
}

jnews_push_notification_load_textdomain();
