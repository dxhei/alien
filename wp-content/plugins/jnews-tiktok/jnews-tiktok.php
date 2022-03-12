<?php
/*
	Plugin Name: JNews - Tiktok Feed
	Plugin URI: http://jegtheme.com/
	Description: TikTok widget and element for JNews
	Version: 10.0.0
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_TIKTOK' ) or define( 'JNEWS_TIKTOK', 'jnews-tiktok' );
defined( 'JNEWS_TIKTOK_VERSION' ) or define( 'JNEWS_TIKTOK_VERSION', '10.0.0' );
defined( 'JNEWS_TIKTOK_URL' ) or define( 'JNEWS_TIKTOK_URL', plugins_url( JNEWS_TIKTOK ) );
defined( 'JNEWS_TIKTOK_FILE' ) or define( 'JNEWS_TIKTOK_FILE', __FILE__ );
defined( 'JNEWS_TIKTOK_DIR' ) or define( 'JNEWS_TIKTOK_DIR', plugin_dir_path( __FILE__ ) );

defined( 'JNEWS_TIKTOK_DEFAULT_URL' ) or define( 'JNEWS_TIKTOK_DEFAULT_URL', 'https://www.tiktok.com' );
defined( 'JNEWS_TIKTOK_API_URL' ) or define( 'JNEWS_TIKTOK_API_URL', 'https://www.tiktok.com/node' );

require_once JNEWS_TIKTOK_DIR . 'autoload.php';
require_once JNEWS_TIKTOK_DIR . 'helper.php';

/**
 * Initialize Plugin
 */
JNews\Tiktok\Init::instance();
