<?php
/*
	Plugin Name: JNews - Pay Writer
	Plugin URI: http://jegtheme.com/
	Description: Provide authors payment and donation for the post they made. easily configure how much author can earn for a post by payment option
	Version: 10.0.3
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
	Text Domain: jnews-pay-writer
*/

defined( 'JNEWS_PAY_WRITER' ) || define( 'JNEWS_PAY_WRITER', 'jnews-pay-writer' );
defined( 'JNEWS_PAY_WRITER_VERSION' ) || define( 'JNEWS_PAY_WRITER_VERSION', '10.0.3' );
defined( 'JNEWS_PAY_WRITER_URL' ) || define( 'JNEWS_PAY_WRITER_URL', plugins_url( JNEWS_PAY_WRITER ) );
defined( 'JNEWS_PAY_WRITER_FILE' ) || define( 'JNEWS_PAY_WRITER_FILE', __FILE__ );
defined( 'JNEWS_PAY_WRITER_DIR' ) || define( 'JNEWS_PAY_WRITER_DIR', plugin_dir_path( __FILE__ ) );
defined( 'JNEWS_PAY_WRITER_CLASSPATH' ) || define( 'JNEWS_PAY_WRITER_CLASSPATH', JNEWS_PAY_WRITER_DIR . 'include/class/' );
defined( 'JNEWS_PAY_WRITER_DB_DATA' ) or define( 'JNEWS_PAY_WRITER_DB_DATA', 'jpwt_paymentsdata' );
defined( 'JNEWS_PAY_WRITER_DB_SUMMARY' ) or define( 'JNEWS_PAY_WRITER_DB_SUMMARY', 'jpwt_paymentssummary' );

require_once JNEWS_PAY_WRITER_DIR . 'include/autoload.php';

/**
 * Initialise JNews View Counter
 *
 * @return JNews\PAY_WRITER\Init
 */
function JNews_Pay_Writer() {
	static $instance;

	// first call to instance() initializes the plugin
	if ( null === $instance || ! ( $instance instanceof JNews\PAY_WRITER\Init ) ) {
		$instance = JNews\PAY_WRITER\Init::instance();
	}

	return $instance;
}

JNews_Pay_Writer();
