<?php
/**
 * JNews Bookmark Class
 *
 * @author Jegtheme
 * @package JNews_Bookmark
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class JNews Bookmark
 */
class JNews_Bookmark {

	/**
	 * JNews Bookmark $instance
	 *
	 * @var JNews_Bookmark
	 */
	private static $instance;

	/**
	 * Endpoint of Bookmark
	 *
	 * @var object
	 */
	private $endpoint;

	/**
	 * Bookmark Meta Name
	 *
	 * @var string
	 */
	private $meta_name = 'jnews_bookmark_article';

	/**
	 * Get Instance JNews_Bookmark
	 *
	 * @return Bookmark
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * JNews Bookmark constructor.
	 */
	private function __construct() {
		$this->setup_endpoint();
		$this->setup_hook();
	}

	/**
	 * Setup The Endpoint
	 */
	public function setup_endpoint() {
		$endpoint = array(
			'bookmark' => array(
				'title' => esc_html__( 'My Bookmark', 'jnews-bookmark' ),
				'label' => 'my_bookmark',
				'slug'  => 'my-bookmark',
			),
		);

		$this->endpoint = apply_filters( 'jnews_bookmark_endpoint', $endpoint );
	}

	/**
	 * Get Endpoint
	 */
	public function get_endpoint() {
		return $this->endpoint;
	}

	/**
	 * Setup WordPress Hook
	 */
	public function setup_hook() {
		/** Action */
		add_action( 'jnews_account_right_content', array( $this, 'get_right_content' ) );
		add_action( 'jnews_ajax_bookmark_handler', array( $this, 'jnews_ajax_bookmark_handler' ) );

		/** Filter */
		add_filter( 'jnews_account_page_endpoint', array( $this, 'account_page_endpoint' ) );
	}

	/**
	 * Load plugin assest
	 */
	public function load_assets() {
		wp_enqueue_style( 'jnews-bookmark', JNEWS_BOOKMARK_URL . '/assets/css/plugin.css', array(), JNEWS_BOOKMARK_VERSION, 'all' );
		wp_enqueue_script( 'jnews-bookmark', JNEWS_BOOKMARK_URL . '/assets/js/plugin.js', array( 'jquery' ), JNEWS_BOOKMARK_VERSION, true );
	}

	/**
	 * Ajax Handler
	 *
	 * @return void JSON Response
	 */
	public function jnews_ajax_bookmark_handler() {
		if ( empty( $_POST['jnews_nonce'] ) && ! check_ajax_referer( 'jnews_nonce', 'jnews_nonce', false ) && ! isset( $_POST['post_id'] ) ) {
			wp_send_json_error(
				array(
					'message' => jnews_return_translation( 'There are no articles to bookmark!', 'jnews-bookmark', 'failed_add_bookmark' ),
				)
			);
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error(
				array(
					'message' => jnews_return_translation( 'You must login to add bookmark!', 'jnews-bookmark', 'add_bookmark_must_login' ),
				)
			);
		}

		$user_id       = get_current_user_id();
		$user_bookmark = get_user_meta( $user_id, $this->meta_name, true );
		$user_bookmark = '' !== $user_bookmark ? $user_bookmark : array();
		$post_id       = sanitize_text_field( wp_unslash( $_POST['post_id'] ) );
		$action        = sanitize_text_field( wp_unslash( $_POST['type'] ) );

		switch ( $action ) {
			case 'add_bookmark':
				$message = jnews_return_translation( 'Bookmark Added!', 'jnews-bookmark', 'bookmark_added' );
				$status  = true;
				break;
			case 'remove_bookmark':
				$status  = false;
				$message = jnews_return_translation( 'Bookmark Removed!', 'jnews-bookmark', 'bookmark_removed' );
				break;
		}

		$user_bookmark[ $post_id ] = array( 'status' => $status );

		update_user_meta( $user_id, $this->meta_name, $user_bookmark );

		wp_send_json_success(
			array(
				'message'  => $message,
				'bookmark' => $status,
				'class'    => 'post-' . $post_id,
			)
		);
	}

	/**
	 * Add Bookmark Endpoint
	 *
	 * @param object $endpoint Global Endpoint.
	 * @return object
	 */
	public function account_page_endpoint( $endpoint ) {
		$show = $this->show_bookmark_button_meta();

		if ( $show && isset( $this->endpoint ) && ! empty( $this->endpoint ) ) {
			$endpoint = array_merge( $endpoint, $this->endpoint );
		}

		return $endpoint;
	}

	/**
	 * Render Empty Content
	 */
	public function empty_content() {
		$no_content = "<div class='jeg_empty_module'>" . jnews_return_translation( 'No Content Available', 'jnews-bookmark', 'no_content_available' ) . '</div>';
		echo apply_filters( 'jnews_module_no_content', $no_content );
	}

	/**
	 * Set Right Content in Account Page
	 */
	public function get_right_content() {
		global $wp;

		if ( is_user_logged_in() && $this->show_bookmark_button_meta() ) {
			if ( isset( $wp->query_vars['account'] ) && ! empty( $wp->query_vars['account'] ) ) {
				foreach ( $this->endpoint as $key => $value ) {
					$query_vars = explode( '/', $wp->query_vars['account'] );

					if ( $query_vars[0] === $value['slug'] ) {
						$paged = 1;

						if ( isset( $query_vars[2] ) ) {
							$paged = (int) $query_vars[2];
						}

						$this->render_template( $paged );
					}
				}
			}
		}
	}

	/**
	 * Render Template of Content
	 *
	 * @param number $paged Current Page Number.
	 */
	public function render_template( $paged ) {
		global $wp;
		$get_post_id = get_user_meta( get_current_user_id(), $this->meta_name, true );

		if ( ! empty( $get_post_id ) ) {
			$posts = array();

			foreach ( $get_post_id as $key => $value ) {
				if ( $value['status'] ) {
					$posts[] = $key;
				}
			}

			if ( ! empty( $posts ) ) {
				$order = get_query_var( 'order', 'desc' );

				$args = array(
					'post_type'           => 'post',
					'post__in'            => $posts,
					'orderby'             => 'date',
					'order'               => $order,
					'paged'               => $paged,
					'ignore_sticky_posts' => 1,
				);

				$posts = new \WP_Query( $args );

				if ( $posts->have_posts() ) {
					$this->load_assets();
					$posts_per_page = $posts->query_vars['posts_per_page'];
					$total_post     = $posts->found_posts;

					$fpost = $posts_per_page * ( $paged - 1 );
					$lpost = $posts_per_page * $paged;

					$fpost = ( $fpost <= 0 ) ? 1 : $fpost;
					$lpost = ( $lpost > $total_post ) ? $total_post : $lpost;
					?>
					<div class="jeg_post_list_meta row clearfix">
						<div class="jeg_post_list_filter col-md-6">
							<input type="hidden" name="current-page-url" value="<?php echo esc_url( home_url( $wp->request ) ); ?>">
							<select name="post-list-filter">
								<option <?php echo ( 'DESC' === $order ) ? esc_attr( 'selected' ) : ''; ?> value="desc"><?php echo jnews_return_translation( 'Sort by latest', 'jnews-bookmark', 'bookmark_short_latest' ); ?></option>
								<option <?php echo ( 'ASC' === $order ) ? esc_attr( 'selected' ) : ''; ?> value="asc"><?php echo jnews_return_translation( 'Sort by older', 'jnews-bookmark', 'bookmark_short_older' ); ?></option>
							</select>
						</div>
						<div class="jeg_post_list_count col-md-6">
							<span><?php echo sprintf( jnews_return_translation( 'Showing %1$s-%2$s of %3$s post results', 'jnews-bookmark', 'bookmark_pagination' ), $fpost, $lpost, $total_post ); ?></span>
						</div>
					</div>
					<?php
					while ( $posts->have_posts() ) :
						$posts->the_post();
						$post_id = get_the_ID();
						do_action( 'jnews_json_archive_push', $post_id );
						?>
						<article <?php post_class( 'jeg_post jeg_pl_sm added' ); ?>>
							<div class="jeg_thumb">
								<a href="<?php the_permalink(); ?>"><?php echo apply_filters( 'jnews_image_thumbnail', $post_id, 'jnews-120x86' ); ?></a>
							</div>
							<div class="jeg_postblock_content">
								<h3 class="jeg_post_title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<div class="jeg_post_meta">
									<?php if ( jnews_check_coauthor_plus() ) : ?>
										<div class="jeg_meta_author coauthor">
											<?php echo jnews_get_author_coauthor( $post_id, false, null, 1 ); ?>
										</div>
									<?php else : ?>
										<div class="jeg_meta_author"><?php jnews_print_translation( 'by', 'jnews-bookmark', 'by' ); ?> <?php jnews_the_author_link(); ?></div>
									<?php endif; ?>
									<div class="jeg_meta_date">
										<a href="<?php the_permalink(); ?>"><i class="fa fa-clock-o"></i> <?php echo esc_html( jeg_get_post_date() ); ?></a>
									</div>
									<div class="jeg_meta_bookmark">
										<a href="#" data-id="<?php echo $post_id; ?>" data-added="true" data-message><i class="fa fa-bookmark"></i></a>
									</div>
								</div>
							</div>
						</article>
						<?php
					endwhile;

					/* pagination */
					$args_pagination = array(
						'pagination_mode'     => 'nav_3',
						'pagination_align'    => 'center',
						'pagination_navtext'  => false,
						'pagination_pageinfo' => false,
						'current'             => $paged,
						'total'               => $posts->max_num_pages,
					);

					$pagination = apply_filters( 'jnews_bookmark_pagination_args', $args_pagination );

					echo jnews_paging_navigation( $pagination );

					wp_reset_postdata();
					return;
				}
			}
		}

		$this->empty_content();
	}

	public function show_bookmark_button_meta( $post_id = null ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$show = jnews_get_bookmark_option( 'jnews_single_bookmark', false );

		return apply_filters( 'jnews_show_bookmark_button', $show, $post_id );
	}

	/**
	 * Generate Bookmark element
	 *
	 * @param int $post_id ID Post.
	 */
	public function generate_element( $post_id ) {
		/* need to flag if this can render login form */
		add_filter( 'jnews_can_render_account_popup', '__return_true' );

		if ( $this->show_bookmark_button_meta( $post_id ) && is_single( $post_id ) ) {
			$this->load_assets();

			$bookmark_status = get_user_meta( get_current_user_id(), 'jnews_bookmark_article', true );
			$bookmark_status = '' !== $bookmark_status ? $bookmark_status : array();
			$class           = 'fa fa-bookmark-o';
			$status          = false;

			if ( array_key_exists( $post_id, (array) $bookmark_status ) && $bookmark_status[ $post_id ]['status'] === true ) {
				$class  = 'fa fa-bookmark';
				$status = true;
			}

			$output = '<div class="jeg_meta_bookmark">
							<a href="#" data-id="' . $post_id . '" data-added="' . $status . '" data-message><i class="' . $class . '"></i></a>
						</div>';

			echo jnews_sanitize_by_pass( $output );
		}
	}
}
