<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Sahifa' ) ) {
	class JNews_Migration_Sahifa {

		/**
		 * @var JNews_Migration_Sahifa
		 */
		private static $instance;

		/**
		 * @var array
		 */
		private $rating;

		/**
		 * @return JNews_Migration_Sahifa
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Sahifa constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_jnews_content_migration_sahifa', array( $this, 'content_migration' ) );
			add_action( 'wp_ajax_nopriv_jnews_content_migration_sahifa', array( $this, 'content_migration' ) );
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

			if ( ! empty( $posts ) && is_array( $posts ) && check_admin_referer( 'jnews_migration_sahifa', 'nonce' ) ) {
				foreach ( $posts as $post ) {
					$review   = get_post_meta( $post->ID, 'taq_review_position', true );
					$featured = get_post_meta( $post->ID, 'tie_post_head', true );

					if ( ! empty( $review ) ) {
						$this->review_migration_handler( $post->ID );
					}

					if ( $featured == 'video' ) {
						$this->video_migration_handler( $post->ID );
					}

					$this->after_migration( $post );
				}
			}
		}

		/**
		 * Handler function for video featured post migration
		 *
		 * @param  int $post_id
		 */
		public function video_migration_handler( $post_id ) {
			$embed  = get_post_meta( $post_id, 'tie_embed_code', true );
			$url    = get_post_meta( $post_id, 'tie_video_url', true );
			$hosted = get_post_meta( $post_id, 'tie_video_self', true );

			set_post_format( $post_id, 'video' );

			if ( ! empty( $embed ) ) {
				update_post_meta( $post_id, '_format_video_embed', $embed );
			}

			if ( ! empty( $url ) ) {
				update_post_meta( $post_id, '_format_video_embed', $url );
			}

			if ( ! empty( $hosted ) ) {
				update_post_meta( $post_id, '_format_video_embed', $hosted );
			}
		}

		/**
		 * Handler function for review post migration
		 *
		 * @param  int $post_id
		 */
		public function review_migration_handler( $post_id ) {
			update_post_meta( $post_id, 'enable_review', true );

			update_post_meta( $post_id, 'name', $this->get_name( $post_id ) );

			update_post_meta( $post_id, 'type', $this->get_type( $post_id ) );

			update_post_meta( $post_id, 'rating', $this->get_rating( $post_id ) );

			update_post_meta( $post_id, 'jnew_rating_mean', $this->get_mean( $post_id ) );

			update_post_meta( $post_id, 'summary', $this->get_summary( $post_id ) );

			update_post_meta( $post_id, 'jnews_review_fields', array( 'enable_review', 'name', 'type', 'summary', 'rating' ) );
		}

		/**
		 * Get review name
		 *
		 * @param  int $post_id
		 *
		 * @return string
		 */
		public function get_name( $post_id ) {
			return get_post_meta( $post_id, 'taq_review_title', true );
		}

		/**
		 * Get review summary
		 *
		 * @param  int $post_id
		 *
		 * @return string
		 */
		public function get_summary( $post_id ) {
			return get_post_meta( $post_id, 'taq_review_summary', true );
		}

		/**
		 * Get review type
		 *
		 * @param  int $post_id
		 *
		 * @return string
		 */
		public function get_type( $post_id ) {
			$type = get_post_meta( $post_id, 'taq_review_style', true );

			if ( ! empty( $type ) ) {
				switch ( $type ) {
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
			}

			return $type;
		}

		/**
		 * Get review rating
		 *
		 * @param  int $post_id
		 *
		 * @return array
		 */
		public function get_rating( $post_id ) {
			$result  = array();
			$ratings = get_post_meta( $post_id, 'taq_review_criteria', true );

			if ( is_array( $ratings ) ) {
				foreach ( $ratings as $rating ) {
					$result[] = array(
						'rating_text'   => $rating['name'],
						'rating_number' => (int) round( $rating['score'] / 10 ),
					);
				}
			}

			$this->rating = $result;

			return $result;
		}

		/**
		 * Get review mean
		 *
		 * @param  int $post_id
		 *
		 * @return float ( default null )
		 */
		public function get_mean( $post_id ) {
			$ratings = $this->rating;

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
						'key'     => 'jnews_migration_sahifa_status',
						'compare' => 'NOT EXISTS',
					),
					array(
						'relation' => 'OR',
						array(
							'key'     => 'taq_review_position',
							'value'   => false,
							'compare' => '!=',
						),
						array(
							'key'     => 'tie_post_head',
							'value'   => 'video',
							'compare' => '=',
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
			update_post_meta( $post->ID, 'jnews_migration_sahifa_status', true );

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
					'message' => sprintf( __( 'Migration successful <strong>%1$s</strong> <a href="%2$s" target="_blank">view post</a>.', 'jnews-migration-sahifa' ), $post->post_title, esc_url( get_permalink( $post->ID ) ) ),
				)
			);
		}

		/**
		 * Check JNews Review Plugin status
		 *
		 * @return false | string
		 */
		public function review_plugin_check() {
			$content_has_review = $this->content_has_review();

			if ( empty( $content_has_review ) ) {
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
						'key'     => 'taq_review_position',
						'value'   => false,
						'compare' => '!=',
					),
				),
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

	}
}

