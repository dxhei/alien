<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE;

/**
 * Class JNews_Subscribe_Post_Type
 *
 * @package JNEWS_SUBSCRIBE
 */
class JNews_Subscribe_Post_Type {

	/**
	 * Instance of JNews_Subscribe_Post_Type
	 *
	 * @var JNews_Subscribe_Post_Type
	 */
	private static $instance;

	/**
	 * JNews_Subscribe_Post_Type constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'subscribe_post_type' ) );
	}

	/**
	 * Singleton page of JNews_Subscribe_Post_Type class
	 *
	 * @return JNews_Subscribe_Post_Type
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register jnews-download CPT
	 */
	public function subscribe_post_type() {
		jnews_register_post_type(
			'jnews-download',
			array(
				'labels'              => array(
					'name'                  => esc_html__( 'Add Download Files', 'jnews-subscribe' ),
					'singular_name'         => esc_html__( 'Download File', 'jnews-subscribe' ),
					'menu_name'             => esc_html__( 'Download Files', 'jnews-subscribe' ),
					'add_new'               => esc_html__( 'Add New', 'jnews-subscribe' ),
					'add_new_item'          => esc_html__( 'Add New File', 'jnews-subscribe' ),
					'edit_item'             => esc_html__( 'Edit File', 'jnews-subscribe' ),
					'new_item'              => esc_html__( 'New File', 'jnews-subscribe' ),
					'all_item'              => esc_html__( 'All Download Files', 'jnews-subscribe' ),
					'view_item'             => esc_html__( 'View File', 'jnews-subscribe' ),
					'search_items'          => esc_html__( 'Search File', 'jnews-subscribe' ),
					'not_found'             => esc_html__( 'No File found', 'jnews-subscribe' ),
					'not_found_in_trash'    => esc_html__( 'No File in Trash', 'jnews-subscribe' ),
					'parent_item_colon'     => '',
					'featured_image'        => esc_html__( 'File Image', 'jnews-subscribe' ),
					'set_featured_image'    => esc_html__( 'Set File Image', 'jnews-subscribe' ),
					'remove_featured_image' => esc_html__( 'Remove File Image', 'jnews-subscribe' ),
					'use_featured_image'    => esc_html__( 'Use as File Image', 'jnews-subscribe' ),
					'attributes'            => esc_html__( 'File Attributes', 'jnews-subscribe' ),
					'filter_items_list'     => esc_html__( 'Filter Files list', 'jnews-subscribe' ),
					'items_list_navigation' => esc_html__( 'Files list navigation', 'jnews-subscribe' ),
					'items_list'            => esc_html__( 'File list', 'jnews-subscribe' ),
				),
				'public'              => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'show_in_menu'        => true,
				'query_var'           => true,
				'menu_icon'           => 'dashicons-download',
				'menu_position'       => 9,
				'rewrite'             => array(
					'slug'       => 'jnews-download',
					'with_front' => false,
				),
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'has_archive'         => false,
				'hierarchical'        => false,
				'supports'            => apply_filters(
					'jnews_download_supports',
					array(
						'title',
						'thumbnail',
					)
				),
			)
		);

	}

}
