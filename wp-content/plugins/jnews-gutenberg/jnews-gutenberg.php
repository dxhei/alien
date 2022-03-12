<?php
/*
	Plugin Name: JNews - Gutenberg
	Plugin URI: http://jegtheme.com/
	Description: Gutenberg extender plugin for JNews
	Version: 10.0.1
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_GUTENBERG' ) or define( 'JNEWS_GUTENBERG', 'jnews-gutenberg' );
defined( 'JNEWS_GUTENBERG_URL' ) or define( 'JNEWS_GUTENBERG_URL', plugins_url( 'jnews-gutenberg' ) );
defined( 'JNEWS_GUTENBERG_FILE' ) or define( 'JNEWS_GUTENBERG_FILE', __FILE__ );
defined( 'JNEWS_GUTENBERG_DIR' ) or define( 'JNEWS_GUTENBERG_DIR', plugin_dir_path( __FILE__ ) );


require_once JNEWS_GUTENBERG_DIR . '/class/autoload.php';

JNEWS_GUTENBERG\Init::getInstance();
