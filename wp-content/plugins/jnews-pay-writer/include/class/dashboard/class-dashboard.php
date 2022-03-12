<?php
/**
 * JNews  Class
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */


namespace JNews\PAY_WRITER\Dashboard;

use JNews\PAY_WRITER\Dashboard\Template\List_Author;
use JNews\PAY_WRITER\Dashboard\Template\List_Post;
use JNews\PAY_WRITER\Dashboard\Template\List_History;
use JNews\PAY_WRITER\Dashboard\Template\List_History_Detail;
use JNews\PAY_WRITER\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Dashboard
 *
 * @package JNews\PAY_WRITER\Dashboard
 */
class Dashboard {
	/**
	 * @var Dashboard
	 */
	private static $instance;

	/**
	 * @var null|array
	 */
	private $author;

	/**
	 * @var boolean
	 */
	public $history;

	/**
	 * @var boolean
	 */
	public $history_detail;

	/**
	 * @var List_Author|List_Post
	 */
	public $list_table;

	/**
	 * @var \WP_Error|array
	 */
	public $stats;

	/**
	 * Dashboard constructor.
	 */
	private function __construct() {
		add_action( 'load-toplevel_page_jpwt-post-stats', array( $this, 'on_load_stats_page' ) );
		add_filter( 'set-screen-option', array( $this, 'handle_stats_pagination_values' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'pay_writer_dashboard' ) );

		// Clear post stats cache on post update
		add_action( 'post_updated', array( 'JNews\PAY_WRITER\Dashboard\Cache_Stats', 'clear_post_stats' ), 10, 1 );
		add_action( 'jpwt_list_table_nav_alignleft', array( $this, 'extra_tablenav' ) );
		add_action( 'jpwt_list_table_nav_after_bulkactions', array( $this, 'time_range_picker' ) );
	}

	/**
	 * @return Dashboard
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Load plugin assest
	 */
	public function load_assets() {
		wp_enqueue_style( 'vanillajs-datepicker', JNEWS_PAY_WRITER_URL . '/assets/css/vendor/vanillajs-datepicker/datepicker.min.css', array(), '1.1.4' );
		wp_enqueue_script( 'vanillajs-datepicker', JNEWS_PAY_WRITER_URL . '/assets/js/vendor/vanillajs-datepicker/datepicker-full.min.js', array(), '1.1.4', true );
	}

	public function on_load_stats_page() {
		$this->load_assets();
		$author_id = get_current_user_id();
		// If an author is given, put that in an array
		JNews_Pay_Writer()->settings['current_page'] = 'stats_detailed';
		$this->author                                = null;
		if ( isset( $_REQUEST['author'] ) && is_numeric( $_REQUEST['author'] ) && get_userdata( $_REQUEST['author'] ) && current_user_can( 'administrator', $author_id ) ) {
			$this->author = array( $_REQUEST['author'] );
		} else {
			if ( ! current_user_can( 'administrator', $author_id ) ) {
				$this->author = array( $author_id );
			} else {
				JNews_Pay_Writer()->settings['current_page'] = 'stats_general';
			}
		}
		if ( is_array( $this->author ) && isset( $_REQUEST['history'] ) ) {
			$this->history = true;
		}

		if ( is_array( $this->author ) && isset( $_REQUEST['history-detail'] ) ) {
			$this->history        = true;
			$this->history_detail = true;
		}

		// Store and maybe_redirect to ordered stats
		Generate_Stats::default_stats_order();

		Generate_Stats::get_default_stats_time_range();

		// Validate time range values (start and end), if set. They must be isset, numeric and positive. If something's wrong, start and end time are taken from the default publication time range
		if ( ( isset( $_REQUEST['tstart'] ) && ( ! is_numeric( $_REQUEST['tstart'] ) || $_REQUEST['tstart'] < 0 ) )
		|| ( isset( $_REQUEST['tend'] ) && ( ! is_numeric( $_REQUEST['tend'] ) || $_REQUEST['tend'] < 0 ) ) ) {
			$_REQUEST['tstart'] = strtotime( $_REQUEST['tstart'] . ' 00:00:00' );
			$_REQUEST['tend']   = strtotime( $_REQUEST['tend'] . ' 23:59:59' );
		} elseif ( ! isset( $_REQUEST['tstart'] ) || ! isset( $_REQUEST['tend'] ) ) {
			$_REQUEST['tstart'] = JNews_Pay_Writer()->settings['stats_tstart'];
			$_REQUEST['tend']   = JNews_Pay_Writer()->settings['stats_tend'];
		}

		// Assign to global var
		JNews_Pay_Writer()->settings['stats_tstart']        = sanitize_text_field( $_REQUEST['tstart'] );
		JNews_Pay_Writer()->settings['stats_tend']          = sanitize_text_field( $_REQUEST['tend'] );
		JNews_Pay_Writer()->settings['time_end_now']        = date( 'Y-m-d', strtotime( '23:59:59' ) );
		JNews_Pay_Writer()->settings['time_start_end_week'] = get_weekstartend( current_time( 'mysql' ) );

		// generate page permalink
		JNews_Pay_Writer()->settings['page_permalink'] = JNews_Pay_Writer()->settings['stats_menu_link'] . '&amp;tstart=' . JNews_Pay_Writer()->settings['stats_tstart'] . '&amp;tend=' . JNews_Pay_Writer()->settings['stats_tend'];

		if ( isset( $_REQUEST['jpwt-time-range'] ) ) {
			JNews_Pay_Writer()->settings['page_permalink'] .= '&amp;jpwt-time-range=' . $_REQUEST['jpwt-time-range'];
		}
		if ( isset( $_REQUEST['orderby'] ) ) {
			JNews_Pay_Writer()->settings['page_permalink'] .= '&amp;orderby=' . $_REQUEST['orderby'];
		}
		if ( isset( $_REQUEST['order'] ) ) {
			JNews_Pay_Writer()->settings['page_permalink'] .= '&amp;order=' . $_REQUEST['order'];
		}
		if ( isset( $_REQUEST['author'] ) ) {
			JNews_Pay_Writer()->settings['page_permalink'] .= '&amp;author=' . $_REQUEST['author'];
		}
		if ( isset( $_REQUEST['history'] ) ) {
			JNews_Pay_Writer()->settings['page_permalink'] .= '&amp;history';
		}
		if ( isset( $_REQUEST['history-detail'] ) ) {
			JNews_Pay_Writer()->settings['page_permalink'] .= '&amp;history-detail';
		}

		JNews_Pay_Writer()->settings['page_permalink'] = admin_url( JNews_Pay_Writer()->settings['page_permalink'] );

		if ( is_array( $this->author ) && ! empty( $this->author ) ) {
			$author_data = get_userdata( $this->author[0] );
			if ( $author_data ) {
				$this->author_name = $author_data->display_name;
				if ( ! $this->history ) {
					JNews_Pay_Writer()->settings['page_permalink'] = Generate_Stats::get_the_author_link( $this->author[0] );
					$this->stats                                   = Generate_Stats::produce_stats( JNews_Pay_Writer()->settings['stats_tstart'], JNews_Pay_Writer()->settings['stats_tend'], $this->author, true );

					if ( ! is_wp_error( $this->stats ) ) {
						$option = 'per_page';
						$args   = array(
							'label'   => 'Posts',
							'default' => 50,
							'option'  => 'jpwt_posts_per_page',
						);
						add_screen_option( $option, $args );

						$this->list_table = new List_Post( $this->stats );
					}
				} else {
					$option = 'per_page';
					$args   = array(
						'label'   => 'History',
						'default' => 50,
						'option'  => 'jpwt_history_per_page',
					);
					add_screen_option( $option, $args );
					if ( ! $this->history_detail ) {
						$this->stats      = Generate_Stats::produce_history_stats( JNews_Pay_Writer()->settings['stats_tstart'], JNews_Pay_Writer()->settings['stats_tend'], $this->author );
						$this->list_table = new List_History( $this->stats );
					} else {
						$this->stats      = Generate_Stats::produce_history_stats( JNews_Pay_Writer()->settings['stats_tstart'], JNews_Pay_Writer()->settings['stats_tend'], $this->author, true );
						$this->list_table = new List_History_Detail( $this->stats );
					}
				}
			}
		} else {
			$this->stats = Generate_Stats::produce_stats( JNews_Pay_Writer()->settings['stats_tstart'], JNews_Pay_Writer()->settings['stats_tend'], null, true );

			if ( ! is_wp_error( $this->stats ) ) {
				$option = 'per_page';
				$args   = array(
					'label'   => 'Authors',
					'default' => 50,
					'option'  => 'jpwt_authors_per_page',
				);
				add_screen_option( $option, $args );

				$this->list_table = new List_Author( $this->stats );
			}
		}
	}

	public function extra_tablenav() {
		if ( JNews_Pay_Writer()->options['display']['enable_post_stats_caching'] ) {
			?>
			<span style="font-style: italic;"><?php esc_html_e( 'Displayed data is cached. You may have to wait 24 hours for updated data.', 'jnews-pay-writer' ); ?></span>
			<?php
		}
	}

	public function time_range_picker() {
		?>
			<div id="jpwt_stats_header_datepicker">
				<?php
					$time_range_options = array(
						'this_month' => __( 'This Month', 'jnews-pay-writer' ),
						'last_month' => __( 'Last Month', 'jnews-pay-writer' ),
						'this_year'  => __( 'This Year', 'jnews-pay-writer' ),
						'this_week'  => __( 'This Week', 'jnews-pay-writer' ),
						'all_time'   => __( 'All Time', 'jnews-pay-writer' ),
						'custom'     => __( 'Custom', 'jnews-pay-writer' ),
					);
					echo '<select name="jpwt-time-range" id="jpwt-time-range">';

					$_REQUEST = array_merge( $_GET, $_POST );
					foreach ( $time_range_options as $key => $value ) {
						if ( isset( $_REQUEST['jpwt-time-range'] ) ) {
							$selected = selected( $key, sanitize_text_field( $_REQUEST['jpwt-time-range'] ), false );
						} else {
							$selected = selected( $key, 'this_month', false );
						}
						?>
							<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
						<?php
					}
					echo '</select>';
					echo '<div id="jpwt-time-range-custom" style="display: none;">';
					echo sprintf( __( '%1$s %3$sTo%4$s %2$s', 'jnews-pay-writer' ), '<input type="text" name="tstart" id="jnews_pay_writer_time_start" class="mydatepicker" value="' . date( 'Y-m-d', JNews_Pay_Writer()->settings['stats_tstart'] ) . '" accesskey="' . JNews_Pay_Writer()->settings['stats_tstart'] . '" size="10" />', '<input type="text" name="tend" id="jnews_pay_writer_time_end" class="mydatepicker" value="' . date( 'Y-m-d', JNews_Pay_Writer()->settings['stats_tend'] ) . '" accesskey="' . JNews_Pay_Writer()->settings['stats_tend'] . '" size="10" />', '<label for="mydatepicker">', '</label>' );
					echo '</div>';
					?>
				<input type="submit" class="button-secondary" name="jnews_pay_writer_submit" value="<?php echo esc_html__( 'Apply', 'jnews-pay-writer' ); ?>">
			</div>
			<a href="<?php echo esc_url_raw( JNews_Pay_Writer()->settings['page_permalink'] ); ?>" title="<?php _e( 'Get current view permalink', 'jnews-pay-writer' ); ?>"><?php _e( 'Get current view permalink', 'jnews-pay-writer' ); ?></a>
		<?php
	}

	public function show_stats() {
		if ( is_wp_error( $this->stats ) ) {
			echo '<form id="jpwt-list-author" class="empty" method="post">';
				$this->time_range_picker();
			echo '</form>';
			echo '<hr class="jpwt_hr_divider">';
			echo esc_html( $this->stats->get_error_message() );
			return;
		}
		echo '<form id="jpwt-list-author" method="post">';
		if ( isset( $this->list_table ) && ! is_wp_error( $this->list_table ) ) {
				$this->list_table->prepare_items();
				$this->list_table->display();
				Helper::get_template_part( 'include/class/dashboard/template/dashboard-stats', 'overall' );
		}
		echo '</form>';
		add_action( 'shutdown', array( $this, 'show_payout' ) );
	}

	public function show_payout() {
		if ( current_user_can( 'administrator', get_current_user_id() ) ) {
			echo '<!-- Begin JNews - Pay Writer output -->' . "\n\n";
			Helper::get_template_part( 'include/class/dashboard/template/dashboard', 'payout' );
			echo '<!-- End JNews - Pay Writer output -->' . "\n\n";
		}
	}

	/**
	 * Show result of payemnt history
	 *
	 * @access  public
	 */
	public function show_history() {
		if ( empty( $this->stats ) ) {
			echo '<form id="jpwt-list-history" class="empty" method="post">';
				$this->time_range_picker();
			echo '</form>';
			echo '<hr class="jpwt_hr_divider">';
		}
		echo '<form id="jpwt-list-history" method="post">';
				$this->list_table->prepare_items();
				$this->list_table->display();
		echo '</form>';
	}

	/**
	 * Saves pagination value in Screen Options.
	 *
	 * @access  public
	 */
	public function handle_stats_pagination_values( $status, $option, $value ) {
		return $value;
	}

	public function pay_writer_dashboard() {
		$menus    = array(
			'pay-writer' => array(
				'page_title' => esc_html__( 'Pay Writer', 'jnews-pay-writer' ),
				'menu_title' => esc_html__( 'Pay Writer', 'jnews-pay-writer' ),
				'capability' => 'read',
				'menu_slug'  => 'jpwt-post-stats',
				'callback'   => array( $this, 'manage_payment_dashboard' ),
				'icon_url'   => '',
				'position'   => null,
			),
		);
		$submenus = array(
			'stats' => array(
				'parent_slug' => $menus['pay-writer']['menu_slug'],
				'page_title'  => esc_html__( 'Stats', 'jnews-pay-writer' ),
				'menu_title'  => esc_html__( 'Stats', 'jnews-pay-writer' ),
				'capability'  => 'read',
				'menu_slug'   => 'jpwt-post-stats',
				'callback'    => array( $this, 'manage_payment_dashboard' ),
				'position'    => null,
			),
		);

		if ( current_user_can( 'administrator', get_current_user_id() ) && JNews_Pay_Writer()->is_jnews ) {
			$customizer_query = array(
				'autofocus[section]' => 'jnews_pay_writer_section',
			);
			$section_link     = add_query_arg( $customizer_query, admin_url( 'customize.php' ) );

			$submenus['settings'] = array(
				'parent_slug' => $menus['pay-writer']['menu_slug'],
				'page_title'  => esc_html__( 'Customizer Setting', 'jnews-pay-writer' ),
				'menu_title'  => esc_html__( 'Customizer Setting', 'jnews-pay-writer' ),
				'capability'  => 'manage_options',
				'menu_slug'   => $section_link,
				'callback'    => '',
				'position'    => null,
			);
		}
		JNews_Pay_Writer()->settings['stats_menu_link'] = 'admin.php?page=' . $menus['pay-writer']['menu_slug'];

		foreach ( $menus as $key => $menu_args ) {
			add_menu_page( $menu_args['page_title'], $menu_args['menu_title'], $menu_args['capability'], $menu_args['menu_slug'], $menu_args['callback'] );
		}
		foreach ( $submenus as $key => $submenu_args ) {
			add_submenu_page( $submenu_args['parent_slug'], $submenu_args['page_title'], $submenu_args['menu_title'], $submenu_args['capability'], $submenu_args['menu_slug'], $submenu_args['callback'] );
		}

	}

	public function manage_payment_dashboard() {
		if ( JNews_Pay_Writer()->is_jnews ) {
			Helper::get_template_part( 'include/class/dashboard/template/dashboard-stats' );
		} else {
			?>
			<div class="wrap jpwt_stats">
				<h2><?php echo esc_html( apply_filters( 'jpwt_admin_menu_name', 'Pay Writer' ) ) . ' - ' . esc_html__( 'Please activate JNews Theme', 'jnews-pay-writer' ); ?></h2>
			</div>
			<?php
		}
	}
}
