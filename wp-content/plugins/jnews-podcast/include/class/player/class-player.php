<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Player;

/**
 * Class Player
 *
 * @package JNEWS_PODCAST\Player
 */
class Player {

	/**
	 * Instance of Player
	 *
	 * @var Player
	 */
	private static $instance;

	protected $shortcode = array(
		'player' => 'jeg_player',
		'track'  => 'jeg_player_track',
	);

	protected $total = 0;

	protected $types = array(
		'audio',
	);

	protected $type;

	/**
	 * Player constructor.
	 */
	private function __construct() {
		add_action( 'template_redirect', array( $this, 'render_player' ) );
		add_action( 'jnews_share_top_bar', array( $this, 'render_player_in_post' ), 99 );
		do_action( 'jnews_render_element', $this->shortcode['player'], array( $this, 'player_shortcode' ) );
		do_action( 'jnews_render_element', $this->shortcode['track'], array( $this, 'player_track_shortcode' ) );
		add_filter( 'wp_print_styles', array( $this, 'add_single_episode_to_playlist' ) );
	}

	/**
	 * Singleton page of Player class
	 *
	 * @return Player
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Callback for [jeg_player] shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function player_shortcode( $atts = array(), $content = '' ) {
		$html = '';
		if ( defined( 'JNEWS_THEME_URL' ) ) {
			static $instance = 0;
			$instance ++;
			wp_enqueue_script( 'jnews-jplayer', JNEWS_PODCAST_URL . '/assets/js/jplayer/jquery.jplayer.js', array( 'jquery' ), JNEWS_PODCAST_VERSION, true );
			wp_enqueue_script( 'jnews-jplayer-playlist', JNEWS_PODCAST_URL . '/assets/js/jnews.playlist.js', array( 'jquery' ), JNEWS_PODCAST_VERSION, true );
			wp_enqueue_style( 'font-awesome', JNEWS_THEME_URL . '/assets/css/font-awesome.min.css', null, jnews_get_theme_version() );
			$cover_item         = '<div class="jeg_player_current_item__cover"></div>';
			$item_title_wrapper = '<div class="jeg_player_current_item__title_wrapper">
								       <div class="jeg_player_current_item__title jeg_post_title"><span>-</span></div>
								   </div>';
			$jeg_player_bar     = '<div class="jeg_player_bar">
										<div class="jeg_player_bar__current_time"><span>00:00</span></div>
										<div class="jeg_progress_bar">
											<div class="jeg_progress_bar__seek">
												<div class="jeg_progress_bar__play"><div tabindex="-1" class="jeg_progress_bar__ball"></div></div>
											</div>
										</div>
										<div class="jeg_player_bar__duration"><span>00:00</span></div>
									</div>';
			$jeg_playlist       = '<div class="jeg_playlist">
										<ul class="jeg_playlist_inner">
											<li></li>
										</ul>
									</div>';
			$main_control       =
				'<a href="javascript:;" class="jeg_player_control__previous disabled" tabindex="1" title="' . jnews_return_translation( 'Previous', 'jnews-podcast', 'previous' ) . '"><i class="fa fa-fast-backward"></i></a>' .
				'<a href="javascript:;" class="jeg_player_control__play" tabindex="1" title="' . jnews_return_translation( 'Play', 'jnews-podcast', 'play' ) . '"><i class="fa fa-play"></i></a>' .
				'<a href="javascript:;" class="jeg_player_control__pause" tabindex="1" title="' . jnews_return_translation( 'Pause', 'jnews-podcast', 'pause' ) . '"><i class="fa fa-pause"></i></a>' .
				'<a href="javascript:;" class="jeg_player_control__next" tabindex="1" title="' . jnews_return_translation( 'Next', 'jnews-podcast', 'next' ) . '"><i class="fa fa-fast-forward"></i></a>';
			$shuffle_btn        =
				'<a href="javascript:;" class="jeg_player_control__shuffle_off" tabindex="1" title="' . jnews_return_translation( 'Shuffle', 'jnews-podcast', 'shuffle' ) . '"><i class="fa fa-random"></i></a>' .
				'<a href="javascript:;" class="jeg_player_control__shuffle" tabindex="1" title="' . jnews_return_translation( 'Shuffle', 'jnews-podcast', 'shuffle' ) . '"><i class="fa fa-random"></i></a>';
			$repeat_btn         =
				'<a href="javascript:;" class="jeg_player_control__repeat_off" tabindex="1" title="' . jnews_return_translation( 'Repeat', 'jnews-podcast', 'repeat' ) . '"><i class="fa fa-repeat"></i></a>' .
				'<a href="javascript:;" class="jeg_player_control__repeat" tabindex="1" title="' . jnews_return_translation( 'Repeat', 'jnews-podcast', 'repeat' ) . '"><i class="fa fa-repeat"></i></a>';

			// Get Track
			$track = '';
			if ( ! empty( $content ) ) {
				$content = wp_strip_all_tags( nl2br( do_shortcode( $content ) ) );

				// Replace last comma
				if ( false !== ( $pos = strrpos( $content, ',' ) ) ) {
					$content = substr_replace( $content, '', $pos, 1 );
				}
				$track  = '<script class="jnews_player_playlist_script" type="application/json">';
				$track .= $content;
				$track .= '</script>';
			}

			$html = '
			<div class="jeg_player audio_player style_' . $instance . '">
			    <div class="jeg_player_wrapper">
			        <div id="jeg-player-' . $instance . '" class="jeg_jplayer"></div>
			        <div id="jeg-player-container-' . $instance . '" class="jeg_audio">
			            <div class="jeg_player_inner">
			                <div class="jeg_player_controls_wrap">
			                    <div class="jeg_control_bar_left">
									<!-- player-control -->
									<div class="jeg_player_control">
										' . $main_control . '
									</div>
			                    </div>
			                    <div class="jeg_control_bar_center">
				                    <div class="jeg_player_current_item">
				                        ' . $cover_item . '
				                        <!-- player-progress -->
					                    <div class="jeg_player_current_item__content">
										   ' . $item_title_wrapper . $jeg_player_bar . '
					                    </div>
									</div>
								</div>
								<div class="jeg_control_bar_toggle_player">
									<a href="javascript:;" class="jeg_player_control__toggle_player" tabindex="1" title="' . jnews_return_translation( 'Toggle Player', 'jnews-podcast', 'toggle_player' ) . '"><i class="fa fa-angle-up"></i></a>
								</div>
			                    <div class="jeg_control_bar_right">
				                    <!-- control-last -->
									<div class="jeg_player_control last">
											' . $shuffle_btn . $repeat_btn . '
				                            <a href="javascript:;" class="jeg_player_control__playlist_toggle" tabindex="1" title="' . jnews_return_translation( 'Toggle PlayList', 'jnews-podcast', 'toggle_playList' ) . '">
				                            	<i class="fa fa-list-ul"></i>
												<div class="jeg_player_control__playlist">
													<span class="jeg_player_control__close_player">
														<i class="fa fa-angle-down"></i>
													</span>
													<div class="jeg_block_heading">
														<h3 class="jeg_block_title">
															<span>' . jnews_return_translation( 'Queue', 'jnews-podcast', 'queue' ) . '</span>
														</h3>
													</div>
				                            		<ul class="jeg_player_control__playlist_inner">
								                        <li></li>
								                    </ul>
												</div>
				                            </a>
				                            <div class="jeg_player_bar__volume_icon">
					                            <a href="javascript:;" class="jeg_player_control__mute" tabindex="1" title="' . jnews_return_translation( 'Mute', 'jnews-podcast', 'mute' ) . '"><i class="fa fa-volume-up"></i></a>
					                            <a href="javascript:;" class="jeg_player_control__unmute" tabindex="1" title="' . jnews_return_translation( 'Unmute', 'jnews-podcast', 'unmute' ) . '"><i class="fa fa-volume-off"></i></a>
					                        </div>
				                    </div>
				                    <div class="jeg_player_bar volume">
				                    	<div class="jeg_volume_bar_wrapper">
						                    <div title="' . jnews_return_translation( 'Volume', 'jnews-podcast', 'volume' ) . '" class="jeg_volume_bar">
						                        <div class="jeg_volume_bar__value"><div tabindex="-1" class="jeg_progress_bar__ball"></div></div>
						                    </div>
					                    </div>
				                    </div>
								</div>
			                </div>
			                ' . $jeg_playlist . '
			                <div class="jeg_no_solution">
			                    <span>Update Required</span>
			                    <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>
			                </div>
						</div>
						<div class="jeg_mobile_player_wrapper">
							<span class="jeg_player_control__close_player" data-playeropen>
								<i class="fa fa-angle-down"></i>
							</span>
							<div class="jeg_player_current_item_cover_container">
								' . $cover_item . '
							</div>
							' . $item_title_wrapper . '
							<div class="jeg_player_bar_container">
								' . $jeg_player_bar . '
							</div>
							' . $jeg_playlist . '
							<div class="jeg_player_control">
								' . $shuffle_btn . $main_control . $repeat_btn . '
							</div> 
						</div>
			        </div>
				</div>
				' . $track . '
			</div>';

		}

		return $html;
	}

	/**
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function player_track_shortcode( $atts = array(), $content = '' ) {
		$atts = shortcode_atts(
			array(
				'series'    => '',
				'thumbnail' => sprintf( '%s/wp-includes/images/media/%s.png', get_site_url(), 'audio' ),
				'title'     => '',
				'href'      => '',
				'src'       => '',
			),
			$atts,
			$this->shortcode['track']
		);

		$data['series_name']    = sanitize_text_field( $atts['series'] );
		$data['post_title']     = sanitize_text_field( $atts['title'] );
		$data['post_thumbnail'] = esc_url( $atts['thumbnail'] );
		$data['post_url']       = esc_url( $atts['href'] );
		$data['upload']         = esc_url( $atts['src'] );

		return wp_json_encode( $data ) . ',';
	}

	/**
	 *  Render Player
	 */
	public function render_player() {
		if ( jnews_podcast_option( 'podcast_enable_player', false ) && jnews_podcast_option( 'podcast_global_player', false ) ) {
			add_action( 'wp_footer', array( $this, 'player' ) );
			add_filter( 'body_class', array( $this, 'add_body_class' ) );
		}
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {
		$classes[] = 'jnews_global_player';

		return $classes;
	}

	/**
	 * @param $post_id
	 */
	public function render_player_in_post( $post_id ) {
		$player      = '';
		$result      = $this->is_single_episode();
		$lock_player = false;
		if ( function_exists( 'jpw_pages_list' ) ) {
			$paywall_truncater = \JNews\Paywall\Truncater\Truncater::instance();
			if ( $paywall_truncater->check_status() ) {
				$paywall_truncater->show_button( true );
				$lock_player = true;
			}
		}
		if ( ! empty( $result ) && ! $lock_player && jnews_podcast_option( 'podcast_enable_player', false ) && ! jnews_podcast_option( 'podcast_global_player', false ) ) {
			$data   = $this->get_episode_data( $post_id );
			$track  = "[jeg_player_track series='{$data['series_name']}' thumbnail='{$data['post_thumbnail']}' title='{$data['post_title']}' href='{$data['post_url']}' src='{$data['episode_upload']}' ]";
			$player = do_shortcode( '[jeg_player]' . $track . '[/jeg_player]' );
		}

		if ( ! empty( $result ) && ! $lock_player && jnews_podcast_option( 'podcast_enable_player', false ) && jnews_podcast_option( 'podcast_global_player', false ) ) {
			$player = jnews_podcast_add_media_menu( $post_id, 'single_episode', 'plus' );
		}

		echo jnews_sanitize_output( $player );
	}

	/**
	 * @return array|bool
	 */
	public function is_single_episode() {
		$result = false;

		if ( is_singular( 'post' ) ) {
			$post_id = get_the_ID();
			$args    = array(
				'post_id' => $post_id,
			);
			$result  = $this->set_player_data( $args );
		}

		return $result;
	}

	/**
	 * Set Player Data
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function set_player_data( $data ) {
		$multiple_data = false;
		$result        = array();
		if ( is_array( $data ) ) {
			if ( ! isset( $data['post_id'] ) ) {
				$multiple_data = true;
			}
			if ( $multiple_data ) {
				foreach ( $data as $key => $value ) {
					if ( isset( $value['post_id'] ) ) {
						$episode_data = $this->get_episode_data( $value['post_id'] );
						if ( ! empty( $episode_data ) ) {
							$result[] = array(
								'series_name'    => $episode_data['series_name'],
								'post_thumbnail' => $episode_data['post_thumbnail'],
								'post_title'     => $episode_data['post_title'],
								'post_url'       => $episode_data['post_url'],
								'upload'         => $episode_data['episode_upload'],
								'post_type'      => $episode_data['post_type'],
							);
						}
					}
				}
			} else {
				$episode_data = $this->get_episode_data( $data['post_id'] );
				if ( ! empty( $episode_data ) ) {
					$result = array(
						'series_name'    => $episode_data['series_name'],
						'post_thumbnail' => $episode_data['post_thumbnail'],
						'post_title'     => $episode_data['post_title'],
						'post_url'       => $episode_data['post_url'],
						'upload'         => $episode_data['episode_upload'],
						'post_type'      => $episode_data['post_type'],
					);
				}
			}
		}

		return $result;
	}

	/**
	 * Get episode data
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public function get_episode_data( $post_id ) {
		$data                 = array();
		$jnews_podcast_option = get_post_meta( $post_id, 'jnews_podcast_option', true );
		$enable               = ( isset( $jnews_podcast_option['enable_podcast'] ) && '1' === $jnews_podcast_option['enable_podcast'] );
		$upload               = isset( $jnews_podcast_option['upload'] ) && ! empty( $jnews_podcast_option['upload'] ) ? $jnews_podcast_option['upload'] : '';
		if ( $enable && ! empty( $upload ) ) {
			$series = wp_get_post_terms( $post_id, 'jnews-series' );
			$series = is_wp_error( $series ) ? '' : $series;
			$series = is_array( $series ) ? ( ! empty( $series ) ? $series[0] : $series ) : $series;
			if ( has_post_thumbnail( $post_id ) ) {
				$image = get_the_post_thumbnail_url( $post_id, 'jnews-75x75' );
			} else {
				$image = sprintf( '%s/wp-includes/images/media/%s.png', get_site_url(), 'audio' );
				if ( ! empty( $series ) ) {
					$attribute = jnews_podcast_attribute( $series->term_id, array( 'fields' => array( 'image' ) ) );
					if ( $attribute['image'] ) {
						$image = wp_get_attachment_image_url( $attribute['image'], 'post-thumbnail' );
					}
				}
			}
			if ( ! empty( $series ) ) {
				$data = array(
					'series_name'    => $series->name,
					'post_thumbnail' => $image,
					'post_title'     => get_the_title( $post_id ),
					'post_url'       => get_post_permalink( $post_id ),
					'episode_upload' => $upload,
					'post_type'      => get_post_type( $post_id ),
				);
				if ( function_exists( 'jpw_pages_list' ) ) {
					$paywall_truncater = \JNews\Paywall\Truncater\Truncater::instance();
					if ( $paywall_truncater->check_status( $post_id ) ) {
						$data['episode_upload'] = '';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Player Template
	 */
	public function player() {
		jnews_podcast_get_template_part( 'include/fragment/player/player' );
	}

	/**
	 * add single to playlist
	 */
	public function add_single_episode_to_playlist() {
		$result = $this->is_single_episode();
		if ( ! empty( $result ) ) {
			wp_localize_script( 'jnews-podcast', 'single_podcast_data', $result );
		}
	}
}
