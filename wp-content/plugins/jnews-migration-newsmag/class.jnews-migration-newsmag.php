<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Newsmag' ) ) {
	class JNews_Migration_Newsmag {

		/**
		 * @var JNews_Migration_Newsmag
		 */
		private static $instance;

		/**
		 * @return JNews_Migration_Newsmag
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Newsmag constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_jnews_content_migration_newsmag', array( $this, 'content_migration' ) );
			add_action( 'wp_ajax_nopriv_jnews_content_migration_newsmag', array( $this, 'content_migration' ) );
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

			if ( ! empty( $posts ) && is_array( $posts ) && check_admin_referer( 'jnews_migration_newsmag', 'nonce' ) ) {
				foreach ( $posts as $post ) {
					$post_setting = get_post_meta( $post->ID, 'td_post_theme_settings', true );
					$post_format  = get_post_meta( $post->ID, 'td_post_video', true );

					if ( ! empty( $post_setting['has_review'] ) ) {
						$this->review_migration_handler( $post->ID, $post_setting );
					}

					if ( ! empty( $post_setting['smart_list_template'] ) ) {
						$this->split_migration_handler( $post->ID, $post_setting );
					}

					if ( ! empty( $post_format['td_video'] ) ) {
						update_post_meta( $post->ID, '_format_video_embed', $post_format['td_video'] );
						set_post_format( $post->ID, 'video' );
					}

					$this->after_migration( $post );
				}
			}
		}

		/**
		 * Handler function for split post migration
		 *
		 * @param  int   $post_id
		 * @param  array $post_setting
		 */
		public function split_migration_handler( $post_id, $post_setting ) {
			$split = array(
				'enable_post_split' => true,
				'post_split'        => array(
					array(
						'template'  => 1,
						'tag'       => $post_setting['td_smart_list_h'] ? $post_setting['td_smart_list_h'] : 'h3',
						'numbering' => $post_setting['td_smart_list_order'] ? 'asc' : 'desc',
						'mode'      => 'normal',
					),
				),
			);

			update_post_meta( $post_id, 'jnews_post_split', $split );
		}

		/**
		 * Handler function for review post migration
		 *
		 * @param  int   $post_id
		 * @param  array $post_setting
		 */
		public function review_migration_handler( $post_id, $post_setting ) {
			update_post_meta( $post_id, 'enable_review', true );

			update_post_meta( $post_id, 'type', $this->get_type( $post_setting ) );

			update_post_meta( $post_id, 'rating', $this->get_rating( $post_setting ) );

			update_post_meta( $post_id, 'jnew_rating_mean', $this->get_mean( $post_setting ) );

			update_post_meta( $post_id, 'summary', $post_setting['review'] );

			update_post_meta( $post_id, 'jnews_review_fields', array( 'enable_review', 'type', 'summary', 'rating' ) );
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

			switch ( $data['has_review'] ) {
				case 'rate_point':
					$type = 'point';
					break;

				case 'rate_stars':
					$type = 'star';
					break;

				case 'rate_percent':
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
		public function get_rating( $data ) {
			$rating = array();

			switch ( $data['has_review'] ) {
				case 'rate_point':
					if ( is_array( $data['p_review_points'] ) ) {
						foreach ( $data['p_review_points'] as $point ) {
							$rating[] = array(
								'rating_text'   => $point['desc'],
								'rating_number' => $point['rate'],
							);
						}
					}
					break;

				case 'rate_stars':
					if ( is_array( $data['p_review_stars'] ) ) {
						foreach ( $data['p_review_stars'] as $percent ) {
							$rating[] = array(
								'rating_text'   => $percent['desc'],
								'rating_number' => (int) round( $percent['rate'] * 2 ),
							);
						}
					}
					break;

				case 'rate_percent':
					if ( is_array( $data['p_review_percents'] ) ) {
						foreach ( $data['p_review_percents'] as $percent ) {
							$rating[] = array(
								'rating_text'   => $percent['desc'],
								'rating_number' => (int) round( $percent['rate'] / 10 ),
							);
						}
					}
					break;
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
		public function get_mean( $data ) {
			$ratings = $this->get_rating( $data );

			$total = $numberofrating = 0;

			if ( is_array( $ratings ) ) {
				foreach ( $ratings as $rating ) {
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
						'key'     => 'jnews_migration_newsmag_status',
						'compare' => 'NOT EXISTS',
					),
					array(
						'relation' => 'OR',
						array(
							'key' => 'td_post_theme_settings',
						),
						array(
							'key' => 'td_post_video',
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
			update_post_meta( $post->ID, 'jnews_migration_newsmag_status', true );

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
					'message' => sprintf( __( 'Migration successful <strong>%1$s</strong> <a href="%2$s" target="_blank">view post</a>.', 'jnews-migration-newsmag' ), $post->post_title, esc_url( get_permalink( $post->ID ) ) ),
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
		 * Check JNews Split Post Plugin status
		 *
		 * @return false | string
		 */
		public function split_plugin_check() {
			if ( empty( $this->content_has_split() ) ) {
				 return false;
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			if ( get_plugins( '/jnews-split' ) ) {
				if ( ! is_plugin_active( 'jnews-split/jnews-split.php' ) ) {
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
						'key'     => 'td_post_theme_settings',
						'value'   => 'has_review',
						'compare' => 'LIKE',
					),
				),
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

		/**
		 * Check if content has split post
		 *
		 * @return array
		 */
		public function content_has_split() {
			$args = array(
				'post_type'      => 'post',
				'meta_query'     => array(
					array(
						'key'     => 'td_post_theme_settings',
						'value'   => 'smart_list_template',
						'compare' => 'LIKE',
					),
				),
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

	}
}

