<?php

/**
 * JNews Tiktok Feed Helper
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */

/**
 * Get jnews option
 *
 * @param $setting
 * @param $default
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
 * Load Text Domain
 */
function jnews_tiktok_load_textdomain() {
	load_plugin_textdomain( JNEWS_TIKTOK, false, basename( __DIR__ ) . '/languages/' );
}

jnews_tiktok_load_textdomain();

if ( ! function_exists( 'jnews_tiktok_get_url' ) ) {
	function jnews_tiktok_get_url( $obj, $username, $method, $param = array() ) {
		$url = JNEWS_TIKTOK_API_URL;

		switch ( $obj ) {
			case 'id':
				$url = 'username' === $method ? JNEWS_TIKTOK_DEFAULT_URL . '/@' . $username : JNEWS_TIKTOK_API_URL . '/share/tag/' . $username;
				break;
			case 'feed':
				$url = JNEWS_TIKTOK_API_URL . '/video/feed/?id=' . $param['id'] . '&minCursor=0&maxCursor=0&count=60&type=' . $param['type'];
				break;
			case 'view':
				$url = 'username' === $method ? JNEWS_TIKTOK_DEFAULT_URL . '/@' . $username : JNEWS_TIKTOK_DEFAULT_URL . '/tag/' . $username;
				break;
			case 'video':
				$url = JNEWS_TIKTOK_DEFAULT_URL . '/@' . $username . '/video/' . $param['id'];
				break;
		}

		return $url;
	}
}
