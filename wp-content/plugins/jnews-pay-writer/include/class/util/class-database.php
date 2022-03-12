<?php
/**
 * Database Class
 *
 * @package jnews-pay-writer
 * @author Jegtheme
 * @since 10.0.0
 */

namespace JNews\PAY_WRITER\Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Database
 * phpcs:disable WordPress.DB.PreparedSQL,Squiz.Commenting.FunctionComment.MissingParamComment
 */
class Database {

	/**
	 * Class instance
	 *
	 * @var Database
	 */
	private static $instance;

	/**
	 * WPDB instance
	 *
	 * @var \wpdb
	 */
	private $wpdb;

	/**
	 * Character set and Collate
	 *
	 * @var string
	 */
	private $charset_collate = '';

	/**
	 * Contain ajax action
	 *
	 * @var string
	 */
	private $async_request = 'jpwt_async_request';

	/**
	 * Return class instance
	 *
	 * @return Database
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor
	 */
	private function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->setup_hook();
	}

	/**
	 * Setup hook
	 */
	private function setup_hook() {
		add_action( "wp_ajax_{$this->async_request}", array( $this, 'async_request_handler' ) );
		add_action( "wp_ajax_nopriv_{$this->async_request}", array( $this, 'async_request_handler' ) );
	}

	/**
	 * Async request handler
	 */
	public function async_request_handler() {
		session_write_close();
		check_ajax_referer( $this->async_request, 'nonce' );
		if ( isset( $_POST['action_type'] ) && ! empty( $_POST['action_type'] ) ) {
			$action_type = sanitize_key( $_POST['action_type'] );
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			switch ( $action_type ) {
				case 'split_payment_status':
					$args        = isset( $_POST['args'] ) ? wp_unslash( $_POST['args'] ) : array();
					$will_update = isset( $_POST['will_update'] ) ? wp_unslash( $_POST['will_update'] ) : array();
					if ( ( is_array( $args ) && ! empty( $args ) ) && ( is_array( $will_update ) && ! empty( $will_update ) ) ) {
						$this->split_payment_status( $args, $will_update );
					}
					break;
				case 'update_payment_status':
					$args           = isset( $_POST['args'] ) ? wp_unslash( $_POST['args'] ) : array();
					$sender_item_id = isset( $_POST['sender_item_id'] ) ? wp_unslash( $_POST['sender_item_id'] ) : array();
					if ( ( is_array( $args ) && ! empty( $args ) ) && ( is_array( $sender_item_id ) && ! empty( $sender_item_id ) ) ) {
						$this->update_payment_status( $args, $sender_item_id );
					}
					break;
				case 'split_payment_summaries':
					$ids      = isset( $_POST['ids'] ) ? wp_unslash( $_POST['ids'] ) : array();
					$payments = isset( $_POST['payments'] ) ? wp_unslash( $_POST['payments'] ) : array();
					if ( ( is_array( $ids ) && ! empty( $ids ) ) && ( is_array( $payments ) && ! empty( $payments ) ) ) {
						$this->split_payment_summaries( $ids, $payments );
					}
					break;
				case 'update_payment_summaries':
					$total_in_past = isset( $_POST['total_in_past'] ) ? wp_unslash( $_POST['total_in_past'] ) : array();
					$payments      = isset( $_POST['payments'] ) ? wp_unslash( $_POST['payments'] ) : array();
					if ( ( is_array( $total_in_past ) && ! empty( $total_in_past ) ) && ( is_array( $payments ) && ! empty( $payments ) ) ) {
						$this->update_payment_summaries( $total_in_past, $payments );
					}
					break;
				case 'update_payments':
					$payments = isset( $_POST['payments'] ) ? wp_unslash( $_POST['payments'] ) : array();
					if ( ( is_array( $payments ) && ! empty( $payments ) ) ) {
						$this->update_payments( $payments );
					}
					break;
			}
			// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
		wp_die();
	}

	/**
	 * Dispatch async request
	 *
	 * @param array $args
	 */
	public function async_request( $args ) {
		$query    = array(
			'action' => $this->async_request,
			'nonce'  => wp_create_nonce( $this->async_request ),
		);
		$request  = array(
			'method'    => 'POST',
			'body'      => $args,
			'timeout'   => 0.01,
			'blocking'  => false,
			'cookies'   => $_COOKIE,
			'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
		);
		$url      = add_query_arg( $query, admin_url( 'admin-ajax.php' ) );
		$response = wp_remote_request( $url, $request );
		if ( is_wp_error( $response ) ) {
			\JNews\PAY_WRITER\Helper::logging( 'JNews - Pay Writer Error ' . $response->get_error_code() . ' : ' . $response->get_error_message() );
		}
	}

	/**
	 * Create table
	 */
	public function create_table() {
		if ( ! empty( $this->wpdb->charset ) ) {
			$this->charset_collate = "DEFAULT CHARACTER SET {$this->wpdb->charset} ";
		}

		if ( ! empty( $this->wpdb->collate ) ) {
			$this->charset_collate .= "COLLATE {$this->wpdb->collate}";
		}

		/**
		 * Data table
		 */
		$table_data = $this->get_table_name( 'data' );

		if ( $this->wpdb->get_var( "SHOW TABLES LIKE '$table_data'" ) !== $table_data ) { // phpcs:ignore WordPress.DB.PreparedSQL
			$sql = 'CREATE TABLE ' . $table_data . ' (
					`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
					`postid` BIGINT(20) NOT NULL,
					`userid` BIGINT(20) NOT NULL,
					`sender_item_id` VARCHAR(63) NOT NULL,
					`payment_date` DATE NOT NULL,
					`payment_datetime` DATETIME NOT NULL,
					`payment_status` VARCHAR(10) NOT NULL,
					`amount` FLOAT(20,2) NOT NULL,
					PRIMARY KEY (`ID`)
                ) ' . $this->charset_collate . ' ENGINE=InnoDB;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		/**
		 * Summary table
		 */
		$table_summary = $this->get_table_name( 'summary' );

		if ( $this->wpdb->get_var( "SHOW TABLES LIKE '$table_summary'" ) !== $table_summary ) { // phpcs:ignore WordPress.DB.PreparedSQL
			$sql = 'CREATE TABLE ' . $table_summary . ' (
					`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
					`postid` BIGINT(20) NOT NULL UNIQUE,
					`userid` BIGINT(20) NOT NULL,
					`payment_date` DATE NOT NULL,
					`payment_datetime` DATETIME NOT NULL,
					`amount` FLOAT(20,2) NOT NULL,
					PRIMARY KEY (`ID`)
				) ' . $this->charset_collate . ' ENGINE=InnoDB;';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}
	}

	/**
	 * Get table name
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	private function get_table_name( $name ) {
		if ( 'data' === $name ) {
			return $this->wpdb->prefix . JNEWS_PAY_WRITER_DB_DATA;
		}

		if ( 'summary' === $name ) {
			return $this->wpdb->prefix . JNEWS_PAY_WRITER_DB_SUMMARY;
		}
	}

	/**
	 * Get Blog IDs
	 *
	 * @return array
	 */
	public function get_blog_ids() {
		return $this->wpdb->get_col( "SELECT blog_id FROM {$this->wpdb->blogs}" );
	}

	/**
	 * Get Payment data
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	/**
	 * Get payment data
	 *
	 * @param array   $args
	 * @param boolean $total
	 *
	 * @return array
	 */
	public function get_payment_data( $args, $total = false ) {
		if ( $total ) {
			$sql = $this->wpdb->prepare( "SELECT userid, sender_item_id, payment_datetime, payment_status, SUM(amount) as amount FROM {$this->get_table_name( 'data' )} WHERE userid=%d AND payment_datetime BETWEEN %s AND %s GROUP BY sender_item_id", $args );
		} else {
			$sql = $this->wpdb->prepare( "SELECT ID, postid, userid, sender_item_id, payment_datetime, payment_status, amount FROM {$this->get_table_name( 'data' )} WHERE userid=%d AND payment_datetime BETWEEN %s AND %s", $args );
		}
		return $this->wpdb->get_results( $sql, 'ARRAY_A' );
	}

	/**
	 * Get Payment summary_data
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_payment_summary( $time_start, $time_end ) {
		$time_start = date( 'Y-m-d H:i:s', $time_start );
		$time_end   = date( 'Y-m-d H:i:s', $time_end );
		$sql        = $this->wpdb->prepare( "SELECT postid, userid, payment_date, payment_datetime, amount FROM {$this->get_table_name('summary')} WHERE payment_datetime BETWEEN %s AND %s", $time_start, $time_end );
		return $this->wpdb->get_results( $sql, 'ARRAY_A' );
	}


	/**
	 * Get total payment
	 *
	 * @param array $ids
	 *
	 * @return array|object|null
	 */
	private function get_total_payment_raw( $ids ) {
		$placeholder = '';
		foreach ( $ids as $id ) {
			$placeholder .= '%d, ';
		}
		$placeholder = rtrim( $placeholder, ', ' );
		$prepare     = $this->wpdb->prepare( "SELECT `postid`, SUM(`amount`) AS `amount` FROM {$this->get_table_name('data')} WHERE `postid` IN ({$placeholder}) AND `payment_status`!='FAILED' GROUP BY `postid`", $ids );
		$result      = $this->wpdb->get_results( $prepare, OBJECT_K ); // phpcs:ignore WordPress.DB.PreparedSQL

		return $result;
	}

	/**
	 * Split payment summary to db
	 *
	 * @param array $args
	 * @param array $will_update
	 */
	private function split_payment_status( $args, $will_update ) {
		foreach ( $will_update as $status => $unique_id ) {
			$split_unique_id = array_chunk( $unique_id, 5000 );
			$args            = array(
				'payment_status'   => strtoupper( sanitize_text_field( $status ) ),
				'payment_date'     => $args['payment_date'],
				'payment_datetime' => $args['payment_datetime'],
			);
			foreach ( $split_unique_id as $unique_id ) {
				$async_request_args = array(
					'action_type'    => 'update_payment_status',
					'args'           => wp_slash( $args ),
					'sender_item_id' => wp_slash( $unique_id ),
				);
				$this->async_request( $async_request_args );
			}
		}
	}

	/**
	 * Update payment summary to db
	 *
	 * @param array $args
	 * @param array $sender_item_id
	 */
	private function update_payment_status( $args, $sender_item_id ) {
		// first update status.
		$value      = array();
		$fields     = array();
		$conditions = array();
		foreach ( $args as $field => $data ) {
			$fields[] = "`$field` = %s";
			array_push( $value, $data );
		}
		foreach ( $sender_item_id as $id ) {
			$conditions[] = '%s';
			array_push( $value, $id );
		}

		$fields     = implode( ', ', $fields );
		$conditions = implode( ', ', $conditions );

		$sql    = "UPDATE `{$this->get_table_name( 'data' )}` SET $fields WHERE sender_item_id IN($conditions)";
		$result = $this->wpdb->query( $this->wpdb->prepare( $sql, $value ) );
		if ( $result ) {
			// Now update total amount.
			$value      = array();
			$conditions = array();
			foreach ( $sender_item_id as $id ) {
				$conditions[] = '%s';
				array_push( $value, $id );
			}
			$conditions = implode( ', ', $conditions );
			$sql        = "SELECT `postid` FROM `{$this->get_table_name( 'data' )}` WHERE sender_item_id IN($conditions) GROUP BY `postid`";
			$ids        = $this->wpdb->get_col( $this->wpdb->prepare( $sql, $value ) );
			if ( ! empty( $ids ) ) {
				$payments = array();
				foreach ( $ids as $id ) {
					array_push(
						$payments,
						array(
							'postid'           => intval( $id ),
							'payment_date'     => $args['payment_date'],
							'payment_datetime' => $args['payment_datetime'],
						)
					);
				}
				$async_request_args = array(
					'action_type' => 'split_payment_summaries',
					'ids'         => wp_slash( $ids ),
					'payments'    => wp_slash( $payments ),
				);
				$this->async_request( $async_request_args );
			}
		}

	}

	/**
	 * Split payment summary into chunks
	 *
	 * @param array $ids
	 * @param array $payments
	 */
	private function split_payment_summaries( $ids, $payments ) {
		if ( ! empty( $ids ) ) {
			$total_in_past = $this->get_total_payment_raw( $ids );
			if ( $total_in_past ) {
				$split_payments = array_chunk( $payments, 5000 );
				foreach ( $split_payments as $payments ) {
					$async_request_args = array(
						'action_type'   => 'update_payment_summaries',
						'total_in_past' => wp_slash( $total_in_past ),
						'payments'      => wp_slash( $payments ),
					);
					$this->async_request( $async_request_args );
				}
			}
		}
	}

	/**
	 * Insert/update bulk payment summary to db
	 *
	 * @param array $total_in_past
	 * @param array $payments
	 */
	private function update_payment_summaries( $total_in_past, $payments ) {
		$summary_array       = array();
		$summary_placeholder = '';
		foreach ( $payments as $key => $payment ) {
			if ( isset( $total_in_past[ $payment['postid'] ] ) && 'FAILED' !== $payment['payment_status'] ) {
				$summary_placeholder .= '(%d, %d, %s, %s, %f), ';
				array_push( $summary_array, intval( $payment['postid'] ) );
				array_push( $summary_array, intval( $payment['authorid'] ) );
				array_push( $summary_array, $payment['payment_date'] );
				array_push( $summary_array, $payment['payment_datetime'] );
				array_push( $summary_array, floatval( $total_in_past[ $payment['postid'] ]['amount'] ) );
			}
		}

		if ( '' !== $summary_placeholder ) {
			// Payment summary query.
			$summary_query  = "INSERT INTO {$this->get_table_name('summary')} (postid, userid, payment_date, payment_datetime, amount) VALUES " . rtrim( $summary_placeholder, ', ' );
			$summary_query .= version_compare( 8.0, $this->wpdb->db_version(), '<=' ) ? ' AS new ON DUPLICATE KEY UPDATE payment_date = new.payment_date, payment_datetime = new.payment_datetime, amount = new.amount;' : ' ON DUPLICATE KEY UPDATE payment_date = VALUES(payment_date), payment_datetime = VALUES(payment_datetime), amount = VALUES(amount);';
			$this->wpdb->query( $this->wpdb->prepare( $summary_query, $summary_array ) );
		}
	}

	/**
	 * Insert payment to db
	 *
	 * @param array $payments
	 */
	private function update_payments( $payments ) {
		$this->wpdb->show_errors();

		$date      = current_time( 'Y-m-d' );
		$date_time = current_time( 'Y-m-d H:i:s' );

		// build values for payment_data query.
		$data_placeholder = '';
		$ids              = array();
		$data_array       = array();

		foreach ( $payments as $key => &$payment ) {
			$payment['payment_date']     = $date;
			$payment['payment_datetime'] = $date_time;

			array_push( $ids, intval( $payment['postid'] ) );

			$data_placeholder .= '(%d, %d, %s, %s, %s, %s, %f), ';
			array_push( $data_array, intval( $payment['postid'] ) );
			array_push( $data_array, intval( $payment['authorid'] ) );
			array_push( $data_array, $payment['sender_item_id'] );
			array_push( $data_array, $date );
			array_push( $data_array, $date_time );
			array_push( $data_array, $payment['payment_status'] );
			array_push( $data_array, floatval( $payment['amount'] ) );
		}

		$insert_data_query = "INSERT INTO {$this->get_table_name('data')} (postid, userid, sender_item_id, payment_date, payment_datetime, payment_status, amount) VALUES " . rtrim( $data_placeholder, ', ' );
		if ( ! empty( $data_array ) ) {
			$insert_data_query = $this->wpdb->prepare( $insert_data_query, $data_array );
			$this->wpdb->query( $insert_data_query );
		}
		$async_request_args = array(
			'action_type' => 'split_payment_summaries',
			'ids'         => wp_slash( $ids ),
			'payments'    => wp_slash( $payments ),
		);
		$this->async_request( $async_request_args );
	}
}
