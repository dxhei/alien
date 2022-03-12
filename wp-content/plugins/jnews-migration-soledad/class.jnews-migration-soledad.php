<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Soledad' ) ) {
	class JNews_Migration_Soledad {

		/**
		 * @var JNews_Migration_Soledad
		 */
		private static $instance;

		/**
		 * @return JNews_Migration_Soledad
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Soledad constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_jnews_content_migration_soledad', array( $this, 'content_migration' ) );
			add_action( 'wp_ajax_nopriv_jnews_content_migration_soledad', array( $this, 'content_migration' ) );
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

			if ( ! empty( $posts ) && is_array( $posts ) && check_admin_referer( 'jnews_migration_soledad', 'nonce' ) ) {
				foreach ( $posts as $post ) {
					$post_review = get_post_meta( $post->ID, 'penci_review_title', true );
					if ( ! empty( $post_review ) ) {
						$this->review_migration_handler( $post->ID );
					}

					$post_recipe = get_post_meta( $post->ID, 'penci_recipe_title', true );
					if ( ! empty( $post_recipe ) ) {
						$this->recipe_migration_handler( $post->ID );
					}

					$post_layout = get_post_meta( $post->ID, 'penci_post_sidebar_display', true );
					if ( ! empty( $post_layout ) ) {
						$this->layout_migration_handler( $post->ID, $post_layout );
					}

					$this->after_migration( $post );
				}
			}
		}

		/**
		 * Handler function for post layout migration
		 *
		 * @param int $post_id
		 */
		public function layout_migration_handler( $post_id, $post_layout ) {
			switch ( $post_layout ) {

				case 'left':
					$post_layout = 'left-sidebar';
					break;

				case 'right':
					$post_layout = 'right-sidebar';
					break;

				case 'no':
					$post_layout = 'no-sidebar';
					break;
			}

			$post_setting = array(
				'override_template' => true,
				'override'          => array(
					array(
						'layout'                 => $post_layout,
						'show_share_counter'     => true,
						'show_view_counter'      => true,
						'show_post_meta'         => true,
						'show_post_author'       => true,
						'show_post_author_image' => true,
						'show_post_date'         => true,
						'show_post_category'     => true,
						'show_post_tag'          => true,
						'show_prev_next_post'    => true,
						'show_popup_post'        => true,
					),
				),
			);

			update_post_meta( $post_id, 'jnews_single_post', $post_setting );
		}

		/**
		 * Handler function for food recipe post migration
		 *
		 * @param int $post_id
		 */
		public function recipe_migration_handler( $post_id ) {
			update_post_meta( $post_id, 'enable_food_recipe', true );
			update_post_meta( $post_id, 'enable_print_recipe', true );
			update_post_meta( $post_id, 'food_recipe_title', get_post_meta( $post_id, 'penci_recipe_title', true ) );
			update_post_meta( $post_id, 'food_recipe_prep', get_post_meta( $post_id, 'penci_recipe_preptime', true ) );
			update_post_meta( $post_id, 'food_recipe_time', get_post_meta( $post_id, 'penci_recipe_cooktime', true ) );
			update_post_meta( $post_id, 'food_recipe_serve', get_post_meta( $post_id, 'penci_recipe_servings', true ) );
			update_post_meta( $post_id, 'instruction', get_post_meta( $post_id, 'penci_recipe_instructions', true ) );

			$ingredients = array();
			$ingredient  = explode( "\n", get_post_meta( $post_id, 'penci_recipe_ingredients', true ) );
			foreach ( $ingredient as $item ) {
				if ( ! empty( $item ) ) {
					$ingredients[] = array(
						'item' => trim( $item ),
					);
				}
			}
			update_post_meta( $post_id, 'ingredient', $ingredients );

			update_post_meta( $post_id, 'jnews_food_recipe_fields', array( 'enable_food_recipe', 'food_recipe_title', 'food_recipe_prep', 'food_recipe_time', 'food_recipe_serve', 'ingredient', 'instruction' ) );
		}

		/**
		 * Handler function for review post migration
		 *
		 * @param int $post_id
		 */
		public function review_migration_handler( $post_id ) {
			update_post_meta( $post_id, 'enable_review', true );
			update_post_meta( $post_id, 'type', 'point' );
			update_post_meta( $post_id, 'name', get_post_meta( $post_id, 'penci_review_title', true ) );
			update_post_meta( $post_id, 'summary', get_post_meta( $post_id, 'penci_review_des', true ) );

			$review_goods = array();
			$review_good  = explode( "\n", get_post_meta( $post_id, 'penci_review_good', true ) );
			foreach ( $review_good as $item ) {
				$review_goods[] = array(
					'good_text' => $item,
				);
			}
			update_post_meta( $post_id, 'good', $review_goods );

			$review_bads = array();
			$review_bad  = explode( "\n", get_post_meta( $post_id, 'penci_review_bad', true ) );
			foreach ( $review_bad as $item ) {
				$review_bads[] = array(
					'bad_text' => $item,
				);
			}
			update_post_meta( $post_id, 'bad', $review_bads );

			$ratings      = array();
			$rating_total = 0;
			$rating_mean  = 0;
			for ( $i = 1; $i <= 6; $i++ ) {
				$rating_text  = get_post_meta( $post_id, 'penci_review_' . $i, true );
				$rating_value = (int) get_post_meta( $post_id, 'penci_review_' . $i . '_num', true );

				if ( ! empty( $rating_text ) && ! empty( $rating_value ) ) {
					$ratings[]     = array(
						'rating_text'   => $rating_text,
						'rating_number' => $rating_value,
					);
					$rating_total += $rating_value;
					$rating_mean   = round( $rating_total / $i, 1 );
				}
			}
			update_post_meta( $post_id, 'rating', $ratings );
			update_post_meta( $post_id, 'jnew_rating_mean', $rating_mean );
			update_post_meta( $post_id, 'jnews_review_fields', array( 'enable_review', 'type', 'name', 'good', 'bad', 'summary', 'rating' ) );
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
						'key'     => 'jnews_migration_soledad_status',
						'compare' => 'NOT EXISTS',
					),
					array(
						'relation' => 'OR',
						array(
							'key' => 'penci_custom_sidebar_page_display',
						),
						array(
							'key' => 'penci_post_sidebar_display',
						),
						array(
							'key' => 'penci_review_title',
						),
						array(
							'key' => 'penci_recipe_title',
						),
						array(
							'key' => 'penci_post_views_count',
						),
						array(
							'key' => 'penci_post_week_views_count',
						),
						array(
							'key' => 'penci_post_month_views_count',
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
			update_post_meta( $post->ID, 'jnews_migration_soledad_status', true );

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
					'message' => sprintf( __( 'Migration successful <strong>%1$s</strong> <a href="%2$s" target="_blank">view post</a>.', 'jnews-migration-soledad' ), $post->post_title, esc_url( get_permalink( $post->ID ) ) ),
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
						'key'     => 'penci_review_title',
						'compare' => 'EXISTS',
					),
				),
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

		/**
		 * Check JNews Food Recipe Plugin status
		 *
		 * @return false | string
		 */
		public function recipe_plugin_check() {
			if ( empty( $this->content_has_recipe() ) ) {
				 return false;
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			if ( get_plugins( '/jnews-food-recipe' ) ) {
				if ( ! is_plugin_active( 'jnews-food-recipe/jnews-food-recipe.php' ) ) {
					return 'activate';
				}
			} else {
				return 'install';
			}

			return false;
		}


		/**
		 * Check if content has recipe on post
		 *
		 * @return array
		 */
		public function content_has_recipe() {
			$args = array(
				'post_type'      => 'post',
				'meta_query'     => array(
					array(
						'key'     => 'penci_recipe_title',
						'compare' => 'EXISTS',
					),
				),
				'posts_per_page' => -1,
			);

			$posts = get_posts( $args );

			return $posts;
		}

	}
}

