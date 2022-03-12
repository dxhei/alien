<?php

/**
 * JNews Tiktok Class
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */

namespace JNews\Tiktok;

use JNews\Tiktok\Customizer\Customizer;
use JNews\Tiktok\Element\Register_Elements;
use JNews\Tiktok\Util\JNews_Tiktok_Render;
use JNews\Tiktok\Widget\Register_Widgets;
use JNews\Tiktok\Util\Render;

/**
 * Class Init
 *
 * @package JNews\Tiktok
 */
class Init {
	/**
	 * @var Init
	 */
	private static $instance;

	/**
	 * Init constructor.
	 */
	private function __construct() {
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * @return Init
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Setup Classes
	 */
	private function setup_init() {
		Customizer::instance();
		Register_Elements::instance();
		Register_Widgets::instance();
	}

	/**
	 * Setup Hooks
	 */
	private function setup_hook() {
		add_action( 'jnews_render_tiktok_feed_footer', array( $this, 'jnews_tiktok_feed_footer' ) );
		add_action( 'wp_print_styles', array( $this, 'load_frontend_css' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_asset' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_admin_asset' ), 11 );
	}

	public function load_admin_asset() {
		wp_enqueue_style( 'jnews-tiktok-admin', JNEWS_TIKTOK_URL . '/assets/css/admin/admin-style.css', null, JNEWS_TIKTOK_VERSION );
		wp_enqueue_style( 'jnews-tiktok-elementor', JNEWS_TIKTOK_URL . '/assets/css/admin/elementor-backend.css', null, JNEWS_TIKTOK_VERSION );
	}

	/**
	 * Load Frontend CSS
	 */
	public function load_frontend_css() {
		wp_enqueue_style( 'jnews-tiktok', JNEWS_TIKTOK_URL . '/assets/css/frontend.css', null, JNEWS_TIKTOK_VERSION );
	}

	/**
	 * Render Tiktok Feed - Footer
	 */
	public function jnews_tiktok_feed_footer() {
		$option = jnews_get_option( 'tiktok_feed_enable', 'hide' );

		if ( $option === 'show' ) {
			$param = array(
				'row'      => jnews_get_option( 'footer_tiktok_row', 1 ),
				'column'   => jnews_get_option( 'footer_tiktok_column', 8 ),
				'type'     => jnews_get_option( 'footer_tiktok_feed_type', 'username' ),
				'username' => jnews_get_option( 'footer_tiktok_username', '' ),
				'hastag'   => jnews_get_option( 'footer_tiktok_hastag', '' ),
				'sort'     => jnews_get_option( 'footer_tiktok_sort_type', 'most_recent' ),
				'hover'    => jnews_get_option( 'footer_tiktok_hover_style', 'zoom' ),
				'open'     => jnews_get_option( 'footer_tiktok_open', true ) ? 'target=\'_blank\'' : '',
				'layout'   => jnews_get_option( 'footer_tiktok_layout', 'masonry' ),
				'button'   => jnews_get_option( 'footer_tiktok_view_button', null ),
				'cover'    => jnews_get_option( 'footer_tiktok_cover', 'cover' ),
			);

			$tiktok = new JNews_Tiktok_Render( $param );
			$tiktok->generate_element();
		}
	}
}
