<?php

namespace JNEWS_PODCAST\Series;

use JNews\Image\ImageNormalLoad;
use JNews\Module\ModuleManager;

/**
 * Class Single_Series
 *
 * @package JNEWS_PODCAST\Series
 */
class Single_Series extends Object_Series {
	/**
	 * Instance of Object_Series
	 *
	 * @var Object_Series
	 */
	private static $instance;
	private $result;

	/**
	 * Single_Series constructor.
	 */
	private function __construct() {
		add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
		$this->set_series_id( get_queried_object_id() );

		$archive      = array();
		$this->result = $archive;
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				do_action( 'jnews_json_archive_push', get_the_ID() );
				$this->result[] = get_post();
			}
		}
	}

	/**
	 * Singleton page of Object_Series class
	 *
	 * @return Object_Series
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * @return mixed
	 */
	public function render_content() {
		$content_width = array( $this->get_content_width() );
		ModuleManager::getInstance()->set_width( $content_width );

		$post_per_page = get_option( 'posts_per_page' );

		$attr = array(
			'pagination_number_post'  => $post_per_page,
			'disable_podcast_detail'  => true,
			'number_post'             => $post_per_page,
			'include_podcast_episode' => $this->series_id,
			'sort_by'                 => 'latest',
			'pagination_mode'         => 'loadmore',
			'paged'                   => jnews_get_post_current_page(),
			'pagination_align'        => 'center',
			'pagination_navtext'      => false,
			'pagination_pageinfo'     => false,
			'push_archive'            => true,
		);

		if ( $this->get_enable_post_excerpt() ) {
			$attr['enable_post_excerpt'] = 'true';
			$attr['excerpt_length']      = $this->get_excerpt_length();
			$attr['excerpt_ellipsis']    = $this->get_excerpt_ellipsis();
		}

		$name                   = 'JNews_Podcast_Episodedetail';
		$name                   = jnews_get_view_class_from_shortcode( $name );
		$this->content_instance = jnews_get_module_instance( $name );

		return null !== $this->content_instance ? $this->content_instance->build_module( $attr ) : '';
	}

	/**
	 * @return int
	 */
	public function get_content_width() {
		$template = $this->get_template();
		$layout   = $this->get_layout();

		if ( '2' !== $template ) {
			switch ( $layout ) {
				case 'right-sidebar':
				case 'left-sidebar':
					return 8;
					break;
			}
		}

		return 12;
	}

	/**
	 * @return mixed|void
	 */
	public function get_template() {
		$template = jnews_podcast_option( 'single_podcast_template', '2' );

		return apply_filters( 'jnews_single_podcast_template', $template, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function get_layout() {
		$layout = jnews_podcast_option( 'single_podcast_layout', 'left-sidebar' );

		return apply_filters( 'jnews_single_podcast_layout', $layout, $this->series_id );
	}

	public function get_enable_post_excerpt() {
		$post_excerpt = jnews_podcast_option( 'enable_post_excerpt', false );

		return apply_filters( 'jnews_single_podcast_enable_excerpt', $post_excerpt, $this->series_id );
	}

	public function get_excerpt_length() {
		$excerpt_length = jnews_podcast_option( 'excerpt_length', 20 );

		return apply_filters( 'jnews_single_podcast_excerpt_length', $excerpt_length, $this->series_id );
	}

	public function get_excerpt_ellipsis() {
		$excerpt_ellipsis = jnews_podcast_option( 'excerpt_ellipsis', '...' );

		return apply_filters( 'jnews_single_podcast_excerpt_ellipsis', $excerpt_ellipsis, $this->series_id );
	}

	/**
	 * Set main class
	 */
	public function main_class() {
		$layout   = $this->get_layout();
		$template = $this->get_template();

		if ( '2' !== $template ) {
			switch ( $layout ) {
				case 'right-sidebar':
					echo 'jeg_sidebar_right';
					break;
				case 'left-sidebar':
					echo 'jeg_sidebar_left';
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Set Template class
	 */
	public function template_class() {
		$is_archive_podcast = is_archive() && is_tax( self::$slug );
		if ( $is_archive_podcast ) {
			$classes = 'jeg_single_podcast_' . $this->get_template();
			echo jnews_sanitize_output( $classes ); // phpcs:ignore
		}
	}

	/**
	 * Post Meta
	 */
	public function render_post_meta() {
		if ( $this->show_post_meta() ) {
			jnews_podcast_get_template_part( '/include/fragment/podcast/podcast-meta' );
		}
	}

	/**
	 * render podcast option
	 */
	public function render_post_option() {
		if ( $this->show_post_option() ) {
			jnews_podcast_get_template_part( '/include/fragment/podcast/podcast-option' );
		}
	}

	/**
	 * @return mixed|void
	 */
	public function show_post_meta() {
		$flag = jnews_podcast_option( 'single_podcast_show_post_meta', true );

		return apply_filters( 'jnews_single_podcast_show_post_meta', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_author_meta() {
		$flag = jnews_podcast_option( 'single_podcast_show_podcast_author', true );

		return apply_filters( 'jnews_single_podcast_show_podcast_author', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_author_meta_image() {
		$flag = jnews_podcast_option( 'single_podcast_show_podcast_author_image', false );

		return apply_filters( 'jnews_single_podcast_show_podcast_author_image', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_total_episode_meta() {
		$flag = jnews_podcast_option( 'single_podcast_show_podcast_total_episode', true );

		return apply_filters( 'jnews_single_podcast_show_podcast_total_episode', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_post_option() {
		$flag = jnews_podcast_option( 'single_podcast_show_post_option', true );

		return apply_filters( 'jnews_single_podcast_show_post_option', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_subscribe_button() {
		$flag = jnews_podcast_option( 'single_podcast_show_subscribe', true );

		return apply_filters( 'jnews_single_podcast_show_subscribe', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_share_button() {
		$flag = jnews_podcast_option( 'single_podcast_show_share_button', true );

		return apply_filters( 'jnews_single_podcast_show_share_button', $flag, $this->series_id );
	}

	/**
	 * @return mixed|void
	 */
	public function show_more_option() {
		$flag = jnews_podcast_option( 'single_podcast_show_more_option', true );

		return apply_filters( 'jnews_single_podcast_show_more_option', $flag, $this->series_id );
	}

	/**
	 * @param $size
	 *
	 * @return mixed|string|void
	 */
	public function featured_image( $size ) {
		if ( $this->show_featured_image() && $this->has_term_thumbnail( $this->series_id ) ) {
			$edit_url = '';
			if ( is_user_logged_in() && ! defined( 'JNEWS_SANDBOX_URL' ) ) {
				$meta   = jnews_podcast_attribute(
					$this->get_series_id(),
					array(
						'fields' => array( 'author' ),
					)
				);
				$author = $meta['author'];
				if ( (int) $author === get_current_user_id() ) {
					$edit_url = jnews_edit_post( $this->get_series_id(), 'left', 'podcast' );
				}
			}
			$image_url = $this->get_featured_image_src( $size );
			$image_id  = self::get_series_image_id( $this->series_id );
			$image     = apply_filters( 'jnews_single_image_unwrap', $image_id, $size );
			if ( 'lazyload' !== get_theme_mod( 'jnews_image_load', 'lazyload' ) ) {
				$image = ImageNormalLoad::getInstance()->single_image_unwrap( $image_id, $size );
			}
			$output  = '<div class="jeg_featured featured_image">' . $edit_url;
			$popup   = get_theme_mod( 'jnews_single_popup_script', 'magnific' );
			$output .= ( 'disable' !== $popup ) ? "<a href=\"{$image_url}\">" : '';
			$output .= $image;
			$output .= ( 'disable' !== $popup ) ? '</a>' : '';
			$output .= '</div>';

			return apply_filters( 'jnews_featured_image', $output, $this->series_id );
		}

		return '';
	}

	/**
	 * @return mixed|void
	 */
	public function show_featured_image() {
		$flag = jnews_podcast_option( 'single_podcast_show_featured', true );

		return apply_filters( 'jnews_single_podcast_show_featured', $flag, $this->series_id );
	}

	/**
	 * @param $size
	 *
	 * @return mixed
	 */
	public function get_featured_image_src( $size ) {
		$term_image = self::get_series_image_id( $this->series_id );
		$image      = wp_get_attachment_image_src( $term_image, $size );

		return $image[0];
	}

}
