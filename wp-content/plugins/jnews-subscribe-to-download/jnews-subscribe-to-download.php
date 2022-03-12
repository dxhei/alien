<?php
/*
	Plugin Name: JNews - Subscribe to Download
	Plugin URI: http://jegtheme.com/
	Description: Subscribe to download functionality for JNews
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_SUBSCRIBE' ) || define( 'JNEWS_SUBSCRIBE', 'jnews-subscribe-to-download' );
defined( 'JNEWS_SUBSCRIBE_VERSION' ) || define( 'JNEWS_SUBSCRIBE_VERSION', '10.0.0' );
defined( 'JNEWS_SUBSCRIBE_URL' ) || define( 'JNEWS_SUBSCRIBE_URL', plugins_url( JNEWS_SUBSCRIBE ) );
defined( 'JNEWS_SUBSCRIBE_FILE' ) || define( 'JNEWS_SUBSCRIBE_FILE', __FILE__ );
defined( 'JNEWS_SUBSCRIBE_DIR' ) || define( 'JNEWS_SUBSCRIBE_DIR', plugin_dir_path( __FILE__ ) );

require_once JNEWS_SUBSCRIBE_DIR . '/class/class.jnews-subscribe.php';

JNEWS_SUBSCRIBE\JNews_Subscribe::get_instance();
