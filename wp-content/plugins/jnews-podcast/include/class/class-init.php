<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST;

use JNEWS_PODCAST\Category\Podcast_Category;
use JNEWS_PODCAST\Customizer\Podcast_Customizer;
use JNEWS_PODCAST\Module\Podcast_Module;
use JNEWS_PODCAST\Player\Player;
use JNEWS_PODCAST\Series\Series;

/**
 * Class Init
 *
 * @package JNEWS_PODCAST
 */
class Init {

	/**
	 * Instance of Init
	 *
	 * @var Init
	 */
	private static $instance;

	/**
	 * Init constructor.
	 */
	private function __construct() {
		$this->load_helper();
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * Load helper file
	 */
	public function load_helper() {
		require_once JNEWS_PODCAST_DIR . 'include/helper.php';
	}

	/**
	 * Setup Init
	 */
	private function setup_init() {
		Podcast_Customizer::get_instance();
		Podcast_Category::get_instance();
		Podcast_Module::get_instance();
		Series::get_instance();
		Player::get_instance();
	}

	private function setup_hook() {
		add_action( 'after_setup_theme', array( $this, 'blog_metabox' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_asset' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_asset' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_admin_asset' ) );
		add_action( 'save_post', array( $this, 'save_podcast_post' ), 99 );
		add_action( 'edit_post', array( $this, 'save_podcast_post' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'post_metabox' ) );
	}

	/**
	 * Singleton page of Init class
	 *
	 * @return Init
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function post_metabox() {
		$screen = get_current_screen();

		if ( 'post' === $screen->id ) {
			$post_id = get_the_ID();
			$this->save_podcast_post( 'metabox', $post_id );
		}
	}

	/**
	 * @param null $option
	 * @param int  $post_id
	 *
	 * @return bool
	 */
	public function save_podcast_post( $option = null, $post_id = 0 ) {
		if ( 'metabox' === $option ) {
			$terms       = get_the_terms( $post_id, Series::get_slug() );
			$post_series = get_post_meta( $post_id, 'jnews_podcast_series', array() );
			if ( ! empty( $terms ) && isset( $terms[0] ) ) {
				if ( ! empty( $post_series ) ) {
					$post_series['id'] = $terms[0]->term_id;
				} else {
					$post_series = array(
						'id' => $terms[0]->term_id,
					);
				}
			}
			update_post_meta( $post_id, 'jnews_podcast_series', $post_series );
		} else {
			global $post;

			if ( isset( $post->post_type ) && 'post' == $post->post_type && isset( $_REQUEST['jnews_podcast_series'] ) && isset( $_REQUEST['jnews_podcast_series']['id'] ) ) {
				$term = get_term( (int) sanitize_text_field( $_REQUEST['jnews_podcast_series']['id'] ) );
				wp_set_post_terms( $post->ID, $term->slug, Series::get_slug() );
			}
		}

		return true;
	}

	/**
	 * Add video option metabox
	 */
	public function blog_metabox() {
		if ( class_exists( 'VP_Metabox' ) ) {
			new \VP_Metabox( JNEWS_PODCAST_CLASSPATH . 'metabox/post-single-podcast.php' );
			new \VP_Metabox( JNEWS_PODCAST_CLASSPATH . 'metabox/post-podcast-series.php' );
		}
	}

	public function load_admin_asset() {
		wp_enqueue_style( 'jnews-podcast-admin', JNEWS_PODCAST_URL . '/assets/css/admin/admin-style.css', null, JNEWS_PODCAST_VERSION );
		wp_enqueue_style( 'jnews-podcast-elementor', JNEWS_PODCAST_URL . '/assets/css/admin/elementor-backend.css', null, JNEWS_PODCAST_VERSION );
	}

	/**
	 * Load Init asset
	 */
	public function load_asset() {
		if ( ! is_admin() ) {
			wp_enqueue_style( 'jnews-podcast', JNEWS_PODCAST_URL . '/assets/css/plugin.css', null, JNEWS_PODCAST_VERSION );
			wp_enqueue_style( 'jnews-podcast-darkmode', JNEWS_PODCAST_URL . '/assets/css/darkmode.css', null, JNEWS_PODCAST_VERSION );
			wp_enqueue_script( 'jnews-podcast', JNEWS_PODCAST_URL . '/assets/js/plugin.js', array( 'jquery' ), JNEWS_PODCAST_VERSION, true );
			wp_localize_script( 'jnews-podcast', 'jnewspodcast', $this->localize_script() );
		}
	}

	/**
	 * @return mixed|void
	 */
	public function localize_script() {
		$option = array();

		$option['lang'] = array(
			'added_queue' => jnews_return_translation( 'Added to Queue', 'jnews-podcast', 'added_to_queue' ),
			'failed'      => jnews_return_translation( "There's something wrong", 'jnews-podcast', 'something_wrong' ),
		);

		$option['player_option'] = sanitize_title( get_bloginfo( 'name' ) ) . '-jnews-player';

		return apply_filters( 'jnews_podcast_asset_localize_script', $option );
	}

}
