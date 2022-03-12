<?php
/*
	Plugin Name: JNews - Food Recipe
	Plugin URI: http://jegtheme.com/
	Description: Food Recipe Plugin for JNews Themes
	Version: 10.0.1
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_FOOD_RECIPE' ) or define( 'JNEWS_FOOD_RECIPE', 'jnews-food-recipe' );
defined( 'JNEWS_FOOD_RECIPE_VERSION' ) or define( 'JNEWS_FOOD_RECIPE_VERSION', '10.0.1' );
defined( 'JNEWS_FOOD_RECIPE_URL' ) or define( 'JNEWS_FOOD_RECIPE_URL', plugins_url( 'jnews-food-recipe' ) );
defined( 'JNEWS_FOOD_RECIPE_FILE' ) or define( 'JNEWS_FOOD_RECIPE_FILE', __FILE__ );
defined( 'JNEWS_FOOD_RECIPE_DIR' ) or define( 'JNEWS_FOOD_RECIPE_DIR', plugin_dir_path( __FILE__ ) );

global $pagenow;

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
 * Load Food Recipe Metabox
 */
if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow || ! is_admin() ) {
	add_action( 'after_setup_theme', 'jnews_food_recipe_metabox_load' );

	if ( ! function_exists( 'jnews_food_recipe_metabox_load' ) ) {
		function jnews_food_recipe_metabox_load() {
			if ( class_exists( 'VP_Metabox' ) ) {
				new VP_Metabox( JNEWS_FOOD_RECIPE_DIR . 'metabox/post-food-recipe-setting.php' );
			}
		}
	}
}

/**
 * Load Food Recipe Class
 */
if ( ! is_admin() ) {
	add_action( 'after_setup_theme', 'jnews_food_recipe' );

	if ( ! function_exists( 'jnews_food_recipe' ) ) {
		function jnews_food_recipe() {
			if ( class_exists( 'VP_Metabox' ) ) {
				require_once 'class.jnews-food-recipe.php';
				JNews_Food_Recipe::getInstance();
			}
		}
	}
}

/**
 * Integration with split content
 */
add_filter( 'jnews_split_content_description', 'jnews_food_recipe_split_content_description', null, 3 );

if ( ! function_exists( 'jnews_food_recipe_split_content_description' ) ) {
	function jnews_food_recipe_split_content_description( $content, $index, $max_page ) {
		require_once 'class.jnews-food-recipe.php';
		return JNews_Food_Recipe::getInstance()->split_food_recipe( $content, $index, $max_page );
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
function jnews_food_recipe_load_textdomain() {
	load_plugin_textdomain( JNEWS_FOOD_RECIPE, false, basename( __DIR__ ) . '/languages/' );
}

jnews_food_recipe_load_textdomain();
