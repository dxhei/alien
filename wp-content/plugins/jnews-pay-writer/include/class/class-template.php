<?php
/**
 * @author Jegtheme
 */

namespace JNews\PAY_WRITER;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Template
 *
 * @package JNews\PAY_WRITER
 */
class Template {

	/**
	 * Endpoint
	 *
	 * @var array
	 */
	private $endpoint;

	/**
	 * Template Construct.
	 */
	public function __construct() {
		$this->setup_endpoint();
		add_action( 'template_include', array( $this, 'load_assets' ) );
		add_action( 'jnews_ajax_earning_chart', array( $this, 'earning_chart' ) );
		add_action( 'jnews_ajax_earning_stats_template', array( $this, 'earning_stats_template' ) );
		add_action( 'jnews_account_right_content', array( $this, 'get_right_content' ) );
		add_filter( 'jnews_account_page_endpoint', array( $this, 'account_page_endpoint' ) );
	}

	/**
	 * Earning stats template to list post
	 *
	 * @return [type]
	 */
	public function earning_stats_template() {
		$response  = array(
			'status' => 'error',
		);
		$nonce     = isset( $_POST['data'] ) && isset( $_POST['data']['nonce'] ) ? sanitize_text_field( $_POST['data']['nonce'] ) : null;
		$author_id = get_current_user_id();
		if ( wp_verify_nonce( $nonce, 'jnews_pay_writer_nonce' ) && $author_id ) {
			$template     = isset( $_POST['data'] ) && isset( $_POST['data']['template'] ) ? sanitize_text_field( $_POST['data']['template'] ) : 'earning';
			$valid_ranges = array( 'today', 'daily', 'last24hours', 'weekly', 'last7days', 'monthly', 'last30days', 'all', 'custom' );
			$time_units   = array( 'MINUTE', 'HOUR', 'DAY' );

			JNews_Pay_Writer()->options['config']['range']         = ( isset( $_POST['data'] ) && isset( $_POST['data']['range'] ) && in_array( $_POST['data']['range'], $valid_ranges ) ) ? sanitize_text_field( $_POST['data']['range'] ) : 'last7days';
			JNews_Pay_Writer()->options['config']['time_quantity'] = ( isset( $_POST['data'] ) && isset( $_POST['data']['time_quantity'] ) && filter_var( $_POST['data']['time_quantity'], FILTER_VALIDATE_INT ) ) ? sanitize_text_field( $_POST['data']['time_quantity'] ) : 24;
			JNews_Pay_Writer()->options['config']['time_unit']     = ( isset( $_POST['data'] ) && isset( $_POST['data']['time_unit'] ) && in_array( strtoupper( $_POST['data']['time_unit'] ), $time_units ) ) ? sanitize_text_field( $_POST['data']['time_unit'] ) : 'hour';

			$response['status'] = 'ok';
			$args               = $this->get_post_data( JNews_Pay_Writer()->options['config']['range'], JNews_Pay_Writer()->options['config']['time_unit'], JNews_Pay_Writer()->options['config']['time_quantity'], $author_id );
			$templates          = array(
				'template/post-stats-' . $template . '.php',
			);
			ob_start();
			jeg_locate_template( Helper::get_template_path( $templates, false, false ), true, $args );
			$result           = ob_get_clean();
			$response['data'] = $result;
		}
		wp_send_json( $response );
	}

	/**
	 * @return mixed
	 */
	public function earning_chart() {
		$response  = array(
			'status' => 'error',
		);
		$nonce     = isset( $_POST['data'] ) && isset( $_POST['data']['nonce'] ) ? sanitize_text_field( $_POST['data']['nonce'] ) : null;
		$author_id = get_current_user_id();

		if ( wp_verify_nonce( $nonce, 'jnews_pay_writer_nonce' ) && $author_id ) {

			$valid_ranges = array( 'today', 'daily', 'last24hours', 'weekly', 'last7days', 'monthly', 'last30days', 'all', 'custom' );
			$time_units   = array( 'MINUTE', 'HOUR', 'DAY' );

			JNews_Pay_Writer()->options['config']['range']         = ( isset( $_POST['data'] ) && isset( $_POST['data']['range'] ) && in_array( $_POST['data']['range'], $valid_ranges ) ) ? sanitize_text_field( $_POST['data']['range'] ) : 'last7days';
			JNews_Pay_Writer()->options['config']['time_quantity'] = ( isset( $_POST['data'] ) && isset( $_POST['data']['time_quantity'] ) && filter_var( $_POST['data']['time_quantity'], FILTER_VALIDATE_INT ) ) ? sanitize_text_field( $_POST['data']['time_quantity'] ) : 24;
			JNews_Pay_Writer()->options['config']['time_unit']     = ( isset( $_POST['data'] ) && isset( $_POST['data']['time_unit'] ) && in_array( strtoupper( $_POST['data']['time_unit'] ), $time_units ) ) ? sanitize_text_field( $_POST['data']['time_unit'] ) : 'hour';

			Helper::update_global_option( 'config', JNews_Pay_Writer()->options['config'] );

			$response = array(
				'status' => 'ok',
				'data'   => json_decode(
					$this->get_chart_data( JNews_Pay_Writer()->options['config']['range'], JNews_Pay_Writer()->options['config']['time_unit'], JNews_Pay_Writer()->options['config']['time_quantity'], $author_id ),
					true
				),
			);
		}

		wp_send_json( $response );
	}

	/**
	 * Get post data
	 *
	 * @param string $range
	 * @param string $time_unit
	 * @param int    $time_quantity
	 * @param int    $author
	 *
	 * @return string
	 */
	private function get_post_data( $range = 'last7days', $time_unit = 'HOUR', $time_quantity = 24, $author = false ) {
		$response = array(
			'status' => 'error',
		);
		if ( function_exists( 'JNews_View_Counter' ) ) {
			$dates      = JNews_View_Counter()->frontend->get_dates( $range, $time_unit, $time_quantity );
			$start_date = $dates[0];
			$end_date   = $dates[ count( $dates ) - 1 ];
			$stats      = $this->get_range_post( strtotime( $start_date ), strtotime( $end_date ), array( $author ) );
			if ( $stats ) {
				foreach ( $stats['raw_stats'] as $userid => $posts ) {
					if ( $userid === $author ) {
						$response['status'] = 'ok';
						$response['data']   = array(
							'result' => array(),
						);
						foreach ( $posts as $post_id => $post ) {
							if ( 'total' !== $post_id ) {
								$response['data']['result'][] = $post;
							}
						}
					}
				}
			}
		}
		return $response;
	}

	/**
	 * Get post in range
	 *
	 * @param int   $start_date
	 * @param int   $end_date
	 * @param array $author
	 *
	 * @return boolean|array
	 */
	private function get_range_post( $start_date, $end_date, $author ) {
		JNews_Pay_Writer()->payment                  = \JNews\PAY_WRITER\Util\Payment::instance();
		JNews_Pay_Writer()->settings['current_page'] = 'stats_general';
		$stats                                       = \JNews\PAY_WRITER\Dashboard\Generate_Stats::produce_stats( $start_date, $end_date, $author, true );
		if ( ! is_wp_error( $stats ) ) {
			return $stats;
		}
		return false;
	}

	/**
	 * Returns an array of dates with views/comments count.
	 *
	 * @param   string $start_date
	 * @param   string $end_date
	 * @param   string $item
	 * @param   int    $author
	 *
	 * @return [type]
	 */
	public function get_range_item_count( $start_date, $end_date, $item = 'views', $author = false ) {
		$response = array();
		if ( function_exists( 'JNews_View_Counter' ) ) {
			global $wpdb;

			$args = array_map( 'trim', explode( ',', JNews_View_Counter()->options['config']['post_type'] ) );
			if ( empty( $args ) ) {
				$args = array( 'post' );
			}

			$post_type_placeholders = array_fill( 0, count( $args ), '%s' );
			$post_ids               = array();
			if ( $author ) {
				$stats = $this->get_range_post( strtotime( $start_date ), strtotime( $end_date ), array( $author ) );
				if ( $stats ) {
					foreach ( $stats['raw_stats'] as $userid => $posts ) {
						foreach ( $posts as $post_id => $post ) {
							if ( 'total' !== $post_id ) {
								$post_ids[] = $post_id;
								$args[]     = $post_id;
							}
						}
					}
				}
			}
			$post_ids_placeholders = array_fill( 0, count( $post_ids ), '%s' );
			$freshness             = false;
			if ( $freshness ) {
				$args[] = $start_date;
			}
			if ( $author ) {
				$args[] = $author;
			}

			// Append dates to arguments list
			array_unshift( $args, $start_date, $end_date );

			if ( 'views' === $item && ! empty( $post_ids_placeholders ) ) {
				$query    = $wpdb->prepare(
					"SELECT `v`.`view_date`, `v`.`postid`, SUM(`v`.`pageviews`) AS `pageviews`, `p`.`post_content`
					FROM `{$wpdb->prefix}popularpostssummary` v INNER JOIN `{$wpdb->posts}` p ON `v`.`postid` = `p`.`ID`
					WHERE (`v`.`view_datetime` BETWEEN %s AND %s) AND `p`.`post_type` IN (" . implode( ', ', $post_type_placeholders ) . ') AND `p`.`ID` IN (' . implode( ', ', $post_ids_placeholders ) . ") AND `p`.`post_status` = 'publish' AND `p`.`post_password` = '' 
					" . ( $freshness ? ' AND `p`.`post_date` >= %s' : '' ) . ( $author ? ' AND `p`.`post_author` = %s' : '' ) . '
					GROUP BY `v`.`view_date`, `v`.`postid` ORDER BY `v`.`view_date` DESC;',
					$args
				);
				$response = $wpdb->get_results( $query, ARRAY_A );
			}

			$earnings = array();
			if ( is_array( $response ) && ! empty( $response ) ) {
				foreach ( $response as $key => $summary ) {
					if ( isset( $summary['postid'] ) ) {
						$post_payment = JNews_Pay_Writer()->payment->status->post_payment_status( array(), $summary['pageviews'], $summary['post_content'] );
						if ( isset( $post_payment['total'] ) ) {
							$post_payment['total'] = \JNews\PAY_WRITER\Dashboard\Generate_Stats::normalize_payment_currency( $post_payment['total'] );
						}
						if ( isset( $earnings[ $summary['view_date'] ] ) ) {
							$earnings[ $summary['view_date'] ]->earnings += $post_payment['total'];
						} else {
							$earnings[ $summary['view_date'] ] = (object) array(
								'earnings' => $post_payment['total'],
							);
						}
					}
				}
				$response = $earnings;
			} else {
				$response = array();
			}
		}
		return $response;
	}

	/**
	 * Fetches chart data.
	 *
	 * @param string $range
	 * @param string $time_unit
	 * @param int    $time_quantity
	 * @param int    $author
	 *
	 * @return string
	 */
	public function get_chart_data( $range = 'last7days', $time_unit = 'HOUR', $time_quantity = 24, $author = false ) {
		$response = array();
		if ( function_exists( 'JNews_View_Counter' ) ) {
			$dates         = JNews_View_Counter()->frontend->get_dates( $range, $time_unit, $time_quantity );
			$start_date    = $dates[0];
			$end_date      = $dates[ count( $dates ) - 1 ];
			$date_range    = \JNEWS_VIEW_COUNTER\Helper::get_date_range( $start_date, $end_date, 'Y-m-d H:i:s' );
			$earnings_data = $this->get_range_item_count( $start_date, $end_date, 'views', $author );
			$earnings      = array();

			if ( 'today' != $range ) {
				foreach ( $date_range as $date ) {
					$key        = date( 'Y-m-d', strtotime( $date ) );
					$earnings[] = ( ! isset( $earnings_data[ $key ] ) ) ? 0 : $earnings_data[ $key ]->earnings;
				}
			} else {
				$key        = date( 'Y-m-d', strtotime( $dates[0] ) );
				$earnings[] = ( ! isset( $earnings_data[ $key ] ) ) ? 0 : $earnings_data[ $key ]->earnings;
			}

			if ( $start_date != $end_date ) {
				$label_date_range = date_i18n( 'M, D d', strtotime( $start_date ) ) . ' &mdash; ' . date_i18n( 'M, D d', strtotime( $end_date ) );
			} else {
				$label_date_range = date_i18n( 'M, D d', strtotime( $start_date ) );
			}

			$total_views = array_sum( $earnings );

			$label_summary = sprintf( _n( '%s earning', '%s earnings', $total_views, 'jnews-pay-writer' ), '<strong>' . number_format_i18n( $total_views ) . '</strong>' );

			// Format labels
			if ( 'today' != $range ) {
				$date_range = array_map(
					function( $d ) {
						return date_i18n( 'Y-m-d', strtotime( $d ) );
					},
					$date_range
				);
			} else {
				$date_range = array( date_i18n( 'Y-m-d', strtotime( $date_range[0] ) ) );
				$earnings   = array( array_sum( $earnings ) );
			}
			$show_views_label = false;
			$earnings         = array_map(
				function( $v, $k ) use ( $date_range, &$show_views_label ) {
					if ( ! $show_views_label && $v > floatval( 0 ) ) {
						$show_views_label = true;
					}
					$v = array(
						'x' => date_i18n( 'Y-m-d', strtotime( $date_range[ $k ] ) ),
						'y' => $v,
					);
					return $v;
				},
				$earnings,
				array_keys( $earnings )
			);

			$response = array(
				'backgroundColor' => '#FAFAFA',
				'color'           => get_theme_mod( 'jnews_accent_color', '#f70d28' ),
				'totals'          => array(
					'label_summary'    => $label_summary,
					'label_date_range' => $label_date_range,
				),
				'labels'          => $date_range,
				'datasets'        => array(
					array(
						'label' => '(' . JNews_Pay_Writer()->options['payment']['payment_currency'] . ') ' . esc_html__( 'Estimated earnings', 'jnews-pay-writer' ),
						'data'  => $earnings,
					),
				),
				'x'               => array(
					'type' => 'time',
					'time' => array(
						'unit'           => 'day',
						'displayFormats' => array(
							'day' => 'DD[\n]MMM[\n]YYYY',
						),
					),
				),
				'y'               => array(
					'display' => false,
				),
			);
			if ( $show_views_label || apply_filters( 'jnews_pay_writer_show_views_label_chart', false ) ) {
				$response['y']['display'] = true;
			}
			if ( count( $date_range ) > 23 ) {
				$response['x']['time']['unit']                   = 'week';
				$response['x']['time']['displayFormats']['week'] = 'DD[\n]MMM[\n]YYYY';
				$divide_week                                     = array_chunk( $date_range, 7 );
				if ( count( $divide_week ) > 12 ) {
					$response['x']['time']['unit']                    = 'month';
					$response['x']['time']['displayFormats']['month'] = 'DD[\n]MMM[\n]YYYY';
					$divide_month                                     = array_chunk( $date_range, 30 );
					if ( count( $divide_month ) > 12 ) {
						$response['x']['time']['unit']                      = 'quarter';
						$response['x']['time']['displayFormats']['quarter'] = 'DD[\n]MMM[\n]YYYY';
						$divide_quarter                                     = array_chunk( $date_range, 90 );
						if ( count( $divide_quarter ) > 12 ) {
							$response['x']['time']['unit']                   = 'year';
							$response['x']['time']['displayFormats']['year'] = 'DD[\n]MMM[\n]YYYY';
						}
					}
				}
			}
		}

		return wp_json_encode( $response );
	}

	/**
	 * Setup endpoint
	 */
	private function setup_endpoint() {
		$endpoint = array(
			'post_earning_stats' => array(
				'title' => esc_html__( 'Post Earning Stats', 'jnews-pay-writer' ),
				'label' => 'post_earning_stats',
				'slug'  => 'post-earning-stats',
			),
		);

		$this->endpoint = apply_filters( 'jnews_pay_writer_endpoint', $endpoint );
	}

	/**
	 * Get the right content
	 */
	public function get_right_content() {
		if ( function_exists( 'JNews_View_Counter' ) ) {
			global $wp;

			if ( is_user_logged_in() ) {
				if ( isset( $wp->query_vars['account'] ) && ! empty( $wp->query_vars['account'] ) ) {
					foreach ( $this->endpoint as $key => $value ) {
						$query_vars = explode( '/', $wp->query_vars['account'] );

						if ( $query_vars[0] === $value['slug'] ) {
							$this->render_template();
						}
					}
				}
			}
		}
	}

	/**
	 * Add Bookmark Endpoint
	 *
	 * @param object $endpoint Global Endpoint.
	 * @return object
	 */
	public function account_page_endpoint( $endpoint ) {

		if ( function_exists( 'JNews_View_Counter' ) ) {
			if ( isset( $this->endpoint ) && ! empty( $this->endpoint ) ) {
				if ( is_array( $endpoint ) && ! empty( $endpoint ) && isset( $endpoint['change_password'] ) ) {
					$position     = array_search( 'change_password', array_keys( $endpoint ) ) + 1;
					$first_slice  = array_slice( $endpoint, 0, $position, true );
					$second_slice = array_slice( $endpoint, $position, count( $endpoint ) - 1, true );
					$endpoint     = $first_slice + $this->endpoint + $second_slice;
				} else {
					$endpoint = array_merge( $endpoint, $this->endpoint );
				}
			}
		}

		return $endpoint;
	}

	/**
	 * Load plugin assest
	 *
	 * @param  string $template
	 * @return string
	 */
	public function load_assets( $template ) {
		if ( function_exists( 'JNews_View_Counter' ) ) {
			global $wp;
			if ( is_user_logged_in() && ! is_admin() ) {
				if ( isset( $wp->query_vars['account'] ) && ! empty( $wp->query_vars['account'] ) ) {
					foreach ( $this->endpoint as $key => $value ) {
						$query_vars = explode( '/', $wp->query_vars['account'] );

						if ( $query_vars[0] === $value['slug'] ) {
							add_action( 'wp_enqueue_scripts', array( $this, 'load_script' ) );
							add_action( 'wp_enqueue_scripts', array( $this, 'load_style' ), 98 );
						}
					}
				}
			}
			return $template;
		}
	}

	public function load_script() {
		// vendor
		wp_register_script( 'chartjs-moment', JNEWS_PAY_WRITER_URL . '/assets/js/vendor/chartjs/chartjs-adapter-moment.min.js', array( 'chartjs', 'moment' ), '1.0.0', true );
		wp_register_script( 'chartjs', JNEWS_PAY_WRITER_URL . '/assets/js/vendor/chartjs/chart.min.js', array(), '3.4.1', true );
		wp_register_script( 'vanillajs-datepicker', JNEWS_PAY_WRITER_URL . '/assets/js/vendor/vanillajs-datepicker/datepicker-full.min.js', array(), '1.1.4', true );

		wp_register_script( 'jnews-pay-writer-chart', JNEWS_PAY_WRITER_URL . '/assets/js/stats/chart.js', array( 'chartjs-moment', 'vanillajs-datepicker' ), JNEWS_PAY_WRITER_VERSION, true );
		wp_register_script( 'jnews-pay-writer-stats', JNEWS_PAY_WRITER_URL . '/assets/js/stats/stats.js', array( 'jnews-pay-writer-chart', 'vanillajs-datepicker' ), JNEWS_PAY_WRITER_VERSION, true );

		wp_enqueue_script( 'jnews-pay-writer-stats' );
		wp_localize_script( 'jnews-pay-writer-stats', 'jpwtoption', $this->localize_script() );
	}

	public function load_style() {
		// vendor
		wp_register_style( 'vanillajs-datepicker', JNEWS_PAY_WRITER_URL . '/assets/css/vendor/vanillajs-datepicker/datepicker.min.css', array(), '1.1.4' );

		wp_register_style( 'jnews-pay-writer-stats', JNEWS_PAY_WRITER_URL . '/assets/css/stats/stats.css', array(), JNEWS_PAY_WRITER_VERSION );
		wp_register_style( 'jnews-pay-writer-stats-darkmode', JNEWS_PAY_WRITER_URL . '/assets/css/stats/darkmode.css', array( 'jnews-pay-writer-stats' ), JNEWS_PAY_WRITER_VERSION );

		wp_enqueue_style( 'vanillajs-datepicker' );
		wp_enqueue_style( 'jnews-pay-writer-stats-darkmode' );
	}

	public function localize_script() {
		$option          = array();
		$option['nonce'] = wp_create_nonce( 'jnews_pay_writer_nonce' );
		return $option;
	}

	/**
	 * Render post stats
	 */
	private function render_template() {
		Helper::get_template_part( 'template/post-stats' );
	}
}
