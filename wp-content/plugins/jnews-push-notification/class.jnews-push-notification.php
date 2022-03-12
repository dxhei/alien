<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Push_Notification' ) ) {
	class JNews_Push_Notification {

		/**
		 * @var JNews_Push_Notification
		 */
		private static $instance;

		/**
		 * @return JNews_Push_Notification
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Push_Notification constructor
		 */
		private function __construct() {
			add_action( 'wp_print_styles', array( $this, 'load_assets' ) );
			add_action( 'jnews_push_notification_single_post', array( $this, 'single_post_form' ) );
			add_action( 'admin_init', array( $this, 'post_category_filter' ) );

			add_filter( 'jnews_push_notification_single_category', array( $this, 'single_category_form' ), null, 2 );
		}

		/**
		 * Load plugin assest
		 */
		public function load_assets() {
			wp_enqueue_style( 'jnews-push-notification', JNEWS_PUSH_NOTIFICATION_URL . '/assets/css/plugin.css', null, JNEWS_PUSH_NOTIFICATION_VERSION );

			wp_enqueue_script( 'jnews-push-notification', JNEWS_PUSH_NOTIFICATION_URL . '/assets/js/plugin.js', null, JNEWS_PUSH_NOTIFICATION_VERSION, true );
		}

		/**
		 * Single category subscribe form
		 *
		 * @param  object $term
		 *
		 * @return string
		 */
		public function single_category_form( $output, $term ) {
			$output = "<div class='jeg_push_notification single_category'>
                        " . $this->render_single_category_form( $term ) . '
                    </div>';

			return $output;
		}

		/**
		 * Render single category subscribe form
		 *
		 * @param  object $term
		 *
		 * @return string
		 */
		public function render_single_category_form( $term ) {
			$enable = jnews_get_option( 'push_notification_category_enable', false );

			if ( ! $enable ) {
				return null;
			}

			$description     = jnews_get_option( 'push_notification_category_description', 'Get real time update about this post category directly on your device, subscribe now.' );
			$btn_subscribe   = jnews_get_option( 'push_notification_category_btn_subscribe', 'Subscribe' );
			$btn_unsubscribe = jnews_get_option( 'push_notification_category_btn_unsubscribe', 'Unsubscribe' );
			$btn_processing  = jnews_get_option( 'push_notification_category_btn_processing', 'Processing . . .' );

			$output = '<div class="jeg_push_notification_content">
                            <p>' . str_replace( PHP_EOL, '<br>', $description ) . "</p>
                            <div class=\"jeg_push_notification_button\">
                                <input type=\"hidden\" name=\"post-category\" value=\"{$term->slug}\">
                                <input type=\"hidden\" name=\"button-subscribe\" value=\"" . esc_html( $btn_subscribe ) . '">
                                <input type="hidden" name="button-unsubscribe" value="' . esc_html( $btn_unsubscribe ) . '">
                                <input type="hidden" name="button-processing" value="' . esc_html( $btn_processing ) . '">
                                <a data-action="unsubscribe" data-type="category" class="button" href="#">
                                    <i class="fa fa-bell-slash-o"></i>
                                    ' . esc_html( $btn_unsubscribe ) . '
                                </a>
                            </div>
                        </div>';

			if ( ! class_exists( 'OneSignal_Admin' ) ) {
				$output =
					'<div class="alert alert-error">
                        <strong>' . esc_html__( 'Plugin Install', 'jnews' ) . '</strong>' . ' : ' . esc_html__( 'Subscribe Push Notification need OneSignal plugin to be installed.', 'jnews-push-notification' ) .
					'</div>';
			}

			return $output;
		}

		/**
		 * Single post subscribe form
		 */
		public function single_post_form() {
			$output = "<div class='jeg_push_notification single_post'>
                        " . $this->render_single_post_form() . '
                    </div>';

			echo jnews_sanitize_output( $output );
		}

		/**
		 * Render single post subscribe form
		 */
		public function render_single_post_form() {
			$enable = jnews_get_option( 'push_notification_post_enable', false );

			if ( ! $enable ) {
				return null;
			}

			$description     = jnews_get_option( 'push_notification_post_description', 'Get real time update about this post categories directly on your device, subscribe now.' );
			$btn_subscribe   = jnews_get_option( 'push_notification_post_btn_subscribe', 'Subscribe' );
			$btn_unsubscribe = jnews_get_option( 'push_notification_post_btn_unsubscribe', 'Unsubscribe' );
			$btn_processing  = jnews_get_option( 'push_notification_post_btn_processing', 'Processing . . .' );
			$post_category   = $this->get_post_category( get_the_ID() );

			$output = '<div class="jeg_push_notification_content">
                             <p>' . str_replace( PHP_EOL, '<br>', $description ) . '</p>
                            <div class="jeg_push_notification_button">
                                <input type="hidden" name="post-category" value="' . implode( ',', $post_category ) . '">
                                <input type="hidden" name="button-subscribe" value="' . esc_html( $btn_subscribe ) . '">
                                <input type="hidden" name="button-unsubscribe" value="' . esc_html( $btn_unsubscribe ) . '">
                                <input type="hidden" name="button-processing" value="' . esc_html( $btn_processing ) . '">
                                <a data-action="unsubscribe" data-type="category" class="button" href="#">
                                    <i class="fa fa-bell-slash-o"></i>
                                    ' . esc_html( $btn_unsubscribe ) . '
                                </a>
                            </div>
                        </div>';

			if ( ! class_exists( 'OneSignal_Admin' ) ) {
				$output =
					'<div class="alert alert-error">
                        <strong>' . esc_html__( 'Plugin Install', 'jnews' ) . '</strong>' . ' : ' . esc_html__( 'Subscribe Push Notification need OneSignal plugin to be installed.', 'jnews-push-notification' ) .
					'</div>';
			}

			return $output;
		}

		/**
		 * Get post categories
		 *
		 * @param  int $post_id
		 *
		 * @return array
		 */
		public function get_post_category( $post_id ) {
			$result = array();

			$categories = wp_get_post_categories( $post_id );

			foreach ( $categories as $category ) {
				$category = get_term_by( 'id', $category, 'category' );
				$result[] = $category->slug;
			}

			return $result;
		}

		/**
		 * Add category post filter
		 */
		public function post_category_filter() {
			if ( current_user_can( 'edit_posts' ) ) {
				add_filter( 'onesignal_send_notification', array( $this, 'send_notification_filter' ), 10, 4 );
			}
		}

		/**
		 * Build category post filter
		 *
		 * @param  array  $fields
		 * @param  string $new_status
		 * @param  string $old_status
		 * @param  obj    $post
		 *
		 * @return array
		 */
		function send_notification_filter( $fields, $new_status, $old_status, $post ) {
			if ( $post->post_type != 'post' ) {
				return false;
			}

			$filters    = array();
			$categories = $this->get_post_category( $post->ID );

			for ( $i = 0; $i < sizeof( $categories ); $i++ ) {
				$filters[] = array(
					'field'    => 'tag',
					'key'      => $categories[ $i ],
					'relation' => '=',
					'value'    => $categories[ $i ],
				);

				if ( $i < ( sizeof( $categories ) - 1 ) ) {
					$filters[] = array(
						'operator' => 'OR',
					);
				}
			}

			if ( sizeof( $filters ) > 0 ) {
				$filters[] = array(
					'operator' => 'OR',
				);
			}

			$filters[] = array(
				'field'    => 'tag',
				'key'      => 'all',
				'relation' => '=',
				'value'    => 'all',
			);

			$fields['included_segments'] = array();
			$fields['filters']           = $filters;

			return $fields;
		}

	}
}
