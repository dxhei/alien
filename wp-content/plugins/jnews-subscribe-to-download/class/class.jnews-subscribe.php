<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class JNews_Subscribe
 *
 * @package JNEWS_SUBSCRIBE
 */
class JNews_Subscribe {
	/**
	 * @var JNews_Subscribe
	 */
	private static $instance;

	/**
	 * @var string
	 */
	private $endpoint = 'ajax-request';

	/**
	 * JNews_Subscribe constructor.
	 */
	private function __construct() {
		$themes = wp_get_theme();
		if ( ( $themes->parent() && $themes->parent()->get( 'TextDomain' ) === 'jnews' ) || $themes->get( 'TextDomain' ) === 'jnews' ) {
			$this->subscribe_includes();
			$this->setup_init();
			$this->setup_hook();
		}
	}

	/**
	 * Include file
	 */
	public function subscribe_includes() {
		include_once JNEWS_SUBSCRIBE_DIR . '/class/class.jnews-subscribe-module.php';
		include_once JNEWS_SUBSCRIBE_DIR . '/class/class.jnews-subscribe-post-type.php';
		include_once JNEWS_SUBSCRIBE_DIR . '/class/class.jnews-subscribe-meta-box.php';
		include_once JNEWS_SUBSCRIBE_DIR . '/class/Action/email.php';
		include_once JNEWS_SUBSCRIBE_DIR . '/class/Action/mailchimp.php';
	}

	/**
	 * Setup JNews_Subscribe init
	 */
	public function setup_init() {
		JNews_Subscribe_Post_Type::get_instance();
		JNews_Subscribe_Module::get_instance();
		if ( is_admin() ) {
			JNews_Subscribe_Meta_Box::get_instance();
		}
	}

	/**
	 * Setup JNews_Subscribe hook
	 */
	public function setup_hook() {

		add_action( 'wp_enqueue_scripts', array( $this, 'load_asset' ) );
		add_action( 'parse_request', array( $this, 'ajax_parse_request' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_script' ) );

	}

	/**
	 * Singleton page of JNews_Subscribe class
	 *
	 * @return JNews_Subscribe
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Load JNews_Subscribe asset
	 */
	public function load_asset() {
		wp_enqueue_style( 'jnews-subscribe', JNEWS_SUBSCRIBE_URL . '/assets/css/plugin.css', null, JNEWS_SUBSCRIBE_VERSION );
		wp_enqueue_script( 'jnews-subscribe', JNEWS_SUBSCRIBE_URL . '/assets/js/jquery.jnews-subscribe.js', array( 'jquery' ), JNEWS_SUBSCRIBE_VERSION, true );
		wp_localize_script( 'jnews-subscribe', 'jnews_subscribe', $this->localize_script() );
	}

	/**
	 * Localize JNews_Subscribe script
	 *
	 * @return mixed|void
	 */
	public function localize_script() {
		$option                  = array();
		$option['action_failed'] = __( 'There was an error. Please try again later', 'jnews-subscribe' );

		return apply_filters( 'jnews_subscribe_localize_script', $option );
	}

	/**
	 * Load JNews_Subscribe admin asset
	 */
	public function admin_script() {
		$screen = get_current_screen();
		if ( isset( $screen->post_type ) && 'jnews-download' === $screen->post_type ) {
			wp_enqueue_style( 'jnews-subscribe', JNEWS_SUBSCRIBE_URL . '/assets/css/admin/plugin.css', null, JNEWS_SUBSCRIBE_VERSION );
		}
	}

	/**
	 * Ajax request parse
	 *
	 * @param $wp
	 */
	public function ajax_parse_request( $wp ) {
		if ( array_key_exists( $this->endpoint, $wp->query_vars ) ) {
			add_filter( 'wp_doing_ajax', '__return_true' );
			$action = $wp->query_vars['action'];

			if ( 'jnews_subscribe_handler' === $action ) {
				$this->jnews_subscribe_handler();
			}
			do_action( 'jnews_ajax_' . $action );
		}
	}

	/**
	 * Ajax Handler
	 */
	public function jnews_subscribe_handler() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['after_submit'], $_REQUEST['file_id'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jnews-subscribe-nonce' ) ) {
			$after_submit = array();
			if ( ! empty( $_REQUEST['after_submit'] ) ) {
				$after_submit = explode( ',', $_REQUEST['after_submit'] );
			}
			$file_id   = $_REQUEST['file_id'];
			$email     = $_REQUEST['email'];
			$file_meta = jeg_metabox( 'subscribe_download_meta_box', null, $file_id );
			$image     = wp_get_attachment_image_src( get_post_thumbnail_id( $file_id ), 'large' );
			$response  = array();
			foreach ( $after_submit as $action => $name ) {
				switch ( $name ) {
					case 'email':
						$emails = Actions\Email::get_instance();
						$emails->__set( 'email_from', $file_meta['email_from'] );
						$emails->__set( 'email_from_name', $file_meta['email_from_name'] );
						$emails->__set( 'email_reply_to', $file_meta['email_reply_to'] );
						$emails->__set( 'email_content_type', $file_meta['email_content_type'] );
						$emails->__set( 'email_subject', $file_meta['email_subject'] );
						if ( $file_meta['file_url'] ) {
							$emails->__set( 'file_url', $file_meta['file_url'] );
						}
						$emails->__set( 'email_content', $emails->email_preview_template( $file_meta['email_content'] ) );
						if ( $emails->get_content_type() === 'text/html' ) {
							if ( $image ) {
								$emails->__set( 'image', $image[0] );
							}
							$emails->__set( 'email_content', $emails->email_preview_template( $file_meta['email_content'] ) );
						}
						$response['email'] = $emails->send( $email, $emails->__get( 'email_subject' ), $emails->__get( 'email_content' ), $emails->get_headers() );
						if ( $response['email'] ) {
							$response['email'] = array(
								'response' => '1',
								'string'   => __( 'Thank you! Download link was sent to your email', 'jnews-subscribe' ),
							);
						} else {
							$response['email'] = array(
								'response' => '0',
								'string'   => call_user_func(
									function () {
										$user = wp_get_current_user();
										$allowed_roles = array( 'editor', 'administrator', 'author' );
										if ( array_intersect( $allowed_roles, $user->roles ) ) {
											return '<i class="fa fa-times" aria-hidden="true"></i> ' . __( 'SMTP setup may not right. This Message is not visible for site visitors', 'jnews-subscribe' );
										} else {
											return '<i class="fa fa-times" aria-hidden="true"></i>';
										}
									}
								),
							);
						}
						break;
					case 'mailchimp':
						try {
							$handler = new Actions\Mailchimp( $file_meta['mailchimp_api_key'] );

							$subscriber['status_if_new'] = 'subscribed';
							$subscriber['status']        = 'subscribed';
							$subscriber['email_address'] = $email;

							$end_point = sprintf( 'lists/%s/members/%s', $file_meta['mailchimp_list'], md5( strtolower( $email ) ) );

							$response['mailchimp'] = $handler->post(
								$end_point,
								$subscriber,
								array(
									'method' => 'PUT', // Add or Update.
								)
							);

							if ( 200 !== $response['mailchimp']['code'] ) {
								$response['mailchimp'] = array(
									'response' => '0',
									'string'   => call_user_func(
										function () {
											$user = wp_get_current_user();
											$allowed_roles = array( 'editor', 'administrator', 'author' );
											if ( array_intersect( $allowed_roles, $user->roles ) ) {
												return '<i class="fa fa-times" aria-hidden="true"></i> ' . __( 'server_error, please try again later. This Message is not visible for site visitors', 'jnews-subscribe' );
											} else {
												return '<i class="fa fa-times" aria-hidden="true"></i>';
											}
										}
									),
								);
							} else {
								$response['mailchimp'] = array(
									'response' => '1',
									'string'   => jnews_return_translation( 'Thank you for subscribe! Download link was sent to your email', 'jnews-subscribe', 'thank_subscribe' ),
								);
							}
						} catch ( \Exception $exception ) {
							$response['mailchimp'] = array(
								'response' => '0',
								'string'   => call_user_func(
									function () use ( $exception ) {
										$user = wp_get_current_user();
										$allowed_roles = array( 'editor', 'administrator', 'author' );
										if ( array_intersect( $allowed_roles, $user->roles ) ) {
											return '<i class="fa fa-times" aria-hidden="true"></i> MailChimp ' . $exception->getMessage();
										} else {
											return '<i class="fa fa-times" aria-hidden="true"></i>';
										}
									}
								),
							);
						}
						break;
				}
			}
			wp_send_json(
				$response
			);
		}
		exit;
	}
}
