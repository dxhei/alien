<?php
/*
	Plugin Name: JNews - Extended Category Option
	Plugin URI: http://jegtheme.com/
	Description: Option and overwrite detail layout of every global category on your website. Recommended for handling large amount of category
	Version: 10.0.2
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_OPTION_CUSTOMIZER' ) or define( 'JNEWS_OPTION_CUSTOMIZER', 'jnews-option-category' );
defined( 'JNEWS_OPTION_CUSTOMIZER_URL' ) or define( 'JNEWS_OPTION_CUSTOMIZER_URL', plugins_url( 'jnews-option-category' ) );
defined( 'JNEWS_OPTION_CUSTOMIZER_FILE' ) or define( 'JNEWS_OPTION_CUSTOMIZER_FILE', __FILE__ );
defined( 'JNEWS_OPTION_CUSTOMIZER_DIR' ) or define( 'JNEWS_OPTION_CUSTOMIZER_DIR', plugin_dir_path( __FILE__ ) );


add_action( 'after_setup_theme', 'jnews_option_category_load' );

if ( ! function_exists( 'jnews_option_category_load' ) ) {
	function jnews_option_category_load() {

		if ( class_exists( 'JNews\Archive\Builder\OptionAbstract' ) ) {
			require_once 'class.jnews-option-category.php';
			do_action( 'before_override_option_category' );
			OptionCategory::getInstance();
		}

	}
}
