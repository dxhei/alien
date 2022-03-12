<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Customizer;

use JNEWS_PODCAST\Series\Series;

/**
 * Class Podcast_Customizer
 *
 * @package JNEWS_PODCAST\Customizer
 */
class Podcast_Customizer {

	/**
	 * Instance of Podcast_Customizer
	 *
	 * @var Podcast_Customizer
	 */
	private static $instance;

	/**
	 * @var \Jeg\Customizer\Customizer
	 */
	private $customizer;

	/**
	 * @var string $slug
	 */
	private $slug;

	/**
	 * Podcast_Customizer constructor.
	 */
	private function __construct() {
		$this->slug = Series::get_slug();
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_control_css' ) );
		add_action( 'jeg_register_customizer_option', array( $this, 'customizer_option' ) );
		add_filter( 'jeg_register_lazy_section', array( $this, 'jnews_podcast_lazy_section' ), 99 );
		add_filter( 'jnews_setup_redirect_tag', array( $this, 'setup_redirect_tag' ) );
	}

	/**
	 * Singleton page for Podcast_Customizer class
	 *
	 * @return Podcast_Customizer
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Load additional customizer style
	 */
	public function customize_control_css() {
		wp_enqueue_style( 'jnews-podcast-additional-customizer', JNEWS_PODCAST_URL . '/assets/css/admin/additional-customizer.css', null, JNEWS_PODCAST_VERSION );
	}

	/**
	 * Setup redirect tag for customizer
	 *
	 * @param $redirect_tag
	 *
	 * @return mixed
	 */
	public function setup_redirect_tag( $redirect_tag ) {
		$redirect_tag['single_series'] = array(
			'url'  => $this->get_random_url( '', 'podcast' ),
			'flag' => $this->is_single( 'podcast' ),
			'text' => esc_html__( 'Single Series', 'jnews-podcast' ),
		);

		$redirect_tag['podcast_category_tag'] = array(
			'url'  => $this->get_random_url( '', 'podcast_category' ),
			'flag' => $this->is_single( 'podcast_category' ),
			'text' => esc_html__( 'Podcast Category', 'jnews-podcast' ),
		);

		return $redirect_tag;
	}

	/**
	 * Get random URL of post_type
	 *
	 * @param $args
	 *
	 * @param string $type
	 *
	 * @return false|string|null
	 */
	public function get_random_url( $args, $type = 'post' ) {
		$terms = jnews_get_series( array( 'hide_empty' => true ) );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			shuffle( $terms );
		}
		switch ( $type ) {
			case 'podcast_category':
				$categories = get_categories(
					array(
						'hide_empty' => true,
						'order'      => 'rand',
						'exclude'    => get_cat_ID( 'Uncategorized' ),
					)
				);
				$category   = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[ array_rand( $categories ) ]->term_id : get_cat_ID( 'Uncategorized' );
				$url        = jnews_podcast_get_category_link( $category );
				break;
			case 'podcast':
				$url = ! empty( $terms ) && ! is_wp_error( $terms ) ? get_term_link( $terms[ array_rand( $terms ) ]->term_id ) : null;
				break;
			default:
				$defaults = array(
					'orderby'     => 'rand',
					'numberposts' => 1,
					'tax_query'   => array(
						array(
							'taxonomy' => $this->slug,
							'field'    => 'term_id',
							'terms'    => $terms[0]->term_id,
						),
					),
				);
				$args     = wp_parse_args( $args, $defaults );

				$posts = get_posts( $args );

				if ( $posts ) {
					$url = get_permalink( $posts[ array_rand( $posts ) ]->ID );
				} else {
					$url = null;
				}
				break;
		}

		return $url;
	}

	/**
	 * Check if the right single page
	 *
	 * @param $type
	 *
	 * @return bool
	 */
	public function is_single( $type ) {
		switch ( $type ) {
			case 'podcast_category':
				global $wp;
				$series = Series::get_instance();
				$result = ( $series->is_category_series_page( $wp ) );
				break;
			case 'podcast':
				$result = ( is_archive() && has_term( '', $this->slug ) );
				break;
			default:
				$result = false;
				break;
		}

		return $result;
	}

	/**
	 * Register new customizer option
	 */
	public function customizer_option() {
		if ( class_exists( '\Jeg\Customizer\Customizer' ) ) {
			$this->customizer = \Jeg\Customizer\Customizer::get_instance();

			$this->set_panel();
			$this->set_section();
		}
	}

	/**
	 * Set new panel customizer
	 */
	public function set_panel() {
		$this->customizer->add_panel(
			array(
				'id'          => 'jnews_podcast',
				'title'       => esc_html__( 'JNews : Podcast Setting', 'jnews-podcast' ),
				'description' => esc_html__( 'JNews podcast setting.', 'jnews-podcast' ),
				'priority'    => 192,
			)
		);
	}

	/**
	 * Set new section panel
	 */
	public function set_section() {

		$podcast_general = array(
			'id'       => 'jnews_podcast_general',
			'title'    => esc_html__( 'Podcast General Settings', 'jnews-podcast' ),
			'panel'    => 'jnews_podcast',
			'priority' => 250,
			'type'     => 'jnews-lazy-section',
		);

		$podcast_template = array(
			'id'       => 'jnews_podcast_template',
			'title'    => esc_html__( 'Podcast Template & Layout Settings', 'jnews-podcast' ),
			'panel'    => 'jnews_podcast',
			'priority' => 250,
			'type'     => 'jnews-lazy-section',
		);

		$podcast_category_template = array(
			'id'       => 'jnews_podcast_category_template',
			'title'    => esc_html__( 'Podcast Category Template Settings', 'jnews-podcast' ),
			'panel'    => 'jnews_podcast',
			'priority' => 250,
			'type'     => 'jnews-lazy-section',
		);

		$podcast_powerpress = array(
			'id'       => 'jnews_podcast_powerpress',
			'title'    => esc_html__( 'PowerPress Settings', 'jnews-podcast' ),
			'panel'    => 'jnews_podcast',
			'priority' => 250,
			'type'     => 'jnews-lazy-section',
		);

		$this->customizer->add_section( $podcast_general );
		$this->customizer->add_section( $podcast_template );
		$this->customizer->add_section( $podcast_category_template );
		$this->customizer->add_section( $podcast_powerpress );
	}

	/**
	 * Register new section and their respective file
	 *
	 * @param $result
	 *
	 * @return mixed
	 */
	public function jnews_podcast_lazy_section( $result ) {
		$result['jnews_podcast_general'][]           = JNEWS_PODCAST_CLASSPATH . 'customizer/sections/general-settings.php';
		$result['jnews_podcast_template'][]          = JNEWS_PODCAST_CLASSPATH . 'customizer/sections/template-settings.php';
		$result['jnews_podcast_category_template'][] = JNEWS_PODCAST_CLASSPATH . 'customizer/sections/archive-series-category-settings.php';
		$result['jnews_podcast_powerpress'][]        = JNEWS_PODCAST_CLASSPATH . 'customizer/sections/powerpress-settings.php';

		return $result;
	}
}
