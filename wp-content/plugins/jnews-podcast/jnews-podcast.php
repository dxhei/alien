<?php
/*
	Plugin Name: JNews - Podcast
	Plugin URI: http://jegtheme.com/
	Description: Turn JNews into a responsive podcast website with ease
	Version: 10.0.3
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_PODCAST' ) or define( 'JNEWS_PODCAST', 'jnews-podcast' );
defined( 'JNEWS_PODCAST_VERSION' ) or define( 'JNEWS_PODCAST_VERSION', '10.0.3' );
defined( 'JNEWS_PODCAST_URL' ) or define( 'JNEWS_PODCAST_URL', plugins_url( JNEWS_PODCAST ) );
defined( 'JNEWS_PODCAST_FILE' ) or define( 'JNEWS_PODCAST_FILE', __FILE__ );
defined( 'JNEWS_PODCAST_DIR' ) or define( 'JNEWS_PODCAST_DIR', plugin_dir_path( JNEWS_PODCAST_FILE ) );
defined( 'JNEWS_PODCAST_CLASSPATH' ) or define( 'JNEWS_PODCAST_CLASSPATH', JNEWS_PODCAST_DIR . 'include/class/' );
defined( 'JNEWS_PODCAST_TEMPLATE' ) or define( 'JNEWS_PODCAST_TEMPLATE', JNEWS_PODCAST_DIR . 'include/fragment/' );

if ( wp_get_theme() == 'JNews' || wp_get_theme()->parent() == 'JNews' ) {
	require_once JNEWS_PODCAST_DIR . 'include/autoload.php';
	JNEWS_PODCAST\Init::get_instance();
}
