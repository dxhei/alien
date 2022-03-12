<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_GUTENBERG;

class Init {
	private static $instance;

	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct() {
		if ( function_exists( 'register_block_type' ) ) {
			$this->setup_hook();
		}
	}

	protected function setup_hook() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_assets' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'register_block' ) );

		add_filter( 'block_categories_all', array( $this, 'module_category' ) );
		add_filter( 'theme_mod_jnews_image_load', array( $this, 'switch_normal_load' ) );
	}

	public function switch_normal_load( $value ) {
		if ( $this->is_gutenberg_editor() ) {
			return 'normal';
		}

		return $value;
	}

	public function is_gutenberg_editor() {
		if ( isset( $_GET['context'] ) && sanitize_text_field( $_GET['context'] ) === 'edit' ) {
			return true;
		}

		return false;
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain( JNEWS_GUTENBERG, false, JNEWS_GUTENBERG . '/languages/' );
	}

	public function register_block() {
		$modules = include_once 'Module/modules.php';

		require_once JNEWS_GUTENBERG_DIR . '/class/Module/module-gutenberg.php';

		foreach ( $modules as $module ) {
			$slug  = $this->get_class_slug( $module['name'] );
			$class = new $module['name']();

			register_block_type(
				'jnews-gutenberg/' . $slug,
				array(
					'attributes'      => $class->attribute(),
					'render_callback' => array( $class, 'render' ),
				)
			);
		}
	}

	protected function get_class_slug( $class ) {
		$slug = explode( '_', $class );
		$slug = strtolower( $slug[1] . '-' . $slug[2] );

		return $slug;
	}

	public function load_assets() {
		if ( ! defined( 'JNEWS_THEME_URL' ) ) {
			return;
		}

		$asset_url     = get_parent_theme_file_uri();
		$theme_version = $this->get_theme_version();

		wp_enqueue_style( 'jnews-frontend-style', $asset_url . '/assets/dist/frontend.min.css' );
		wp_enqueue_style( 'jnews-newsticker', $asset_url . '/assets/css/jnewsticker.css', null, $theme_version );
		wp_enqueue_style( 'jnews-gutenberg-editor', JNEWS_GUTENBERG_URL . '/assets/css/editor.css', null, $theme_version );

		wp_enqueue_script( 'jnews-owlslider', $asset_url . '/assets/js/jowlslider.js', null, $theme_version, true );
		wp_enqueue_script( 'jnews-newsticker', $asset_url . '/assets/js/jnewsticker.js', null, $theme_version, true );

		wp_enqueue_script(
			'jnews-gutenberg-editor',
			JNEWS_GUTENBERG_URL . '/assets/dist/index.js',
			array(
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-editor',
				'wp-i18n',
			)
		);

		wp_localize_script( 'jnews-gutenberg-editor', 'jnewsgutenbergoption', \JNews\Asset\FrontendAsset::getInstance()->localize_script() );
	}

	protected function get_theme_version() {
		$theme = wp_get_theme();

		return $theme->get( 'Version' );
	}

	public function module_category( $categories ) {
		$category = array(
			array(
				'slug'  => 'jnews-block',
				'title' => esc_html__( 'JNews Block', 'jnews-gutenberg' ),
			),
			array(
				'slug'  => 'jnews-hero',
				'title' => esc_html__( 'JNews Hero', 'jnews-gutenberg' ),
			),
			array(
				'slug'  => 'jnews-slider',
				'title' => esc_html__( 'JNews Slider', 'jnews-gutenberg' ),
			),
			array(
				'slug'  => 'jnews-element',
				'title' => esc_html__( 'JNews Element', 'jnews-gutenberg' ),
			),
			array(
				'slug'  => 'jnews-carousel',
				'title' => esc_html__( 'JNews Carousel', 'jnews-gutenberg' ),
			),
		);

		return array_merge( $categories, $category );
	}
}
