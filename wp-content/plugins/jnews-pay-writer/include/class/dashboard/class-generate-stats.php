<?php
/**
 * Generate Stats
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */

namespace JNews\PAY_WRITER\Dashboard;

use JNews\PAY_WRITER\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Generate Stats
 *
 * @package JNews\PAY_WRITER\Dashboard
 */
class Generate_Stats {

	/**
	 * @var array
	 */
	public static $grp_args;

	/**
	 * Produce Stats
	 *
	 * @param string       $time_start
	 * @param string       $time_end
	 * @param null|array   $author
	 * @param null|boolean $format
	 *
	 * @return \WP_Error|array
	 */
	public static function produce_stats( $time_start, $time_end, $author = null, $format = null, $regenerate = true ) {
		global $current_user;

		$return = array();
		if ( $regenerate ) {
			// we don't need to check another user
			$requested_posts = self::get_requested_posts( $time_start, $time_end, $author );

			if ( is_wp_error( $requested_posts ) ) {
				return $requested_posts;
			}

			$stats = self::group_stats_by_author( $requested_posts );
			if ( is_wp_error( $stats ) ) {
				return $stats;
			}

			$stats = self::data2cash( $stats, $author );
			if ( is_wp_error( $stats ) ) {
				return $stats;
			}

			$stats = self::calculate_total_stats( $stats );
			if ( is_wp_error( $stats ) ) {
				return $stats;
			}

			if ( empty( $stats ) ) {
				return new \WP_Error( 'jpwt_empty_selection_after_all', __( 'Your query resulted in an empty result. Try to select a wider time range!', 'jnews-pay-writer' ), array() );
			}

			$return['raw_stats'] = $stats;

			$stats = self::process_total_paid_unpaid( $stats, $time_start, $time_end );

			if ( $format ) {
				$formatted_stats = self::format_stats_for_output( $stats, $author );
				if ( is_wp_error( $formatted_stats ) ) {
					return $formatted_stats;
				}

				$return['formatted_stats'] = $formatted_stats;
			}
			set_transient( 'jpwt_payout_stats', $return, DAY_IN_SECONDS );
			return $return;
		}
		return get_transient( 'jpwt_payout_stats' );

	}

	public static function process_total_paid_unpaid( $stats, $time_start, $time_end ) {
		$total_paid_posts     = array();
		$raw_total_paid_posts = JNews_Pay_Writer()->database->get_payment_summary( $time_start, $time_end );
		foreach ( $raw_total_paid_posts as $raw_total_paid ) {
			$total_paid_posts[ $raw_total_paid['postid'] ] = $raw_total_paid['amount'];
		}

		foreach ( $stats as $author_id => &$author_stats ) {
			$stats[ $author_id ]['total']['jpwt_payment']['author_total_paid'] = 0;
			$stats[ $author_id ]['total']['jpwt_payment']['author_unpaid']     = 0;

			foreach ( $author_stats as $single ) {
				if ( isset( $single->jpwt_payment ) ) {
					$single->jpwt_payment['post_total_paid'] = isset( $total_paid_posts[ $single->ID ] ) ? self::normalize_payment_currency( $total_paid_posts[ $single->ID ] ) : 0.0;
					$author_unpaid                           = $single->jpwt_payment['total'] - $single->jpwt_payment['post_total_paid'];
					$single->jpwt_payment['post_unpaid']     = 0 <= $author_unpaid ? self::normalize_payment_currency( $author_unpaid ) : 0.0;
					$stats[ $author_id ]['total']['jpwt_payment']['author_total_paid'] += $single->jpwt_payment['post_total_paid'];
					$stats[ $author_id ]['total']['jpwt_payment']['author_unpaid']     += $single->jpwt_payment['post_unpaid'];
				}
			}
			$stats[ $author_id ]['total']['jpwt_payment']['author_total_paid'] = self::normalize_payment_currency( $stats[ $author_id ]['total']['jpwt_payment']['author_total_paid'] );
			$stats[ $author_id ]['total']['jpwt_payment']['author_unpaid']     = self::normalize_payment_currency( $stats[ $author_id ]['total']['jpwt_payment']['author_unpaid'] );
		}

		return $stats;
	}

	/**
	 * Get Requested Post
	 *
	 * @param string $time_start
	 * @param string $time_end
	 * @param null   $author
	 *
	 * @return \WP_Error|\WP_Post[]|int[]|null
	 */
	public static function get_requested_posts( $time_start, $time_end, $author = null ) {
		self::$grp_args = array(
			'post_type'           => array( 'post' ),
			'post_status'         => array( 'publish' ),
			'date_query'          => array(
				'after'     => date( 'Y-m-d H:i:s', $time_start ),
				'before'    => date( 'Y-m-d H:i:s', $time_end ),
				'inclusive' => true,
			),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'posts_per_page'      => -1,
			'ignore_sticky_posts' => 1,
			'suppress_filters'    => false,
			'cache_results'       => false,
		);

		if ( $author ) {
			self::$grp_args['author__in'] = $author;
		}

		$requested_posts = new \WP_Query( self::$grp_args );

		if ( $requested_posts->no_found_rows === false ) {
			return new \WP_Error( 'jpwt_empty_selection', __( 'Your query resulted in an empty result. Try to select a wider time range!', 'jnews-pay-writer' ), array() );
		}

		return $requested_posts->posts;
	}

	public static function produce_history_stats( $time_start, $time_end, $author = null, $detail = false ) {
		$ids               = array();
		$query_args        = array(
			'author'     => $author[0],
			'date_start' => date( 'Y-m-d H:i:s', $time_start ),
			'date_end'   => date( 'Y-m-d H:i:s', $time_end ),
		);
		$result            = JNews_Pay_Writer()->database->get_payment_data( $query_args, ! $detail );
		$formatted_history = array();
		foreach ( $result as $value ) {
			if ( ! $detail ) {
				if ( ! array_key_exists( $value['sender_item_id'], $formatted_history ) ) {
					$formatted_history [ $value['sender_item_id'] ]['author_id']        = $value['userid'];
					$formatted_history [ $value['sender_item_id'] ]['payment_datetime'] = $value['payment_datetime'];
					$formatted_history [ $value['sender_item_id'] ]['payment_status']   = $value['payment_status'];
					$formatted_history [ $value['sender_item_id'] ]['amount']           = 0;
				}
				$formatted_history [ $value['sender_item_id'] ]['amount'] += $value['amount'];
			} else {
				$ids[] = $value['postid'];
				$formatted_history [ $value['ID'] ]['author_id']        = $value['userid'];
				$formatted_history [ $value['ID'] ]['post_id']          = $value['postid'];
				$formatted_history [ $value['ID'] ]['sender_item_id']   = $value['sender_item_id'];
				$formatted_history [ $value['ID'] ]['payment_datetime'] = $value['payment_datetime'];
				$formatted_history [ $value['ID'] ]['payment_status']   = $value['payment_status'];
				$formatted_history [ $value['ID'] ]['amount']           = $value['amount'];
			}
		}
		if ( $detail ) {
			$posts = get_posts(
				array(
					'numberposts' => -1,
					'include'     => implode( ',', $ids ),
				)
			);
			foreach ( $posts as $post ) {
				foreach ( $formatted_history as $id => &$data ) {
					if ( (int) $data['post_id'] === $post->ID ) {
						$data['post_title'] = $post->post_title;
						$data['post']       = $post;
					}
				}
			}
		}
		return $formatted_history;
	}

	/**
	 * group_stats_by_author
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public static function group_stats_by_author( $data ) {
		$sorted_array = array();
		foreach ( $data as $post_id => $single ) {
			$sorted_array[ $single->post_author ][ $post_id ] = $single;
		}

		return apply_filters( 'jpwt_grouped_by_author_stats', $sorted_array );
	}

	/**
	 * Check if can save stats order
	 */
	public static function default_stats_order() {
		// Exit if disabled
		if ( ! JNews_Pay_Writer()->options['display']['save_stats_order'] ) {
			return;
		}

		// If there is a saved sorting, use it
		if ( ! isset( $_GET['orderby'] ) and isset( $_COOKIE[ 'jpwt_' . JNews_Pay_Writer()->settings['current_page'] . '_orderby' ] ) ) {
			$redirect_url = admin_url( 'admin.php' ) . '?' . $_SERVER['QUERY_STRING'] . '&orderby=' . $_COOKIE[ 'jpwt_' . JNews_Pay_Writer()->settings['current_page'] . '_orderby' ];

			if ( isset( $_COOKIE[ 'jpwt_' . JNews_Pay_Writer()->settings['current_page'] . '_order' ] ) ) {
				$redirect_url .= '&order=' . $_COOKIE[ 'jpwt_' . JNews_Pay_Writer()->settings['current_page'] . '_order' ];
			}

			wp_safe_redirect( $redirect_url );

		}

		// Store stats sorting settings, cookies expire in 6 months
		if ( isset( $_GET['orderby'] ) ) {
			setcookie( 'jpwt_' . JNews_Pay_Writer()->settings['current_page'] . '_orderby', htmlentities( $_GET['orderby'] ), time() + ( 86400 * 180 ) );

			if ( isset( $_GET['order'] ) ) {
				setcookie( 'jpwt_' . JNews_Pay_Writer()->settings['current_page'] . '_order', htmlentities( $_GET['order'] ), time() + ( 86400 * 180 ) );
			}
		}

	}

	/**
	 * Set default stats time range
	 */
	public static function get_default_stats_time_range() {

		// First and Last available post time args
		$args = array(
			'post_type'      => array( 'post' ),
			'posts_per_page' => 1,
			'orderby'        => 'post_date',
			'order'          => 'ASC',
		);

		// First available post time
		$first_available_post_time = JNews_Pay_Writer()->options['display']['first_available_post_time'];
		if ( $first_available_post_time['exp'] < time() ) {
			$first_available_post = new \WP_Query( $args );
			if ( $first_available_post->no_found_rows === false ) {
				$first_available_post_time['exp']  = time();
				$first_available_post_time['time'] = current_time( 'timestamp' );
			} else {
				$first_available_post_time['exp']  = time() + ( mt_rand( 1, 60 ) * 60 );
				$first_available_post_time['time'] = strtotime( $first_available_post->posts[0]->post_date );
			}
			Helper::update_display_option( 'first_available_post_time', $first_available_post_time );
		}
		JNews_Pay_Writer()->settings['first_available_post_time'] = $first_available_post_time['time'];

		// Last available post time
		$args['order']            = 'DESC';
		$last_available_post_time = JNews_Pay_Writer()->options['display']['last_available_post_time'];
		if ( $last_available_post_time['exp'] < time() ) {
			$last_available_post = new \WP_Query( $args ); // for future scheduled posts

			$last_available_post_time['exp'] = time() + ( mt_rand( 1, 60 ) * 60 );
			if ( $last_available_post->no_found_rows === false ) {
				$last_available_post_time['time'] = strtotime( $last_available_post->posts[0]->post_date );
			}

			if ( $last_available_post_time['time'] < current_time( 'timestamp' ) ) {
				$last_available_post_time['time'] = current_time( 'timestamp' ); // Pub Bonus needs to select even days without posts in the future, maybe there are publishings
			}
			Helper::update_display_option( 'last_available_post_time', $last_available_post_time );
		}
		JNews_Pay_Writer()->settings['last_available_post_time'] = $last_available_post_time['time'];

		// Default time range already done
		if ( isset( JNews_Pay_Writer()->settings['stats_tstart'] ) ) {
			return;
		}

		// Define default time range monthly
		JNews_Pay_Writer()->settings['stats_tstart'] = strtotime( '00:00:00' ) - ( ( date( 'j' ) - 1 ) * 24 * 60 * 60 ); // starts from timestamp of current day and subtracts seconds for enough days (depending on what day is today)
		JNews_Pay_Writer()->settings['stats_tend']   = strtotime( '23:59:59' );
	}

	public static function get_the_author_link( $author_id, $history = false, $history_detail = false ) {

		$link = admin_url( JNews_Pay_Writer()->settings['stats_menu_link'] . '&amp;author=' . $author_id . '&amp;tstart=' . JNews_Pay_Writer()->settings['stats_tstart'] . '&amp;tend=' . JNews_Pay_Writer()->settings['stats_tend'] );

		if ( isset( $_REQUEST['jpwt-time-range'] ) and ! empty( $_REQUEST['jpwt-time-range'] ) ) {
			$link .= '&amp;jpwt-time-range=' . sanitize_text_field( $_REQUEST['jpwt-time-range'] );
		}

		if ( isset( $_REQUEST['paged'] ) and ! empty( $_REQUEST['paged'] ) ) {
			$link .= '&amp;paged=' . sanitize_text_field( $_REQUEST['paged'] );
		}

		if ( $history ) {
			if ( $history_detail ) {
				$link .= '&amp;history-detail';
			} else {
				$link .= '&amp;history';
			}
		}

		return apply_filters( 'jpwt_get_author_link', $link );
	}

	public static function format_stats_for_output( $data, $author = null ) {
		$formatted_stats = array(
			'cols'  => array(),
			'stats' => array(),
		);
		if ( is_array( $author ) && 'stats_general' !== JNews_Pay_Writer()->settings['current_page'] ) {
			foreach ( $data as $author_id_foreach => $author_stats_foreach ) {
				$author_id    = $author_id_foreach;
				$author_stats = $author_stats_foreach;
			}
			$post_stats = current( $author_stats );

			$formatted_stats['cols']['post_title']            = __( 'Title', 'jnews-pay-writer' );
			$formatted_stats['cols']['post_publication_date'] = __( 'Pub. Date', 'jnews-pay-writer' );

			// payment count
			$formatted_stats['cols']['post_words'] = __( 'Words', 'jnews-pay-writer' );
			if ( function_exists( 'jnews_view_counter_query' ) ) {
				$formatted_stats['cols']['post_visits'] = __( 'Visits', 'jnews-pay-writer' );
			}
			$formatted_stats['cols']['post_basic']      = __( 'Basic', 'jnews-pay-writer' );
			$formatted_stats['cols']['post_total_paid'] = __( 'Total Paid', 'jnews-pay-writer' );
			$formatted_stats['cols']['post_unpaid']     = __( 'Unpaid', 'jnews-pay-writer' );

			foreach ( $author_stats as $key => $post ) {
				if ( $key === 'total' ) {
					continue; // Skip author's total
				}
				$post_date = explode( ' ', $post->post_date );

				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_id']               = $post->ID;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_title']            = $post->post_title;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_publication_date'] = $post_date[0];

				// payment count
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_basic']         = isset( $post->jpwt_payment['standard'] ) ? $post->jpwt_payment['standard'] : 0;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_words']         = isset( $post->jpwt_count['word'] ) ? $post->jpwt_count['word'] : 0;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_visits']        = isset( $post->jpwt_count['view'] ) ? $post->jpwt_count['view'] : 0;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_total_payment'] = isset( $post->jpwt_payment['total'] ) ? $post->jpwt_payment['total'] : 0;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_total_paid']    = isset( $post->jpwt_payment['post_total_paid'] ) ? $post->jpwt_payment['post_total_paid'] : 0;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]['post_unpaid']        = isset( $post->jpwt_payment['post_unpaid'] ) ? $post->jpwt_payment['post_unpaid'] : 0;
				$formatted_stats['stats'][ $author_id ][ $post->ID ]                       = apply_filters( 'jpwt_post_stats_format_stats_after_each_default', $formatted_stats['stats'][ $author_id ][ $post->ID ], $author_id, $post );
			}
			$formatted_stats['cols'] = apply_filters( 'jpwt_post_stats_format_stats_after_cols_default', $formatted_stats['cols'] );
		} else {
			foreach ( $data as $author_id => $posts ) {

				$author_data    = get_userdata( $author_id );
				$paypal_account = get_user_option( 'paypal_account', $author_id );

				$formatted_stats['stats'][ $author_id ]['author_id']             = $author_id;
				$formatted_stats['stats'][ $author_id ]['author_name']           = $author_data->display_name;
				$formatted_stats['stats'][ $author_id ]['author_paypal_account'] = $paypal_account ? $paypal_account : '';
				$formatted_stats['stats'][ $author_id ]['author_total_post']     = (int) $posts['total']['jpwt_misc']['posts'];

				if ( count( $formatted_stats['stats'] ) == 0 ) {
					return new \WP_Error( 'jpwt_no_author_with_total_payment', __( 'No posts reach the threshold.', 'jnews-pay-writer' ), array() );
				}

				// COLUMNS
				$formatted_stats['cols']['author_name']           = __( 'Author Name', 'jnews-pay-writer' );
				$formatted_stats['cols']['author_paypal_account'] = __( 'Paypal Account', 'jnews-pay-writer' );
				$formatted_stats['cols']['author_total_post']     = __( 'Written posts', 'jnews-pay-writer' );

				// payment count
				$formatted_stats['stats'][ $author_id ]['author_total_basic']   = isset( $posts['total']['jpwt_payment']['standard'] ) ? $posts['total']['jpwt_payment']['standard'] : 0;
				$formatted_stats['stats'][ $author_id ]['author_total_payment'] = isset( $posts['total']['jpwt_payment']['total'] ) ? $posts['total']['jpwt_payment']['total'] : 0;
				$formatted_stats['stats'][ $author_id ]['author_total_paid']    = isset( $posts['total']['jpwt_payment']['author_total_paid'] ) ? $posts['total']['jpwt_payment']['author_total_paid'] : 0;
				$formatted_stats['stats'][ $author_id ]['author_unpaid']        = isset( $posts['total']['jpwt_payment']['author_unpaid'] ) ? $posts['total']['jpwt_payment']['author_unpaid'] : 0;
				$formatted_stats['cols']['author_total_basic']                  = __( 'Basic', 'jnews-pay-writer' );
				$formatted_stats['cols']['author_total_paid']                   = __( 'Total Paid', 'jnews-pay-writer' );
				$formatted_stats['cols']['author_unpaid']                       = __( 'Unpaid', 'jnews-pay-writer' );

				$formatted_stats['cols'] = apply_filters( 'jpwt_general_stats_format_stats_after_cols_default', $formatted_stats['cols'] );
			}
		}
		return apply_filters( 'jpwt_formatted_stats', $formatted_stats );
	}

	public static function uasort_stats_sort( $a, $b ) {
		$result = strnatcasecmp( $a[ $_REQUEST['orderby'] ], $b[ $_REQUEST['orderby'] ] ); // Determine sort order
		return ( $_REQUEST['order'] === 'asc' ) ? $result : -$result; // Send final sort direction to usort
	}

	public static function calculate_total_stats( $data ) {
		foreach ( $data as $author_id => $author_stats ) {
			$data[ $author_id ]['total']['jpwt_payment'] = array();
			$data[ $author_id ]['total']['jpwt_count']   = array();
			foreach ( $author_stats as $post_id => $single ) {
				// Written posts count
				if ( ! isset( $data[ $author_id ]['total']['jpwt_misc']['posts'] ) ) {
					$data[ $author_id ]['total']['jpwt_misc']['posts'] = 1;
				} else {
					$data[ $author_id ]['total']['jpwt_misc']['posts']++;
				}

				// Compute total countings
				foreach ( $single->jpwt_count as $what => $value ) {
					// Avoid notices of non isset index
					if ( ! isset( $data[ $author_id ]['total']['jpwt_count'][ $what ] ) ) {
						$data[ $author_id ]['total']['jpwt_count'][ $what ] = $single->jpwt_count[ $what ];
					} else {
						$data[ $author_id ]['total']['jpwt_count'][ $what ] += $single->jpwt_count[ $what ];
					}
				}

				// Compute total payment
				foreach ( $single->jpwt_payment as $what => $value ) {
					// Avoid notices of non isset index
					if ( ! isset( $data[ $author_id ]['total']['jpwt_payment'][ $what ] ) ) {
						$data[ $author_id ]['total']['jpwt_payment'][ $what ] = $value;
					} else {
						$data[ $author_id ]['total']['jpwt_payment'][ $what ] += $value;
					}
					$data[ $author_id ]['total']['jpwt_payment'][ $what ] = self::normalize_payment_currency( $data[ $author_id ]['total']['jpwt_payment'][ $what ] );
				}
				$data[ $author_id ] = apply_filters( 'jpwt_sort_stats_by_author_foreach_post', $data[ $author_id ], $single );
			}
		}
		if ( 'stats_general' === JNews_Pay_Writer()->settings['current_page'] ) {
			$args = array(
				'fields' => array( 'ID' ),
				'number' => -1,
			);

			$all_users = get_users( $args );

			foreach ( $all_users as $user ) {
				$ID = $user->ID;

				if ( isset( $data[ $ID ] ) ) {
					continue; // already in stats, don't override!
				}

				// Set up empty total record
				$data[ $ID ]['total'] = array(
					'jpwt_count'   => array(),
					'jpwt_payment' => array( 'total' => 0 ),
					'jpwt_misc'    => array( 'posts' => 0 ),
				);
			}
		}
		return apply_filters( 'jpwt_generated_raw_stats', $data );
	}

	public static function data2cash( $data, $author = null ) {
		$processed_data = array();

		foreach ( $data as $author_id => &$author_stats ) {
			foreach ( $author_stats as $single ) {
				// Use cached data if available
				$post_stats = Cache_Stats::get_post_stats( $single->ID );
				if ( false !== $post_stats ) {
					$processed_data[ $author_id ][ $single->ID ] = $post_stats;

				} else {
					do_action( 'jpwt_data2cash_single_before', $single );

					$post_counting = JNews_Pay_Writer()->payment->status->post_counting_status( $single );

					if ( count( $post_counting ) == 0 ) {
						continue;
					}
					$post_payment = JNews_Pay_Writer()->payment->status->post_payment_status( $single );

					if ( isset( $post_payment['total'] ) ) {
						$post_payment['total'] = self::normalize_payment_currency( $post_payment['total'] );
					}
					if ( isset( $post_payment['standard'] ) ) {
						$post_payment['standard'] = self::normalize_payment_currency( $post_payment['standard'] );
					}
					if ( isset( $post_payment['view'] ) ) {
						$post_payment['view'] = self::normalize_payment_currency( $post_payment['view'] );
					}
					if ( isset( $post_payment['word'] ) ) {
						$post_payment['word'] = self::normalize_payment_currency( $post_payment['word'] );
					}

					$single->jpwt_count   = $post_counting;
					$single->jpwt_payment = $post_payment;

					$processed_data[ $author_id ][ $single->ID ] = apply_filters( 'jpwt_post_counting_payment_data', $single, $author );

					// Cache post stats for one day
					Cache_Stats::set_post_stats( $single->ID, $processed_data[ $author_id ][ $single->ID ] );
				}
			}
		}
		do_action( 'jpwt_data2cash_processed_data', $processed_data );

		return $processed_data;
	}

	/**
	 * @param array $stats
	 *
	 * @return array
	 */
	public static function get_overall_stats( $stats ) {
		$overall_stats = array(
			'posts'         => 0,
			'total_payment' => 0,
			'payment'       => array(),
			'count'         => array(),
		);

		foreach ( $stats as $single ) {
			// Posts total count
			$overall_stats['posts'] += $single['total']['jpwt_misc']['posts'];

			// Total payment
			$overall_stats['total_payment'] += $single['total']['jpwt_payment']['total'];

			// Total counts
			if ( isset( $single['total'] ) and isset( $single['total']['jpwt_count'] ) ) {
				foreach ( $single['total']['jpwt_count'] as $key => $data ) {
					if ( ! isset( $overall_stats['count'][ $key ] ) ) {
						$overall_stats['count'][ $key ] = $data;
					} else {
						$overall_stats['count'][ $key ] += $data;
					}
				}
			}

			// Total payments
			if ( isset( $single['total'] ) and isset( $single['total']['jpwt_payment'] ) ) {
				foreach ( $single['total']['jpwt_payment'] as $key => $data ) {
					if ( $key == 'total' ) {
						continue; // skip total payment
					}

					if ( ! isset( $overall_stats['payment'][ $key ] ) ) {
						$overall_stats['payment'][ $key ] = $data;
					} else {
						$overall_stats['payment'][ $key ] += $data;
					}
				}
			}
		}

		return apply_filters( 'jpwt_overall_stats', $overall_stats, $stats );
	}

	/**
	 * Formats payments for output
	 *
	 * @param mixed $payment
	 *
	 * @return string
	 */
	public static function format_payment( $payment, $currency = null ) {
		if ( null === $currency ) {
			$currency = JNews_Pay_Writer()->options['payment']['payment_currency'];
		}
		$currency_locale = Helper::get_currency_locale( $currency );
		$suffix          = '';
		$prefix          = '';

		switch ( $currency_locale['currency_pos'] ) {
			case 'left_space':
				$prefix = $currency . ' ';
				break;
			case 'left':
				$prefix = $currency;
				break;
			case 'right_space':
				$suffix = ' ' . $currency;
				break;
			case 'right':
				$suffix = $currency;
				break;
		}
		$total_payment = $prefix . number_format( $payment, $currency_locale['num_decimals'], $currency_locale['decimal_sep'], $currency_locale['thousand_sep'] ) . $suffix;
		return apply_filters( 'jpwt_format_payment', $total_payment, $currency, $currency_locale );
	}

	/**
	 * We need to normalize output so paypal can receive the payment
	 *
	 * @param float $total
	 *
	 * @return float
	 */
	public static function normalize_payment_currency( $total ) {
		$currency        = JNews_Pay_Writer()->options['payment']['payment_currency'];
		$currency_locale = Helper::get_currency_locale( $currency );
		$total           = round( $total, $currency_locale['num_decimals'] );
		return $total;
	}
}
