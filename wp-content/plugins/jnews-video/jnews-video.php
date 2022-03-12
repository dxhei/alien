<?php
/*
	Plugin Name: JNews - Video
	Plugin URI: http://jegtheme.com/
	Description: Change and turn on video mode on JNews
	Version: 10.0.3
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_VIDEO' ) or define( 'JNEWS_VIDEO', 'jnews-video' );
defined( 'JNEWS_VIDEO_VERSION' ) or define( 'JNEWS_VIDEO_VERSION', '10.0.3' );
defined( 'JNEWS_VIDEO_URL' ) or define( 'JNEWS_VIDEO_URL', plugins_url( JNEWS_VIDEO ) );
defined( 'JNEWS_VIDEO_FILE' ) or define( 'JNEWS_VIDEO_FILE', __FILE__ );
defined( 'JNEWS_VIDEO_DIR' ) or define( 'JNEWS_VIDEO_DIR', plugin_dir_path( JNEWS_VIDEO_FILE ) );
defined( 'JNEWS_VIDEO_TEMPLATE' ) or define( 'JNEWS_VIDEO_TEMPLATE', JNEWS_VIDEO_DIR . 'fragment/' );

defined( 'JNEWS_VIDEO_PLAYLIST_DB_DATA' ) or define( 'JNEWS_VIDEO_PLAYLIST_DB_DATA', 'jeg_playlist' );
defined( 'JNEWS_VIDEO_STATISTICS_DB_DATA' ) or define( 'JNEWS_VIDEO_STATISTICS_DB_DATA', 'jeg_video_statistics' );

if ( wp_get_theme() == 'JNews' || wp_get_theme()->parent() == 'JNews' ) {
	require_once JNEWS_VIDEO_DIR . 'class/autoload.php';
	JNEWS_VIDEO\Init::get_instance();
}
