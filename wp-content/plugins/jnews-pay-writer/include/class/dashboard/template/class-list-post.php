<?php
/**
 * List Post
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */

namespace JNews\PAY_WRITER\Dashboard\Template;

use JNews\PAY_WRITER\Dashboard\Generate_Stats;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class List_Post extends \WP_List_Table {

	var $data; // hold formatted stats
	var $raw_data; // holds raw stats
	var $columns; // holds formatted stats columns
	var $current_post_id; // holds current author id in table tr
	var $author_id; // holds author id (owner of these posts)

	public function __construct( $stats_data ) {
		global $status, $page;
		// Set parent defaults
		parent::__construct(
			array(
				'singular' => 'author',     // singular name of the listed records
				'plural'   => 'authors',    // plural name of the listed records
				'ajax'     => false,        // does this table support ajax?
			)
		);
		$this->author_id = key( $stats_data['formatted_stats']['stats'] );
		$this->data      = current( $stats_data['formatted_stats']['stats'] );
		$this->columns   = $stats_data['formatted_stats']['cols'];
		$this->raw_data  = $stats_data['raw_stats'];
	}

	/**
	 * Generates the table navigation above or below the table
	 *
	 * @see WP_List_Table::display_tablenav()
	 * @param string $which
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		if ( $this->has_items() ) {
			?>
			<div class="alignleft actions bulkactions">
				<?php
					$this->bulk_actions( $which );
					do_action( 'jpwt_list_table_nav_after_bulkactions' );
				?>
			</div>
			<br class="clear">
			<?php
		}
		?>
	<div class="tablenav <?php echo esc_attr( $which ); ?>">
		<div class="alignleft">
			<?php do_action( 'jpwt_list_table_nav_alignleft' ); ?>
		</div>
		<?php
		$this->extra_tablenav( $which );
		$this->pagination( $which );
		?>

		<br class="clear" />
	</div>
		<?php
	}
	/**
	 * Displays the table.
	 *
	 * @see WP_List_Table::display
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );
		?>
<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
	<thead>
	<tr>
		<?php $this->print_column_headers(); ?>
	</tr>
	</thead>

	<tbody id="the-list"
		<?php
		if ( $singular ) {
			echo " data-wp-lists='list:$singular'";
		}
		?>
		>
		<?php $this->display_rows_or_placeholder(); ?>
	</tbody>

	<tfoot>
	<tr>
		<?php $this->print_column_headers( false ); ?>
	</tr>
	</tfoot>

</table>
		<?php
	}

	public function column_default( $item, $column_name ) {
		$author = $this->author_id;

		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();
		if ( $column_name === $primary ) {
			if ( isset( $item['post_id'] ) ) {
				$this->current_post_id = $item['post_id'];
			}
		}

		$post        = $this->raw_data[ $author ][ $this->current_post_id ];
		$field_value = null;
		if ( isset( $item[ $column_name ] ) ) {
			$field_value = $item[ $column_name ];

			// Cases in which other stuff needs to be added to the output
			switch ( $column_name ) {
				// Attach link to post title: if user can edit posts, attach edit link (faster), if not post permalink (slower)
				case 'post_title':
					$post_link = get_edit_post_link( $post->ID );
					if ( $post_link == '' ) {
						$post_link = get_permalink( $post->ID );
					}

					$field_value = '<a href="' . $post_link . '" title="' . esc_html( $post->post_title ) . '">' . esc_html( $item[ $column_name ] ) . '</a>';
					break;
				case 'post_basic':
				case 'post_total_paid':
				case 'post_unpaid':
					$field_value = Generate_Stats::format_payment( $field_value );
					if ( 'post_unpaid' === $column_name ) {
						$field_value = '<abbr class="jpwt_payment_column">' . $field_value . '</abbr>';
					}
					break;
			}

			$field_value = apply_filters( 'jpwt_list_post_each_field_value', $field_value, $column_name, $post, $this->current_post_id );

		}

		return $field_value;
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/ $this->_args['singular'],  // Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/ $item['ID']                // The value of the checkbox should be the record's id
		);
	}


	public function get_columns() {
		$columns = array(
			// 'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
			'title' => 'Title',
		);

		$columns = $this->columns;

		return apply_filters( 'jpwt_list_post_cols_after_default', $columns );
	}

	public function get_sortable_columns() {
		$sortable_columns = array(
			'post_id'               => array( 'post_id', false ),
			'post_title'            => array( 'post_title', false ),
			'post_publication_date' => array( 'post_publication_date', true ), // true = already sorted
			'post_words'            => array( 'post_words', false ),
			'post_total_paid'       => array( 'post_total_paid', false ),
			'post_unpaid'           => array( 'post_unpaid', false ),
		);
		if ( function_exists( 'jnews_view_counter_query' ) ) {
			$sortable_columns['post_visits'] = array( 'post_visits', false );
		}

		return apply_filters( 'jpwt_list_post_sortable_columns', $sortable_columns );
	}


	public function get_bulk_actions() {
		return array();
		$actions = array(
			'delete' => 'Delete',
		);
		return $actions;
	}


	public function process_bulk_action() {

		// Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			wp_die( 'Items deleted (or they would be if we had items to delete)!' );
		}

	}


	public function prepare_items() {
		$per_page              = $this->get_items_per_page( 'jpwt_posts_per_page', 300 );
		$this->_column_headers = $this->get_column_info();
		$data                  = $this->data;
		$sortable_cols         = $this->get_sortable_columns();

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) && isset( $sortable_cols[ $_REQUEST['orderby'] ] ) && ( $_REQUEST['order'] == 'desc' or $_REQUEST['order'] == 'asc' ) && ! ( $_REQUEST['orderby'] == 'post_publication_date' && $_REQUEST['order'] == 'desc' ) ) {
			usort( $data, array( 'JNews\PAY_WRITER\Dashboard\Generate_Stats', 'uasort_stats_sort' ) );
		}

		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		$data         = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items  = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,                  // WE have to calculate the total number of items
				'per_page'    => $per_page,                     // WE have to determine how many items to show on a page
				'total_pages' => ceil( $total_items / $per_page ),   // WE have to calculate the total number of pages
			)
		);
	}

	/**
	 * Displays the pagination.
	 *
	 * @since 3.1.0
	 *
	 * @param string $which
	 */
	protected function pagination( $which ) {
		if ( empty( $this->_pagination_args ) ) {
			return;
		}

		$total_items     = $this->_pagination_args['total_items'];
		$total_pages     = $this->_pagination_args['total_pages'];
		$infinite_scroll = false;
		if ( isset( $this->_pagination_args['infinite_scroll'] ) ) {
			$infinite_scroll = $this->_pagination_args['infinite_scroll'];
		}

		if ( 'top' === $which && $total_pages > 1 ) {
			$this->screen->render_screen_reader_content( 'heading_pagination' );
		}

		$output = '<span class="displaying-num">' . sprintf(
			/* translators: %s: Number of items. */
			_n( '%s item', '%s items', $total_items ),
			number_format_i18n( $total_items )
		) . '</span>';

		$current              = $this->get_pagenum();
		$removable_query_args = wp_removable_query_args();

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		$current_url = remove_query_arg( $removable_query_args, $current_url );

		$page_links = array();

		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';

		$disable_first = false;
		$disable_last  = false;
		$disable_prev  = false;
		$disable_next  = false;

		if ( 1 == $current ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( 2 == $current ) {
			$disable_first = true;
		}
		if ( $total_pages == $current ) {
			$disable_last = true;
			$disable_next = true;
		}
		if ( $total_pages - 1 == $current ) {
			$disable_last = true;
		}

		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='first-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				__( 'First page' ),
				'&laquo;'
			);
		}

		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='prev-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
				__( 'Previous page' ),
				'&lsaquo;'
			);
		}

		if ( 'bottom' === $which ) {
			$html_current_page  = $current;
			$total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
		} else {
			$html_current_page = sprintf(
				"%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' disabled/><span class='tablenav-paging-text'>",
				'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
				$current,
				strlen( $total_pages )
			);
		}
		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
		$page_links[]     = $total_pages_before . sprintf(
			/* translators: 1: Current page, 2: Total pages. */
			_x( '%1$s of %2$s', 'paging' ),
			$html_current_page,
			$html_total_pages
		) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
				__( 'Next page' ),
				'&rsaquo;'
			);
		}

		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='last-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
				__( 'Last page' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class .= ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . implode( "\n", $page_links ) . '</span>';

		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

		echo $this->_pagination;
	}
}
