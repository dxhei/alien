<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Series;

use JNews\Util\VideoAttribute;

/**
 * Class Series
 *
 * @package JNEWS_PODCAST\Series
 */
class Series extends Object_Series {

	/**
	 * Hold instance of object
	 *
	 * @var array
	 */
	private static $object;

	/**
	 * Hold supported import plugin
	 *
	 * @var array
	 */
	private static $supported = array(
		'powerpress_taxonomy_',
	);

	/**
	 * Hold Supported source
	 *
	 * @var array
	 */
	private static $import_source = array(
		'powerpress-anchor-rss-podcast',
		'powerpress-soundcloud-rss-podcast',
	);

	/**
	 * @var string
	 */
	private $async_import_image = 'jnews_podcast_import_image';

	/**
	 * Instance of Object_Series
	 *
	 * @var Object_Series
	 */
	private static $instance;
	/**
	 * @var
	 */
	private static $endpoint;
	/**
	 * Hold Category
	 *
	 * @var array $categories
	 */
	private $categories = array();

	/**
	 * Series constructor.
	 */
	private function __construct() {
		$this->setup_hook();
		$this->setup_endpoint();
	}

	/**
	 * Setup Series
	 */
	private function setup_hook() {
		add_action( 'after_setup_theme', array( $this, 'option_load' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'wp', array( $this, 'remove_player' ) );
		add_filter( 'template_include', array( $this, 'podcast_template' ) );
		$taxonomy = self::$slug;
		add_action( "delete_{$taxonomy}", array( $this, 'clear_taxonomy_data' ) );

		/**
		 * Integration powerpress
		 */
		$meta_type = 'post';
		add_action( "wp_ajax_{$this->async_import_image}", array( $this, 'async_import_image' ) );
		add_action( "wp_ajax_nopriv_{$this->async_import_image}", array( $this, 'async_import_image' ) );
		add_action( "added_{$meta_type}_meta", array( $this, 'get_post_detail_from_import' ), 10, 4 );
		add_action( 'set_object_terms', array( $this, 'get_podcast_image_from_taxonomy' ), 10, 4 );
		add_filter( 'pre_update_option', array( $this, 'get_taxonomy_detail_from_import' ), 10, 3 );
		add_action( 'save_post', array( $this, 'get_post_detail_from_powerpress' ), 99 );
	}

	/**
	 * Setup Category Series endpoint
	 */
	protected function setup_endpoint() {
		$endpoint = array(
			'series-category' => array(
				'slug'  => 'series-category',
				'label' => 'series_category',
				'title' => esc_html__( 'Category', 'jnews-podcast' ),
			),

		);

		self::$endpoint = apply_filters( 'jnews_podcast_page_endpoint', $endpoint );
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
	 * @param $post_id
	 */
	public function get_post_detail_from_powerpress( $post_id ) {
		if ( defined( 'POWERPRESS_VERSION' ) ) {
			$podcast_option = get_post_meta( $post_id, 'jnews_podcast_option', true );
			if ( is_array( $podcast_option ) && ! empty( $podcast_option ) ) {
				$enable_podcast   = isset( $podcast_option['enable_podcast'] ) ? '1' === $podcast_option['enable_podcast'] : false;
				$podcast_duration = isset( $podcast_option['podcast_duration'] ) ? $podcast_option['podcast_duration'] : '';
				$podcast_url      = isset( $podcast_option['upload'] ) ? $podcast_option['upload'] : '';
				if ( $enable_podcast && empty( $podcast_duration ) && empty( $podcast_url ) && jnews_get_powerpress_option( 'powerpress_import_override_post_detail', true ) ) {
					$powerpress_meta  = powerpress_get_enclosure_data( $post_id );
					$podcast_duration = $powerpress_meta['duration'];
					$podcast_url      = $powerpress_meta['url'];
					$podcast_option   = array(
						'enable_podcast'   => '1',
						'podcast_duration' => $podcast_duration,
						'upload'           => $podcast_url,
					);
					update_post_meta( $post_id, 'jnews_podcast_option', $podcast_option );
				}
			}
		}
	}

	/**
	 * Register Podcast template
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function podcast_template( $template ) {
		global $wp;
		$is_archive_podcast = is_archive() && is_tax( self::$slug );

		if ( $this->is_category_series_page( $wp ) ) {
			Category_Series_Archive::page_title( $wp->query_vars['series-category'] );
			$template = JNEWS_PODCAST_TEMPLATE . '/podcast/single-archive-category.php';
		}

		if ( $is_archive_podcast ) {
			$template = JNEWS_PODCAST_TEMPLATE . '/podcast/single-podcast.php';
		}

		return $template;
	}

	/**
	 * Check the category page
	 *
	 * @param $wp
	 *
	 * @return bool
	 */
	public function is_category_series_page( $wp ) {
		$bool = false;
		if ( ! is_admin() ) {
			foreach ( self::$endpoint as $key => $value ) {
				if ( isset( $wp->query_vars[ $value['slug'] ] ) && ! empty( $wp->query_vars[ $value['slug'] ] ) ) {
					$bool = true;
				}
			}
		}

		return $bool;
	}

	/**
	 * Clear taxonomy data when deleted
	 *
	 * @param $term
	 */
	public function clear_taxonomy_data( $term ) {
		$taxonomy_option = array(
			'jnews_' . self::$slug . '_podcast_image',
			'jnews_' . self::$slug . '_term_image',
			'powerpress_taxonomy_podcasting',
			"powerpress_taxonomy_{$term}",
		);
		foreach ( $taxonomy_option as $index => $value ) {
			$key    = $term;
			$option = get_option( $value, array() );
			if ( "powerpress_taxonomy_{$key}" !== $value ) {
				if ( ! empty( $option ) ) {
					unset( $option[ $key ] );
					update_option( $value, $option );
				}
			} else {
				delete_option( $value );
			}
		}
	}

	/**
	 * Update taxonomy series when import podcast
	 *
	 * @param $value
	 * @param $option
	 * @param $old_value
	 *
	 * @return mixed
	 */
	public function get_taxonomy_detail_from_import( $value, $option, $old_value ) {
		if ( jnews_get_powerpress_option( 'powerpress_import_override_term_taxonomy_detail', false ) ) {
			$do_import = false;
			if ( isset( $_REQUEST['import'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				// podcast_log( 'Do check supported source' );
				foreach ( self::$import_source as $import_source ) {
					if ( false !== strpos( $_REQUEST['import'], $import_source ) ) { // phpcs:ignore WordPress.Security.NonceVerification
						$do_import = true;
						break;
					}
					$do_import = false;
				}
				// podcast_log( 'Do check supported plugin' );
				foreach ( self::$supported as $supported ) {
					if ( false !== strpos( $option, $supported ) ) {
						$do_import = true;
						break;
					}
					$do_import = false;
				}
			}
			if ( $do_import ) {
				// podcast_log( 'Plugin and Source supported, Do check taxonomy' );
				$taxonomy_id = str_replace( $supported, '', $option );
				$term        = get_term( $taxonomy_id );
				if ( ( $term->taxonomy === self::$slug ) && $value && is_array( $value ) ) {
					if ( jnews_get_powerpress_option( 'powerpress_import_override_post_category', true ) ) {
						$itunes_categories = array();
						if ( ! empty( $value['apple_cat_1'] ) ) {
							$itunes_categories['apple_cat_1'] = $value['apple_cat_1'];
						}
						if ( ! empty( $value['apple_cat_2'] ) ) {
							$itunes_categories['apple_cat_2'] = $value['apple_cat_2'];
						}
						if ( ! empty( $value['apple_cat_3'] ) ) {
							$itunes_categories['apple_cat_3'] = $value['apple_cat_3'];
						}
						if ( ! empty( $value['itunes_cat_1'] ) ) {
							$itunes_categories['itunes_cat_1'] = $value['itunes_cat_1'];
						}
						if ( ! empty( $value['itunes_cat_2'] ) ) {
							$itunes_categories['itunes_cat_2'] = $value['itunes_cat_2'];
						}
						if ( ! empty( $value['itunes_cat_3'] ) ) {
							$itunes_categories['itunes_cat_3'] = $value['itunes_cat_3'];
						}
						$categories       = $this->get_itunes_categories( $itunes_categories );
						$this->categories = $categories;
					}
					$args = array(
						'action_type' => 'import_podcast_detail',
						'taxonomy_id' => $taxonomy_id,
						'value'       => $value,
						'term'        => $term,
					);
					$this->async_request( $args );
				}
			}
		}

		return $value;
	}

	/**
	 * Handle Itunes Categories
	 *
	 * @param $itunes_categories
	 *
	 * @return array
	 */
	private function get_itunes_categories( $itunes_categories ) {
		$categories = array();
		if ( defined( 'POWERPRESS_VERSION' ) ) {
			$apple_category  = powerpress_apple_categories();
			$itunes_category = powerpress_itunes_categories();
			foreach ( $itunes_categories as $key => $values ) {
				$args                 = array();
				list( $cat, $subcat ) = explode( '-', $values );
				if ( $cat ) {
					$cat_title     = false !== strpos( $key, 'apple' ) ? $apple_category[ $cat . '-00' ] : $itunes_category[ $cat . '-00' ];
					$args['title'] = $cat_title;
					$cat_id        = $this->create_taxonomy( $args );
					if ( '00' !== $subcat ) {
						$cat_title     = false !== strpos( $key, 'apple' ) ? $apple_category[ $cat . '-' . $subcat ] : $itunes_category[ $cat . '-' . $subcat ];
						$args['title'] = $cat_title;
						if ( $cat_id ) {
							$args['parent'] = $cat_id;
						}
						$cat_id = $this->create_taxonomy( $args );
					}
					if ( $cat_id ) {
						$categories[] = $cat_id;
					}
				}
			}
		}

		return $categories;
	}

	/**
	 * Create Taxonomy
	 *
	 * @param $args
	 *
	 * @return array|bool|int|mixed|\WP_Error
	 */
	private function create_taxonomy( $args ) {
		$term = VideoAttribute::getInstance()->create_taxonomy( $args );
		if ( is_wp_error( $term ) ) {
			$term = false;
		} else {
			$term_exist = term_exists( (int) $term, 'category' );
			if ( 0 === $term_exist['term_id'] || null === $term_exist['term_id'] ) {
				$term = false;
			}
		}

		return $term;
	}

	/**
	 * Use default image from podcast series for podcast post
	 *
	 * @param $object_id
	 * @param $terms
	 * @param $tt_ids
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public function get_podcast_image_from_taxonomy( $object_id, $terms, $tt_ids, $taxonomy ) {
		if ( ( $taxonomy === self::$slug ) && jnews_get_powerpress_option( 'powerpress_import_use_taxonomy_image', false ) && isset( $tt_ids[0] ) ) {
			// podcast_log( 'Do check podcast default images' );
			$args = array(
				'action_type' => 'import_episode_default_image',
				'object_id'   => $object_id,
				'tt_ids'      => $tt_ids[0],
			);
			$this->async_request( $args );
		}

		return $object_id;
	}

	/**
	 * Integration powerpress/powerpressadmin-rss-import.php
	 * PowerPress_RSS_Podcast_Import->_import_post_to_db()
	 * We need to get the episode detail.
	 *
	 * Update podcast post when import podcast.
	 *
	 * @param $mid
	 * @param $object_id
	 * @param $meta_key
	 * @param $_meta_value
	 */
	public function get_post_detail_from_import( $mid, $object_id, $meta_key, $_meta_value ) {
		if ( jnews_get_powerpress_option( 'powerpress_import_override_post_detail', true ) && 'enclosure' === $meta_key ) {
			$args = array(
				'action_type' => 'import_episode_detail',
				'object_id'   => $object_id,
				'_meta_value' => $_meta_value,
				'categories'  => $this->categories,
			);
			$this->async_request( $args );
		}
	}

	/**
	 * Dispatch async request
	 *
	 * @param $args
	 */
	public function async_request( $args ) {
		$query    = array(
			'action' => $this->async_import_image,
			'nonce'  => wp_create_nonce( $this->async_import_image ),
		);
		$request  = array(
			'method'    => 'POST',
			'body'      => $args,
			'timeout'   => 0.01,
			'blocking'  => false,
			'cookies'   => $_COOKIE,
			'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
		);
		$url      = add_query_arg( $query, admin_url( 'admin-ajax.php' ) );
		$response = wp_remote_request( $url, $request );
		if ( is_wp_error( $response ) ) {
			// podcast_log( 'Error ' . $response->get_error_code() . ' : ' . $response->get_error_message() );
		}
	}

	/**
	 * Async request import image
	 */
	public function async_import_image() {
		session_write_close();
		check_ajax_referer( $this->async_import_image, 'nonce' );
		if ( isset( $_POST['action_type'] ) && ! empty( $_POST['action_type'] ) ) {
			$action_type = sanitize_key( $_POST['action_type'] );
			switch ( $action_type ) {
				case 'import_episode_image':
					$this->import_episode_image();
					break;
				case 'import_podcast_detail':
					$this->import_podcast_detail();
					break;
				case 'import_episode_detail':
					$this->import_episode_detail();
					break;
				case 'import_podcast_image':
					$this->import_podcast_image();
					break;
				case 'import_episode_default_image':
					$this->import_episode_default_image();
					break;
			}
		}
		wp_die();
	}

	/**
	 * Import podcast detail
	 */
	private function import_podcast_detail() {
		if ( isset( $_POST['taxonomy_id'], $_POST['value'], $_POST['term'] ) ) {
			$taxonomy_id = (int) sanitize_text_field( $_POST['taxonomy_id'] );
			// podcast_log( 'Taxonomy jnews-series and rss image available, Upload Image ' );
			if ( isset( $_POST['value']['rss2_image'] ) && ! empty( $_POST['value']['rss2_image'] ) ) {
				$attachment_id = VideoAttribute::getInstance()->save_to_media_library( 0, esc_url( $_POST['value']['rss2_image'] ) );
				if ( ! empty( $attachment_id ) ) {
					// podcast_log( 'Taxonomy jnews-series and rss image available, Upload Image ' );
					$this->set_series_image( $attachment_id, $taxonomy_id, false );
				}
			}
			if ( empty( $_POST['term']->description ) ) {

				$term_args = array(
					'description' => $_POST['value']['description'],
				);
				wp_update_term( $taxonomy_id, self::$slug, $term_args );
			}
		}
	}

	private function import_episode_detail() {
		if ( isset( $_POST['object_id'], $_POST['_meta_value'] ) ) {
			$object_id      = (int) sanitize_text_field( $_POST['object_id'] );
			$podcast_option = get_post_meta( $object_id, 'jnews_podcast_option', true );
			if ( ! is_array( $podcast_option ) ) {
				$podcast_option = array();
			}
			if ( jnews_get_powerpress_option( 'powerpress_import_override_post_category', true ) && isset( $_POST['categories'] ) && ! empty( $_POST['categories'] ) ) {
				wp_set_post_categories( $object_id, $_POST['categories'] );
			}
			// podcast_log( 'Powerpress Detected, Do check Data' );
			$meta_data                        = $_POST['_meta_value'];
			$meta_parts                       = explode( "\n", $meta_data, 4 );
			$podcast_option['enable_podcast'] = '1';
			$podcast_option['upload']         = $meta_parts[0];
			// podcast_log( $meta_parts );
			if ( isset( $meta_parts[3] ) && ! empty( $meta_parts[3] ) ) {
				// podcast_log( 'Data avaiable, Get Itunes Detail' );
				$unserialize_data = maybe_unserialize( str_replace( '\\"', '"', $meta_parts[3] ) );
				if ( $unserialize_data && is_array( $unserialize_data ) ) {
					$podcast_option['podcast_duration'] = jeg_video_duration( $unserialize_data['duration'] );
					// podcast_log( $unserialize_data );
					if ( ! jnews_get_powerpress_option( 'powerpress_import_use_taxonomy_image', false ) && isset( $unserialize_data['itunes_image'] ) && ! empty( $unserialize_data['itunes_image'] ) ) {
						// podcast_log( 'Itunes image detected, save image' );
						$attachment_id = VideoAttribute::getInstance()->save_to_media_library( $object_id, $unserialize_data['itunes_image'] );
						// podcast_log( 'Image ID : ' . $attachment_id );
						if ( ! empty( $attachment_id ) ) {
							// podcast_log( 'Image Saved, set as featured Image' );
							VideoAttribute::getInstance()->set_featured_image( $object_id, $attachment_id, $unserialize_data['itunes_image'] );
						}
					}
				}
			}
			update_post_meta( $object_id, 'jnews_podcast_option', $podcast_option );
		}
	}

	/**
	 * Import Podcast Image
	 */
	private function import_podcast_image() {
		if ( isset( $_POST['post_id'], $_POST['thumbnail_url'], $_POST['taxonomy_id'] ) ) {
			$post_id       = (int) sanitize_text_field( $_POST['post_id'] );
			$taxonomy_id   = (int) sanitize_text_field( $_POST['taxonomy_id'] );
			$thumbnail_url = esc_url( $_POST['thumbnail_url'] );
			$attachment_id = VideoAttribute::getInstance()->save_to_media_library( $post_id, $thumbnail_url );
			if ( ! empty( $attachment_id ) ) {
				// podcast_log( 'Taxonomy jnews-series and rss image available, Upload Image ' );
				$this->set_series_image( $attachment_id, $taxonomy_id, false );
			}
		}
	}

	/**
	 * Import episode default image
	 */
	private function import_episode_default_image() {
		if ( isset( $_POST['tt_ids'], $_POST['object_id'] ) ) {
			$tt_ids           = (int) sanitize_text_field( $_POST['tt_ids'] );
			$object_id        = (int) sanitize_text_field( $_POST['object_id'] );
			$podcast_image_id = $this->get_series_default_image_id( $tt_ids );
			if ( $podcast_image_id ) {
				VideoAttribute::getInstance()->set_featured_image( $object_id, $podcast_image_id, '' );
			}
		}
	}

	/**
	 * Import Episode Image
	 */
	private function import_episode_image() {
		if ( isset( $_POST['object_id'], $_POST['itunes_image'] ) ) {
			$object_id    = (int) sanitize_text_field( $_POST['object_id'] );
			$itunes_image = esc_url( $_POST['itunes_image'] );
			// podcast_log( 'Itunes image detected, save image' );
			$attachment_id = VideoAttribute::getInstance()->save_to_media_library( $object_id, $itunes_image );
			// podcast_log( 'Image ID : ' . $attachment_id );
			if ( ! empty( $attachment_id ) ) {
				// podcast_log( 'Image Saved, set as featured Image' );
				VideoAttribute::getInstance()->set_featured_image( $object_id, $attachment_id, $itunes_image );
			}
		}
	}

	/**
	 * Register new category option
	 */
	public function option_load() {
		self::$object[] = new Option_Series( self::$slug );
	}

	/**
	 * Premium content
	 */
	public function remove_player() {
		$disable_other_player = false;
		$lock_player          = false;
		if ( jnews_podcast_option( 'podcast_enable_player', false ) ) {
			$disable_other_player = true;
		}
		if ( function_exists( 'jpw_pages_list' ) ) {
			$paywall_truncater = \JNews\Paywall\Truncater\Truncater::instance();
			if ( $paywall_truncater->check_status() ) {
				$paywall_truncater->show_button( true );
				$lock_player = true;
			}
		}
		if ( $disable_other_player || $lock_player ) {
			add_filter(
				'option_powerpress_general',
				function ( $value ) {
					$value['display_player']            = 0;
					$value['disable_player']['podcast'] = true;

					return $value;
				},
				99
			);
		}
	}

	/**
	 * Regsiter jnews-series
	 */
	public function register_taxonomy() {
		jnews_register_taxonomy(
			self::$slug,
			array( 'post' ),
			array(
				'hierarchical'          => false,
				'labels'                => array(
					'name'                       => esc_html__( 'Podcast Series', 'jnews-podcast' ),
					'singular_name'              => esc_html__( 'Podcast Series', 'jnews-podcast' ),
					'search_items'               => esc_html__( 'Search Podcast Series', 'jnews-podcast' ),
					'popular_items'              => esc_html__( 'Popular Podcast Series', 'jnews-podcast' ),
					'all_items'                  => esc_html__( 'All Podcast Series', 'jnews-podcast' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => esc_html__( 'Edit Podcast Series', 'jnews-podcast' ),
					'update_item'                => esc_html__( 'Update Podcast Series', 'jnews-podcast' ),
					'add_new_item'               => esc_html__( 'Add Podcast Series', 'jnews-podcast' ),
					'new_item_name'              => esc_html__( 'New Podcast Series', 'jnews-podcast' ),
					'separate_items_with_commas' => esc_html__( 'Separate podcast series  with commas', 'jnews-podcast' ),
					'add_or_remove_items'        => esc_html__( 'Add or remove podcast series', 'jnews-podcast' ),
					'choose_from_most_used'      => esc_html__( 'Choose from the most used podcast series', 'jnews-podcast' ),
					'menu_name'                  => esc_html__( 'Podcast Series', 'jnews-podcast' ),
				),
				'show_ui'               => true,
				'show_admin_column'     => true,
				'show_in_quick_edit'    => false,
				'show_in_rest'          => false,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'series' ),
			)
		);
		self::flush_rewrite_rules();
	}

	/**
	 * Flush rewrite rules
	 */
	private static function flush_rewrite_rules() {
		self::add_rewrite_rule();

		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	/**
	 * Create new rewrite rule
	 */
	private static function add_rewrite_rule() {
		foreach ( self::$endpoint as $endpoint ) {
			add_rewrite_endpoint( $endpoint['slug'], EP_ROOT | EP_PAGES );
			/**
			 * series-category\/?([\-_0-9a-zA-Z]{1,})\/page\/?([0-9]{1,})\/?$ normal regex
			 * ^series-category/?([\-_0-9a-zA-Z]{1,})/page/?([0-9]{1,})/?$ htaccess regex
			 */
			add_rewrite_rule( '' . $endpoint['slug'] . '/(.+?)/page/?([0-9]{1,})/?$', 'index.php?&' . $endpoint['slug'] . '=$matches[1]&paged=$matches[2]', 'top' );
			add_rewrite_rule( '' . $endpoint['slug'] . '/(.+?)/?$', 'index.php?&' . $endpoint['slug'] . '=$matches[1]', 'top' );
		}
	}

}
