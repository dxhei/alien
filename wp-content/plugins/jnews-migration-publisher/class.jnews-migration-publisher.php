<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Publisher' ) ) {
	class JNews_Migration_Publisher {

		/**
		 * @var JNews_Migration_Publisher
		 */
		private static $instance;

		/**
		 * @return JNews_Migration_Publisher
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Publisher constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_jnews_content_migration_publisher', array( $this, 'content_migration' ) );
			add_action( 'wp_ajax_nopriv_jnews_content_migration_publisher', array( $this, 'content_migration' ) );

			add_filter( 'jnews_get_total_view', array( $this, 'get_total_view' ), 99, 2 );
		}

		/**
		 * Main function for content migration
		 *
		 * @param  boolean $count ( set true if you want to return the number of post only )
		 */
		public function content_migration( $count = false ) {
			$posts = get_posts( $this->build_args( $count ) );

			if ( $count ) {
				return count( $posts );
			}

			if ( ! empty( $posts ) && is_array( $posts ) && check_admin_referer( 'jnews_migration_publisher', 'nonce' ) ) {
				foreach ( $posts as $post ) {
					$post_review = get_post_meta( $post->ID, '_bs_review_enabled', true );
					$post_video  = get_post_meta( $post->ID, '_featured_embed_code', true );

					if ( ! empty( $post_review ) ) {
						$this->review_migration_handler( $post->ID );
					}

					if ( ! empty( $post_video ) ) {
						set_post_format( $post->ID, 'video' );
						update_post_meta( $post->ID, '_format_video_embed', $post_video );
					}

					$this->post_layout_handler( $post );
					$this->post_category_handler( $post );

					$this->after_migration( $post );
				}
			}
		}

		public function post_category_handler( $post ) {
			$primary_category = get_post_meta( $post->ID, '_bs_primary_category', true );

			update_post_meta( $post->ID, 'jnews_primary_category', array( $primary_category ) );
		}

		public function post_layout_handler( $post ) {
			$options       = get_option( 'bs_publisher_theme_options' );
			$post_layout   = get_post_meta( $post->ID, 'page_layout', true );
			$post_template = get_post_meta( $post->ID, 'post_template', true );

			$author_box = isset( $options['post_author_box'] ) ? $options['post_author_box'] : 'show';
			$prev_next  = isset( $options['post_next_prev'] ) ? $options['post_next_prev'] : 'style-1';

			$post_settings = isset( $options['post-page-settings'] ) ? $options['post-page-settings'] : '';
			$show_featured = ! empty( $post_settings ) && isset( $post_settings['featured'] ) ? $post_settings['featured'] : true;
			$show_category = ! empty( $post_settings ) && isset( $post_settings['term'] ) ? $post_settings['term'] : true;
			$show_tag      = ! empty( $post_settings ) && isset( $post_settings['term-tax'] ) ? $post_settings['term-tax'] : true;

			$post_meta        = ! empty( $post_settings ) && isset( $post_settings['meta'] ) ? $post_settings['meta'] : '';
			$show_post_meta   = ! empty( $post_meta ) && isset( $post_meta['show'] ) ? $post_meta['show'] : true;
			$show_meta_author = ! empty( $post_meta ) && isset( $post_meta['author'] ) ? $post_meta['author'] : true;
			$show_post_avatar = ! empty( $post_meta ) && isset( $post_meta['author_avatar'] ) ? $post_meta['author_avatar'] : true;
			$show_meta_date   = ! empty( $post_meta ) && isset( $post_meta['date'] ) ? $post_meta['date'] : true;

			switch ( $post_layout ) {
				case '1-col':
					$post_layout = 'no-sidebar-narrow';
					break;

				case 'default':
				case '2-col-right':
					$post_layout = 'right-sidebar';
					break;

				case '2-col-left':
					$post_layout = 'left-sidebar';
					break;
			}

			switch ( $post_template ) {
				case 'style-9':
					$show_featured = false;
					break;
			}

			switch ( $author_box ) {
				case 'hide':
					$author_box = false;
					break;
				default:
					$author_box = true;
					break;
			}

			switch ( $prev_next ) {
				case 'hide':
					$prev_next = false;
					break;
				default:
					$prev_next = true;
					break;
			}

			$post_related = get_post_meta( $post->ID, 'post_related', true );

			$post_setting = array(
				'override_template' => true,
				'override'          => array(
					array(
						'layout'                 => $post_layout,
						'paralax'                => true,
						'sidebar'                => 'default-sidebar',
						'show_featured'          => $show_featured,
						'show_share_counter'     => true,
						'show_view_counter'      => true,
						'show_post_meta'         => $show_post_meta,
						'show_post_author'       => $show_meta_author,
						'show_post_author_image' => $show_post_avatar,
						'show_post_date'         => $show_meta_date,
						'show_post_category'     => $show_category,
						'show_post_tag'          => $show_tag,
						'show_prev_next_post'    => $prev_next,
						'show_popup_post'        => true,
						'show_author_box'        => $author_box,
						'show_post_related'      => ( $post_related == 'show' ) ? true : false,
					),
				),
			);

			update_post_meta( $post->ID, 'jnews_single_post', $post_setting );
		}

		/**
		 * Handler function for review post migration
		 *
		 * @param  int   $post_id
		 * @param  array $post_setting
		 */
		public function review_migration_handler( $post_id ) {
			update_post_meta( $post_id, 'enable_review', true );

			$review_type = get_post_meta( $post_id, '_bs_review_rating_type', true );
			$review_type = $this->get_type( $review_type );
			update_post_meta( $post_id, 'type', $review_type );

			update_post_meta( $post_id, 'name', get_post_meta( $post_id, '_bs_review_heading', true ) );

			$review_value = get_post_meta( $post_id, '_bs_review_criteria', true );
			$review_value = $this->get_rating( $review_value );
			update_post_meta( $post_id, 'rating', $review_value );

			$review_mean = $this->get_mean( $review_value );
			update_post_meta( $post_id, 'jnew_rating_mean', $review_mean );

			$review_summary = get_post_meta( $post_id, '_bs_review_verdict_summary', true );
			update_post_meta( $post_id, 'summary', $review_summary );

			update_post_meta( $post_id, 'jnews_review_fields', array( 'enable_review', 'type', 'name', 'summary', 'rating' ) );
		}

		/**
		 * Get review type
		 *
		 * @param  array $data
		 *
		 * @return string
		 */
		public function get_type( $data ) {
			$type = '';

			switch ( $data ) {
				case 'points':
					$type = 'point';
					break;

				case 'stars':
					$type = 'star';
					break;

				case 'percentage':
					$type = 'percentage';
					break;
			}

			return $type;
		}

		/**
		 * Get review rating
		 *
		 * @param  array $data
		 *
		 * @return array
		 */
		public function get_rating( $review_value ) {
			$rating = array();

			if ( is_array( $review_value ) ) {
				foreach ( $review_value as $point ) {
					$rating[] = array(
						'rating_text'   => $point['label'],
						'rating_number' => $point['rate'],
					);
				}
			}

			return $rating;
		}

		/**
		 * Get review mean
		 *
		 * @param  array $data
		 *
		 * @return float ( default null )
		 */
		public function get_mean( $review_value ) {
			$total = $numberofrating = 0;

			if ( is_array( $review_value ) ) {
				foreach ( $review_value as $rating ) {
					if ( $rating['rating_number'] != 0 && ! empty( $rating['rating_text'] ) ) {
						$total += $rating['rating_number'];
						$numberofrating++;
					}
				}

				if ( $numberofrating > 0 ) {
					$mean = $total / $numberofrating;
					$mean = round( $mean, 1 );
					return $mean;
				}
			}

			return null;
		}

		/**
		 * Build query argument
		 *
		 * @param  boolean $count
		 *
		 * @return array
		 */
		public function build_args( $count ) {
			$args = array(
				'post_type'      => 'post',
				'meta_query'     => array(
					array(
						'key'     => 'jnews_migration_publisher_status',
						'compare' => 'NOT EXISTS',
					),
					array(
						'relation' => 'OR',
						array(
							'key' => '_bs_primary_category',
						),
					),
				),
				'posts_per_page' => $count ? -1 : 1,
			);

			return $args;
		}

		/**
		 * End migration action
		 *
		 * @param  object $post
		 */
		public function after_migration( $post ) {
			update_post_meta( $post->ID, 'jnews_migration_publisher_status', true );

			$this->get_migration_response( $post );
		}

		/**
		 * Get migration response message
		 *
		 * @param  object $post
		 */
		public function get_migration_response( $post ) {
			wp_send_json(
				array(
					'status'  => 'success',
					'message' => sprintf( __( 'Migration successful <strong>%1$s</strong> <a href="%2$s" target="_blank">view post</a>.', 'jnews-migration-publisher' ), $post->post_title, esc_url( get_permalink( $post->ID ) ) ),
				)
			);
		}

		/**
		 * Check JNews Review Plugin status
		 *
		 * @return false | string
		 */
		public function review_plugin_check() {
			if ( empty( $this->content_has_review() ) ) {
				 return false;
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			if ( get_plugins( '/jnews-review' ) ) {
				if ( ! is_plugin_active( 'jnews-review/jnews-review.php' ) ) {
					return 'activate';
				}
			} else {
				return 'install';
			}

			return false;
		}

		/**
		 * Check if content has review post
		 *
		 * @return array
		 */
		public function content_has_review() {
			$args = array(
				'post_type'      => 'post',
				'meta_query'     => array(
					array(
						'key'     => '_bs_review_enabled',
						'value'   => true,
						'compare' => '==',
					),
				),
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

		public function get_total_view( $total, $post_id ) {
			$data = get_post_meta( $post_id, 'better-views-count', true );

			if ( ! empty( $data ) ) {
				$total = $total + (int) $data;
			}

			return $total;
		}
	}
}

