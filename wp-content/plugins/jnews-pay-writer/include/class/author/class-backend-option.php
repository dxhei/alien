<?php
/**
 * Backend Option
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package JNews\PAY_WRITER\Author
 */

namespace JNews\PAY_WRITER\Author;

use JNews\Archive\Builder\OptionAbstract;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Backend_Option
 */
class Backend_Option extends OptionAbstract {

	/**
	 * Setup Hook
	 */
	protected function setup_hook() {
		add_action( 'show_user_profile', array( $this, 'render_options' ) );
		add_action( 'edit_user_profile', array( $this, 'render_options' ) );

		add_action( 'edit_user_profile_update', array( $this, 'save_user' ) );
		add_action( 'personal_options_update', array( $this, 'save_user' ) );

		add_action( 'wpmu_delete_user', array( $this, 'remove_user_data' ) );
		add_action( 'delete_user', array( $this, 'remove_user_data' ) );
		add_action( 'make_spam_user', array( $this, 'remove_user_data' ) );
	}

	/**
	 * Removes pay writer data for all users from a user who is deleted or spammed
	 *
	 * @param int $user_id
	 */
	public function remove_user_data( $user_id ) {
		do_action( 'jpwt_before_remove_user_data', $user_id );

		$options = $this->get_options();
		$this->do_delete( $options, $user_id );

		do_action( 'jpwt_remove_user_data', $user_id );
	}

	/**
	 * Save pay writer data for all users from a user who is save user data
	 *
	 * @param int $user_id
	 */
	public function save_user( $user_id ) {
		if ( current_user_can( 'edit_user', $user_id ) ) {
			$options = $this->get_options();
			$this->do_save( $options, $user_id );
		}
	}

	/**
	 * @param \WP_User $user
	 *
	 * @return int|null
	 */
	protected function get_id( $user ) {
		if ( ! isset( $user->ID ) || empty( $user->ID ) ) {
			return null;
		} else {
			return $user->ID;
		}
	}

	/**
	 * @param string $key
	 * @param int    $user_id
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get_value( $key, $user_id, $default ) {
		$value = get_user_option( $key, $user_id );

		if ( $value ) {
			return $value;
		} else {
			return $default;
		}
	}

	/**
	 * @param array $options
	 * @param int   $user_id
	 */
	protected function do_save( $options, $user_id ) {
		foreach ( $options as $key => $field ) {
			$option = isset( $_POST[ $key ] ) && ! empty( $_POST[ $key ] ) ? sanitize_email( $_POST[ $key ] ) : $field['default'];
			$option = empty( $option ) ? false : $option;
			update_user_option( $user_id, $key, $option );
		}
	}

	/**
	 * @param array $options
	 * @param int   $user_id
	 */
	protected function do_delete( $options, $user_id ) {
		foreach ( $options as $key => $field ) {
			delete_user_option( $user_id, $key );
		}
	}

	/**
	 * @return array
	 */
	public function prepare_segments() {
		$segments = array();

		$segments[] = array(
			'id'   => 'jnews-paypal-credential',
			'name' => esc_html__( 'JNews Paypal Credential', 'jnews-pay-writer' ),
		);

		return $segments;
	}

	/**
	 * @return array
	 */
	protected function get_options() {
		$options = array();

		$options['paypal_account'] = array(
			'segment' => 'jnews-paypal-credential',
			'title'   => esc_html__( 'Paypal Account', 'jnews-pay-writer' ),
			'desc'    => wp_kses( __( 'Please enter a valid Paypal account Id or Paypal email', 'jnews-pay-writer' ), wp_kses_allowed_html() ),
			'type'    => 'text',
			'default' => '',
		);

		return $options;
	}
}
