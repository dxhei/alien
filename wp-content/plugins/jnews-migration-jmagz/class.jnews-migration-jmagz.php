<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_JMagz' ) ) {
	class JNews_Migration_JMagz {

		/**
		 * @var JNews_Migration_JMagz
		 */
		private static $instance;

		/**
		 * @return JNews_Migration_JMagz
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_JMagz constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_jnews_content_migration_jmagz', array( $this, 'content_migration' ) );
			add_action( 'wp_ajax_nopriv_jnews_content_migration_jmagz', array( $this, 'content_migration' ) );
		}

		/**
		 * Main function for content migration
		 *
		 * @param  boolean $count ( set true if you want to return the number of post only )
		 *
		 * @return mixed value
		 */
		public function content_migration( $count = false ) {
			$posts = get_posts( $this->build_args( $count ) );

			if ( $count ) {
				return count( $posts );
			}

			if ( ! empty( $posts ) && is_array( $posts ) ) {
				foreach ( $posts as $post ) {
					$video_format   = get_post_meta( $post->ID, 'jmagz_blog_video', true );
					$gallery_format = get_post_meta( $post->ID, 'jmagz_blog_gallery', true );

					if ( ! empty( $video_format['video_url'] ) ) {
						update_post_meta( $post->ID, '_format_video_embed', $video_format['video_url'] );
					}

					if ( ! empty( $gallery_format['binding_group'] ) ) {
						update_post_meta( $post->ID, '_format_gallery_images', $this->get_gallery_images( $gallery_format ) );
					}

					if ( $post->post_type == 'review' ) {
						$this->review_migration_handler( $post );

						$this->import_review_taxonomy( $post->ID );
					}

					$this->after_migration( $post );
				}

				flush_rewrite_rules();
			}
		}

		/**
		 * Get gallery image ids
		 *
		 * @param  array $images
		 *
		 * @return array
		 */
		public function get_gallery_images( $images ) {
			$items = array();

			if ( is_array( $images['binding_group'] ) ) {
				foreach ( $images['binding_group'] as $image ) {
					$items[] = $image['image'];
				}
			}

			return $items;
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
				'post_type'      => array( 'post', 'review' ),
				'meta_query'     => array(
					array(
						'key'     => 'jnews_migration_jmagz_status',
						'compare' => 'NOT EXISTS',
					),
					array(
						'relation' => 'OR',
						array(
							'key' => 'jmagz_blog_gallery',
						),
						array(
							'key' => 'jmagz_blog_video',
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
			update_post_meta( $post->ID, 'jnews_migration_jmagz_status', true );

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
					'message' => sprintf( __( 'Migration successful <strong>%1$s</strong> <a href="%2$s" target="_blank">view post</a>.', 'jnews-migration-jmagz' ), $post->post_title, esc_url( get_permalink( $post->ID ) ) ),
				)
			);
		}

		/**
		 * Handler function for review post migration
		 *
		 * @param  object $post
		 */
		public function review_migration_handler( $post ) {
			$review_meta     = get_post_meta( $post->ID, 'jmagz_review_meta', true );
			$review_good_bad = get_post_meta( $post->ID, 'jmagz_review_good_bad', true );
			$review_rating   = get_post_meta( $post->ID, 'jmagz_review_rating', true );
			$review_price    = get_post_meta( $post->ID, 'jmagz_review_price', true );

			set_post_type( $post->ID, 'post' );

			update_post_meta( $post->ID, 'enable_review', true );

			update_post_meta( $post->ID, 'type', 'point' );

			update_post_meta( $post->ID, 'name', $review_meta['product_name'] );

			update_post_meta( $post->ID, 'summary', $review_meta['product_summary'] );

			update_post_meta( $post->ID, 'good', $review_good_bad['good'] );

			update_post_meta( $post->ID, 'bad', $review_good_bad['bad'] );

			update_post_meta( $post->ID, 'rating', $review_rating['rating'] );

			update_post_meta( $post->ID, 'price', $review_price['price'] );

			update_post_meta( $post->ID, 'jnews_price_lowest', get_post_meta( $post->ID, 'price_lowest', true ) );

			update_post_meta( $post->ID, 'jnew_rating_mean', get_post_meta( $post->ID, 'rating_mean', true ) );

			update_post_meta( $post->ID, 'jnews_review_fields', array( 'enable_review', 'type', 'name', 'summary', 'good', 'bad', 'rating', 'price' ) );
		}

		/**
		 * Check JNews Review Plugin status
		 *
		 * @return mixed value
		 */
		public function review_plugin_check() {
			$has_review = $this->content_has_review();

			if ( empty( $has_review ) ) {
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
		 * Check JPlugin status
		 *
		 * @return boolean
		 */
		public function jplugin_check() {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			if ( get_plugins( '/jplugin' ) ) {
				if ( is_plugin_active( 'jplugin/jplugin.php' ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check JMagz Plugin status
		 *
		 * @return boolean
		 */
		public function jmagz_plugin_check() {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			if ( get_plugins( '/jmagz-plugin' ) ) {
				if ( is_plugin_active( 'jmagz-plugin/jmagz-plugin.php' ) ) {
					return true;
				}
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
				'post_type'      => 'review',
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

		/**
		 * Import review post taxonomy
		 *
		 * @param  int $post_id
		 */
		public function import_review_taxonomy( $post_id ) {
			$terms = get_the_terms( $post_id, 'review-category' );

			if ( is_array( $terms ) ) {
				$terms_slug = array();

				foreach ( $terms as $term ) {
					$terms_slug[] = $term->slug;
					$currentterm  = get_term_by( 'slug', $term->slug, 'category' );

					if ( ! $currentterm ) {
						$tax_args = array(
							'slug'        => $term->slug,
							'description' => $term->description,
						);

						if ( isset( $term->parent ) ) {
							$term_parent_slug = get_term_by( 'id', $term->parent, 'review-category' );

							if ( ! empty( $term_parent_slug ) ) {
								$term_parent_id = get_term_by( 'slug', $term_parent_slug, 'category' );

								if ( ! empty( $term_parent_id ) ) {
									$tax_args['parent'] = $term_parent_id;
								}
							}
						}

						wp_insert_term( $term->name, 'category', $tax_args );
					}
				}

				wp_set_object_terms( $post_id, $terms_slug, 'category' );
			}
		}

	}
}
