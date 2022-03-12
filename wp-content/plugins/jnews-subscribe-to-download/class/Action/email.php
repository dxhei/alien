<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE\Actions;

/**
 * Class Email
 *
 * @package JNEWS_SUBSCRIBE\Actions
 */
class Email {

	/**
	 * @var Email
	 */
	private static $instance;

	private $email_content_type;
	private $email_content;
	private $email_from;
	private $email_from_name;
	private $email_reply_to;
	private $email_subject;
	private $message;
	private $headers;
	private $file_url;
	private $image;

	/**
	 * Singleton page of Email class
	 *
	 * @return Email
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * TODO: Implement __get() method.
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->$name;
	}

	/**
	 * TODO: Implement __set() method.
	 *
	 * @param $name
	 * @param $value
	 */
	public function __set( $name, $value ) {
		$this->$name = $value;
	}

	/**
	 * @return mixed|void
	 */
	public function get_headers() {
		if ( ! $this->headers ) {
			$this->headers  = "From: {$this->email_from_name} <{$this->email_from}>\r\n";
			$this->headers .= ! empty( $this->get_email_reply_to() ) ? $this->get_email_reply_to() : '';
			$this->headers .= "Content-Type: {$this->get_content_type()}; charset=utf-8\r\n";
		}

		return apply_filters( 'jnews_subscribe_email_headers', $this->headers );
	}

	/**
	 * @return string
	 */
	public function get_email_reply_to() {
		$replyTo              = '';
		$this->email_reply_to = apply_filters( 'jnews_subscribe_email_reply_to', $this->email_reply_to );
		if ( ! empty( $this->email_reply_to ) ) {
			$replyTo = "Reply-To: {$this->email_reply_to}\r\n";
		}

		return $replyTo;
	}

	/**
	 * @return mixed|void
	 */
	public function get_content_type() {

		if ( 'html' === $this->email_content_type || 'text/html' === $this->email_content_type ) {
			$this->email_content_type = 'text/html';
		} else {
			$this->email_content_type = 'text/plain';
		}

		return apply_filters( 'jnews_subscribe_email_content_type', $this->email_content_type );
	}

	/**
	 * @param $message
	 *
	 * @return string|string[]
	 */
	public function email_preview_template( $message ) {
		if ( isset( $this->file_url ) ) {
			$message = str_replace( '{{file_url}}', $this->file_url, $message );
		}
		if ( isset( $this->image ) ) {
			$message = str_replace( '{{image_url}}', $this->image, $message );
		}
		$message = str_replace( '{{date}}', date( get_option( 'date_format' ), current_time( 'timestamp' ) ), $message );
		$message = str_replace( '{{sitename}}', get_bloginfo( 'name' ), $message );
		$message = str_replace( '{{siteurl}}', get_bloginfo( 'url' ), $message );
		$message = str_replace( '{{logo_url}}', get_theme_mod( 'jnews_header_logo', get_parent_theme_file_uri( 'assets/img/logo.png' ) ), $message );

		return $message;
	}

	/**
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param $headers
	 *
	 * @return bool|void
	 */
	public function send( $to, $subject, $message, $headers ) {
		return wp_mail( $to, $subject, $message, $headers );
	}
}
