<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE;

use Jeg\Form\Form_Meta_Box;

/**
 * Class JNews_Subscribe_Meta_Box
 *
 * @package JNEWS_SUBSCRIBE
 */
class JNews_Subscribe_Meta_Box {

	/**
	 * Instance of JNews_Subscribe_Meta_Box class
	 *
	 * @var JNews_Subscribe_Meta_Box
	 */
	private static $instance;

	/**
	 * JNews_Subscribe_Meta_Box constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'initialize_metabox' ) );
	}

	/**
	 * Singleton page of JNews_Subscribe_Meta_Box class
	 *
	 * @return mixed
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize Metabox
	 */
	public function initialize_metabox() {
		$fields   = $this->metabox_fields();
		$segments = $this->metabox_segments();

		$option = array(
			'id'        => 'subscribe_download_meta_box',
			'title'     => esc_html__( 'Download Settings', 'jnews-subscribe' ),
			'post_type' => 'jnews-download',
			'type'      => 'tabbed',
			'fields'    => $fields,
			'segments'  => $segments,
		);

		if ( class_exists( 'Jeg\Form\Form_Meta_Box' ) ) {
			new Form_Meta_Box( $option );
		}
	}

	/**
	 * Metabox Fields
	 *
	 * @return array
	 */
	public function metabox_fields() {
		$default_message = sprintf( esc_html__( 'New message from %s', 'jnews-subscribe' ), get_option( 'blogname' ) );
		$site_domain     = $this->get_site_domain();
		$fields          = array();
		$template_desc   = 'Enter the text that is sent to users after completion of a successful subscribe. Available template tags:<br>{{file_url}} - The file url<br>{{image_url}} - The feature image url<br>{{date}} - The date of the subscribe<br>{{sitename}} - The site name<br>{{siteurl}} - The site url<br>{{logo_url}} - The logo url';

		$fields['file_url'] = array(
			'segment'     => 'file',
			'type'        => 'text',
			'title'       => esc_html__( 'File URL', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set a link to download file', 'jnews-subscribe' ),
		);
		// CONTENT TYPE.
		$fields['email_content_type'] = array(
			'type'        => 'select',
			'segment'     => esc_html__( 'email', 'jnews-subscribe' ),
			'title'       => esc_html__( 'Send As', 'jnews-subscribe' ),
			'default'     => 'html',
			'description' => wp_kses( sprintf( __( 'Select the content type that will be used for the email content.', 'jnews-subscribe' ), $template_desc ), wp_kses_allowed_html() ),
			'options'     => array(
				'html'  => esc_html__( 'HTML', 'jnews-subscribe' ),
				'plain' => esc_html__( 'Plain Text', 'jnews-subscribe' ),
			),
		);
		// SUBJECT.
		$fields['email_subject'] = array(
			'segment'     => esc_html__( 'email', 'jnews-subscribe' ),
			'type'        => 'text',
			'title'       => esc_html__( 'Subject', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set the email subject', 'jnews-subscribe' ),
			'default'     => $default_message,
		);
		// CONTENT.
		$fields['email_content'] = array(
			'segment'     => esc_html__( 'email', 'jnews-subscribe' ),
			'type'        => 'textarea',
			'sanitize'    => 'jnews_sanitize_by_pass',
			'title'       => esc_html__( 'Message', 'jnews-subscribe' ),
			'description' => wp_kses( sprintf( __( 'Want to customize Message fields? Please read more about the shortcode on our <a href="%s" target="_blank">documentation</a>.', 'jnews-subscribe' ), 'https://support.jegtheme.com/documentation/subscribe-to-download/' ), wp_kses_allowed_html() ),
			'default'     => 'Here is your download link {{file_url}}',
		);
		// FROM.
		$fields['email_from'] = array(
			'segment'     => esc_html__( 'email', 'jnews-subscribe' ),
			'type'        => 'text',
			'title'       => esc_html__( 'From Email', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set the sender address', 'jnews-subscribe' ),
			'default'     => 'email@' . $site_domain,
		);
		// NAME.
		$fields['email_from_name'] = array(
			'segment'     => esc_html__( 'email', 'jnews-subscribe' ),
			'type'        => 'text',
			'title'       => esc_html__( 'From Name', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set the sender name', 'jnews-subscribe' ),
			'default'     => get_bloginfo( 'name' ),
		);
		// REPLY TO.
		$fields['email_reply_to'] = array(
			'type'        => 'text',
			'segment'     => esc_html__( 'email', 'jnews-subscribe' ),
			'title'       => esc_html__( 'Reply-To', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set Reply-To address', 'jnews-subscribe' ),
		);

		// MAILCHIMP.
		$fields['mailchimp_api_key'] = array(
			'type'        => 'text',
			'segment'     => esc_html__( 'mailchimp', 'jnews-subscribe' ),
			'title'       => esc_html__( 'API Key', 'jnews-subscribe' ),
			'description' => wp_kses( sprintf( __( 'Use this field to set the API Key for the current form. Please click <a href="%s" target="_blank">here</a> to know about API Key .', 'jnews-subscribe' ), 'https://mailchimp.com/help/about-api-keys/' ), wp_kses_allowed_html() ),
		);
		// MAILCHIMP LIST.
		$fields['mailchimp_list'] = array(
			'type'        => 'text',
			'segment'     => esc_html__( 'mailchimp', 'jnews-subscribe' ),
			'title'       => esc_html__( 'Audience ID', 'jnews-subscribe' ),
			'description' => wp_kses( sprintf( __( 'Use this field to set the Audience ID. Please click <a href="%s" target="_blank">here</a> about how to know your Audience ID .', 'jnews-subscribe' ), 'https://mailchimp.com/help/find-audience-id/' ), wp_kses_allowed_html() ),
		);

		return $fields;
	}

	/**
	 * Get Site Domain
	 *
	 * @return string|string[]
	 */
	public function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	/**
	 * Metabox Segment
	 *
	 * @return array
	 */
	public function metabox_segments() {
		$segment = array();

		$segment['file'] = array(
			'name'     => esc_html__( 'File Segment', 'jnews-subscribe' ),
			'priority' => 1,
		);

		$segment['email'] = array(
			'name'     => esc_html__( 'Email', 'jnews-subscribe' ),
			'priority' => 1,
		);

		$segment['mailchimp'] = array(
			'name'     => esc_html__( 'MailChimp', 'jnews-subscribe' ),
			'priority' => 1,
		);

		return $segment;
	}

}
