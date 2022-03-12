<?php
/**
 * Plugin Name: JNews - Bookmark
 * Plugin URI: http://jegtheme.com/
 * Description: JNews Bookmark functionality
 * Version: 10.0.0
 * Author: Jegtheme
 * Author URI: http://jegtheme.com
 * License: GPL2
 *
 * @package JNews - Bookmark
 */

defined( 'JNEWS_BOOKMARK' ) || define( 'JNEWS_BOOKMARK', 'jnews-bookmark' );
defined( 'JNEWS_BOOKMARK_VERSION' ) || define( 'JNEWS_BOOKMARK_VERSION', '10.0.0' );
defined( 'JNEWS_BOOKMARK_URL' ) || define( 'JNEWS_BOOKMARK_URL', plugins_url( JNEWS_BOOKMARK ) );
defined( 'JNEWS_BOOKMARK_FILE' ) || define( 'JNEWS_BOOKMARK_FILE', __FILE__ );
defined( 'JNEWS_BOOKMARK_DIR' ) || define( 'JNEWS_BOOKMARK_DIR', plugin_dir_path( __FILE__ ) );

require_once 'class-jnews-bookmark.php';

/**
 * Activation hook
 */
register_activation_hook( JNEWS_BOOKMARK_DIR, JNews_Bookmark::get_instance() );

/**
 * Meta Right Hook
 */
add_action( 'jnews_render_before_meta_right', 'jnews_bookmark_element', 11, 2 );

if ( ! function_exists( 'jnews_bookmark_element' ) ) {
	/**
	 * Render Bookmark Button
	 *
	 * @param Integer $post_id Post ID.
	 */
	function jnews_bookmark_element( $post_id ) {
		JNews_Bookmark::get_instance()->generate_element( $post_id );
	}
}

if ( ! function_exists( 'jnews_bookmark_post_meta_element_option' ) ) {
	/**
	 * Add donation button to post meta element option
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function jnews_bookmark_post_meta_element_option( $options ) {
		foreach ( $options as $idx => $option ) {
			if ( 'meta_right' === $option['param_name'] || 'meta_left' === $option['param_name'] ) {
				$options[ $idx ]['value'][ esc_html__( 'Bookmark Button', 'jnews-bookmark' ) ] = 'bookmark';
			}
		}
		return $options;
	}
}
add_filter( 'jnews_post_meta_element_options', 'jnews_bookmark_post_meta_element_option' );

if ( ! function_exists( 'jnews_bookmark_post_meta_element_render_meta' ) ) {
	/**
	 * Render post meta element
	 *
	 * @param string $data
	 * @param class  $class
	 * @param string $func
	 *
	 * @return string
	 */
	function jnews_bookmark_post_meta_element_render_meta( $data, $class, $func ) {
		if ( ! method_exists( $class, $func ) && strpos( $func, 'render_bookmark' ) !== false ) {
			ob_start();
			JNews_Bookmark::get_instance()->generate_element( get_the_ID() );
			return ob_get_clean();
		}
		return $data;
	}
}
add_filter( 'jnews_post_meta_element_render_meta', 'jnews_bookmark_post_meta_element_render_meta', 11, 3 );

if ( ! function_exists( 'jnews_bookmark_post_meta_element_render_meta_back' ) ) {
	/**
	 * Render post meta element back
	 *
	 * @param string $data
	 * @param class  $class
	 * @param string $func
	 * @param array  $attr
	 *
	 * @return string
	 */
	function jnews_bookmark_post_meta_element_render_meta_back( $data, $class, $func, $attr ) {
		if ( ! method_exists( $class, $func ) && strpos( $func, 'render_bookmark' ) !== false ) {
			return '<div class="jeg_meta_bookmark">
						<a href="#" data-id="20904" data-added="" data-message=""><i class="fa fa-bookmark-o"></i></a>
					</div>';
		}
		return $data;
	}
}
add_filter( 'jnews_post_meta_element_render_meta_back', 'jnews_bookmark_post_meta_element_render_meta_back', 11, 4 );

if ( ! function_exists( 'jnews_get_bookmark_option' ) ) {
	/**
	 * Get jnews option
	 *
	 * @param array $setting Settings.
	 * @param mixed $default Default Value.
	 * @return mixed
	 */
	function jnews_get_bookmark_option( $setting, $default = null ) {
		$options = get_option( 'jnews_option', array() );
		$value   = $default;
		if ( isset( $options['jnews_bookmark'][ $setting ] ) ) {
			$value = $options['jnews_bookmark'][ $setting ];
		}
		return $value;
	}
}

add_filter( 'jnews_show_bookmark_button', 'jnews_show_bookmark_button', null, 2 );

if ( ! function_exists( 'jnews_show_bookmark_button' ) ) {
	/**
	 * Filters Bookmark Button Status
	 *
	 * @param bool   $show Status Bookmark Button.
	 * @param string $post_id ID Post.
	 * @return bool
	 */
	function jnews_show_bookmark_button( $show, $post_id ) {
		if ( vp_metabox( 'jnews_override_bookmark_settings.override_bookmark_button', false, $post_id ) ) {
			return vp_metabox( 'jnews_override_bookmark_settings.override_show_bookmark_button', false, $post_id );
		}

		return $show;
	}
}

add_action( 'jeg_register_customizer_option', 'jnews_bookmark_option' );

if ( ! function_exists( 'jnews_bookmark_option' ) ) {
	/**
	 * Register Bookmark Option
	 */
	function jnews_bookmark_option() {
		require_once 'class-jnews-bookmark-option.php';
		JNews_Bookmark_Option::get_instance();
	}
}


add_filter( 'jeg_register_lazy_section', 'jnews_bookmark_lazy_section' );

if ( ! function_exists( 'jnews_bookmark_lazy_section' ) ) {
	/**
	 * Register Bookmark Lazy Section
	 *
	 * @param object $result Options.
	 * @return object $result
	 */
	function jnews_bookmark_lazy_section( $result ) {
		$result['jnews_bookmark_section'][] = JNEWS_BOOKMARK_DIR . 'bookmark-option.php';
		return $result;
	}
}


add_action( 'after_setup_theme', 'jnews_bookmark_option_metabox_load' );

if ( ! function_exists( 'jnews_bookmark_option_metabox_load' ) ) {
	/**
	 * Register Bookmark Option in Metabox
	 */
	function jnews_bookmark_option_metabox_load() {
		if ( class_exists( 'VP_Metabox' ) ) {
			new VP_Metabox( JNEWS_BOOKMARK_DIR . 'metabox/bookmark-metabox.php' );
		}
	}
}

if ( ! function_exists( 'jnews_return_translation' ) ) {
	/**
	 * Return Translation
	 *
	 * @param string  $string The String.
	 * @param string  $domain The Domain.
	 * @param string  $name The Name.
	 * @param boolean $escape The String.
	 */
	function jnews_return_translation( $string, $domain, $name, $escape = true ) {
		return apply_filters( 'jnews_return_translation', $string, $domain, $name, $escape );
	}
}

if ( ! function_exists( 'jnews_return_main_translation' ) ) {
	add_filter( 'jnews_return_translation', 'jnews_return_main_translation', 10, 4 );

	/**
	 * Main Translation Function
	 *
	 * @param string  $string The String.
	 * @param string  $domain The Domain.
	 * @param string  $name The Name.
	 * @param boolean $escape The String.
	 */
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
function jnews_bookmark_load_textdomain() {
	load_plugin_textdomain( JNEWS_BOOKMARK, false, basename( __DIR__ ) . '/languages/' );
}

jnews_bookmark_load_textdomain();
