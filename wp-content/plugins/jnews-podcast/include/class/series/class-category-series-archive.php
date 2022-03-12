<?php

/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Series;

use JNews\Archive\ArchiveAbstract;
use JNews\Module\ModuleManager;
use JNEWS_PODCAST\Module\Element\Podcast\Podcast_View_Abstract;
use JNEWS_PODCAST\Module\Module_Query;

/**
 * Class Category_Series_Archive
 *
 * @package JNEWS_PODCAST\Series
 */
class Category_Series_Archive extends ArchiveAbstract {

	/** @var string */
	private static $page_title;
	/** @var array */
	private $result;
	/** @var string */
	private $tag = 'jnews_podcast_archive_category_';

	/**
	 * Category_Series_Archive constructor.
	 *
	 * @param null $category
	 */
	public function __construct( $category = null ) {
		$this->result = self::retrive_podcast( $category );
	}

	/**
	 * @param $category
	 *
	 * @return array
	 */
	private static function retrive_podcast( $category ) {
		if ( null === $category ) {
			global $wp;
			$series = Series::get_instance();
			if ( $series->is_category_series_page( $wp ) ) {
				$category = $wp->query_vars['series-category'];
			} else {
				$category = get_categories(
					array(
						'hide_empty' => true,
						'order'      => 'rand',
						'exclude'    => get_cat_ID( 'Uncategorized' ),
					)
				);
				$category = ! empty( $category ) ? $category[0]->term_id : 0;
			}
		}
		if ( is_numeric( $category ) ) {
			$category = get_term( $category, 'category' );
		} else {
			if ( false !== strrpos( $category, '/' ) ) {
				$category = substr( $category, strrpos( $category, '/' ) + 1 );
			}
			$category = get_term_by( 'slug', $category, 'category', 'array' );
		}

		$list_podcast     = Module_Query::get_podcast_by_category( $category->term_id );
		self::$page_title = $category->name;

		return $list_podcast ? $list_podcast : array();
	}

	/**
	 * @param $category
	 */
	public static function page_title( $category ) {
		self::retrive_podcast( $category );
		add_filter( 'document_title_parts', array( 'JNEWS_PODCAST\Series\Category_Series_Archive', 'set_page_title' ) );
	}

	/**
	 * @param $title
	 *
	 * @return mixed
	 */
	public static function set_page_title( $title ) {
		$split      = $title;
		$additional = '';

		if ( isset( self::$page_title ) ) {
			$additional = self::$page_title;
		}

		global $wp_query;
		$split['title'] = isset( $wp_query->queried_object->post_title );

		if ( ! empty( $additional ) ) {
			$title['title'] = $additional . ' ' . $split['title'];
		}

		return $title;
	}

	/**
	 * @return string
	 */
	public function get_page_title() {
		return self::$page_title;
	}

	/**
	 * @param array $podcast_ids
	 *
	 * @return string
	 */
	public function render_content( $podcast_ids = array() ) {
		ModuleManager::getInstance()->set_width( array( $this->get_content_width() ) );
		$include_podcast = empty( $podcast_ids ) ? ( ! empty( $this->result ) ? implode( ',', $this->result ) : '-10' ) : implode( ',', $podcast_ids );

		$attr = array(
			'date_format'            => $this->get_content_date(),
			'date_format_custom'     => $this->get_content_date_custom(),
			'excerpt_length'         => $this->get_content_excerpt(),
			'pagination_mode'        => $this->get_content_pagination(),
			'boxed'                  => $this->get_boxed(),
			'boxed_shadow'           => $this->get_boxed_shadow(),
			'box_shadow'             => $this->get_box_shadow(),
			'pagination_number_post' => $this->get_posts_per_page(),
			'number_post'            => $this->get_posts_per_page(),
			'sort_by'                => 'index',
			'paged'                  => 1,
			'podcast_base_on'        => 'include_asc',
		);
		if ( ! empty( $include_podcast ) ) {
			$attr['include_podcast'] = $include_podcast . ',' . $include_podcast . ',' . $include_podcast;
		}

		$attr = apply_filters( 'jnews_get_content_attr', $attr, $this->tag, null );
		$name = apply_filters( 'jnews_get_content_layout', 'JNews_Block_' . $this->get_content_type(), $this->tag );
		$name = jnews_get_view_class_from_shortcode( $name );

		/** Create the module @var Podcast_View_Abstract $content_instance */
		$this->content_instance = jnews_get_module_instance( $name );
		$result                 = $this->content_instance->build_module( $attr );

		return "<div class='jnews_category_podcast'>
						{$result}
					</div>";
	}

	/**
	 * @return int
	 */
	public function get_content_width() {
		$width = parent::get_content_width();

		if ( in_array( $this->get_page_layout(), array( 'right-sidebar', 'left-sidebar' ), true ) ) {
			$sidebar = $this->get_content_sidebar();
			if ( ! is_active_sidebar( $sidebar ) ) {
				return 12;
			}
		}

		return $width;
	}

	/**
	 * @return mixed|void
	 */
	public function get_page_layout() {
		 return apply_filters( $this->tag . 'page_layout', get_theme_mod( $this->tag . 'page_layout', 'right-sidebar' ) );
	}

	/**
	 * @return mixed|void
	 */
	public function get_content_sidebar() {
		 return apply_filters( $this->tag . 'sidebar', get_theme_mod( $this->tag . 'sidebar', 'default-sidebar' ) );
	}

	public function get_content_date() {
		// TODO: Implement get_content_date() method.
	}

	public function get_content_date_custom() {
		 // TODO: Implement get_content_date_custom() method.
	}

	/**
	 * @return mixed|void
	 */
	public function get_content_excerpt() {
		 return apply_filters( $this->tag . 'content_excerpt', get_theme_mod( $this->tag . 'content_excerpt', 20 ) );
	}

	/**
	 * @return mixed|void
	 */
	public function get_content_pagination() {
		return apply_filters( $this->tag . 'content_pagination', get_theme_mod( $this->tag . 'content_pagination', 'scrollload' ) );
	}

	/**
	 * @return bool|mixed|void
	 */
	public function get_boxed() {
		return apply_filters( $this->tag . 'boxed', get_theme_mod( $this->tag . 'boxed', false ) );
	}

	/**
	 * @return bool|mixed|void
	 */
	public function get_boxed_shadow() {
		if ( ! $this->get_boxed() ) {
			return false;
		}

		return apply_filters( $this->tag . 'boxed_shadow', get_theme_mod( $this->tag . 'boxed_shadow', false ) );
	}

	public function get_box_shadow() {
		// TODO: Implement get_box_shadow() method.
	}

	/**
	 * @return mixed|void
	 */
	public function get_posts_per_page() {
		$posts_per_page = apply_filters( $this->tag . 'posts_per_page', get_theme_mod( $this->tag . 'posts_per_page', false ) );

		return $posts_per_page ? $posts_per_page : get_option( 'posts_per_page' );
	}

	/**
	 * @return mixed|void
	 */
	public function get_content_type() {
		return apply_filters( $this->tag . 'content', get_theme_mod( $this->tag . 'content', 'podcast_2' ) );
	}

	/**
	 * @return mixed|void
	 */
	public function get_second_sidebar() {
		return apply_filters( $this->tag . 'second_sidebar', get_theme_mod( $this->tag . 'second_sidebar', 'default-sidebar' ) );
	}

	/**
	 * @return mixed|void
	 */
	public function sticky_sidebar() {
		return apply_filters( $this->tag . 'sticky_sidebar', get_theme_mod( $this->tag . 'sticky_sidebar', true ) );
	}

	public function get_content_pagination_limit() {
		// TODO: Implement get_content_pagination_limit() method.
	}

	public function get_content_pagination_align() {
		// TODO: Implement get_content_pagination_align() method.
	}

	public function get_content_pagination_navtext() {
		// TODO: Implement get_content_pagination_navtext() method.
	}

	public function get_content_pagination_pageinfo() {
		 // TODO: Implement get_content_pagination_pageinfo() method.
	}
}
