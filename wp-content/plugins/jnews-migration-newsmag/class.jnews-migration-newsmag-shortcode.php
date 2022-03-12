<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Newsmag_Shortcode' ) ) {
	class JNews_Migration_Newsmag_Shortcode {
		/**
		 * @var JNews_Migration_Newsmag_Shortcode
		 */
		private static $instance;

		/**
		 * @var string
		 */
		private $prefix = 'add';

		/**
		 * @var string
		 */
		private $separator = '_';

		/**
		 * @var string
		 */
		private $suffix = 'shortcode';

		/**
		 * @var string
		 */
		protected $unique_id;

		/**
		 * @var string
		 */
		private $meta_name = 'jnews_video_cache';

		/**
		 * @return JNews_Migration_Newsmag_Shortcode
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		/**
		 * JNews_Migration_Newsmag_Shortcode constructor
		 */
		private function __construct() {
			add_action( 'wp_print_styles', array( $this, 'load_assets' ) );

			add_filter( 'shortcode_atts_gallery', array( $this, 'migration_gallery_filter' ), 10, 3 );

			$this->register_shortcode();
		}

		/**
		 * Load shortcode css and js assest
		 */
		public function load_assets() {
			wp_enqueue_style( 'jnews-migration-newsmag-style', JNEWS_MIGRATION_NEWSMAG_URL . '/assets/css/shortcode.css', null, JNEWS_MIGRATION_NEWSMAG_VERSION );

			wp_enqueue_style( 'js_composer_front' );
		}

		/**
		 * Gallery Shortcode Filter
		 *
		 * @param  array $out
		 * @param  array $pairs
		 * @param  array $atts
		 *
		 * @return array
		 */
		public function migration_gallery_filter( $out, $pairs, $atts ) {
			if ( isset( $atts['td_select_gallery_slide'] ) && $atts['td_select_gallery_slide'] === 'slide' ) {
				$out['jnewsslider']       = true;
				$out['jnewsslider_title'] = isset( $atts['td_gallery_title_input'] ) ? $atts['td_gallery_title_input'] : '';
			}

			return $out;
		}

		public function get_shortcode_func() {
			return $this->prefix . $this->separator . $this->suffix;
		}

		/**
		 * Register shortcode
		 */
		public function register_shortcode() {
			$shortcodes = $this->get_shortcode_list();

			foreach ( $shortcodes as $shortcode ) {
				call_user_func_array(
					$this->get_shortcode_func(),
					array(
						$shortcode['name'],
						array( $this, $shortcode['func'] ),
					)
				);
			}
		}

		/**
		 * Shortcode list
		 *
		 * @return array
		 */
		public function get_shortcode_list() {
			$shortcode = array(
				array(
					'name' => 'td_block_video_youtube',
					'func' => 'jeg_youtube_video_playlist',
				),
				array(
					'name' => 'td_block_video_vimeo',
					'func' => 'jeg_vimeo_video_playlist',
				),
				array(
					'name' => 'button',
					'func' => 'jeg_button',
				),
			);

			return $shortcode;
		}

		/**
		 * Get current post id
		 *
		 * @return int
		 */
		public function get_post_id() {
			global $wp_query;

			if ( isset( $wp_query->post ) ) {
				return $wp_query->post->ID;
			}

			return null;
		}

		/**
		 * Generate unique ID
		 */
		public function generate_unique_id() {
			$this->unique_id = 'jnews_playlist_' . $this->get_post_id() . '_' . uniqid();
		}

		public function youtube_api() {
			return get_theme_mod( 'jnews_youtube_api' );
		}

		/**
		 * Get video wrapper
		 *
		 * @param  int     $post_id
		 * @param  array   $result
		 * @param  bollean $autoplay
		 *
		 * @return string
		 */
		public function get_video_wrapper( $post_id, $result, $autoplay ) {
			$output   = '';
			$autoplay = $autoplay ? '&amp;autoplay=1;' : '';
			$video_id = $result[ $post_id ]['id'];

			if ( $result[ $post_id ]['type'] === 'youtube' ) {
				$output .=
					'<div class="jeg_video_container">
                        <iframe src="//www.youtube.com/embed/' . $video_id . '?showinfo=1' . $autoplay . '&amp;autohide=1&amp;rel=0&amp;wmode=opaque" allowfullscreen="" height="500" width="700"></iframe>
                    </div>';
			} elseif ( $result[ $post_id ]['type'] === 'vimeo' ) {
				$output .=
					'<div class="jeg_video_container">
                        <iframe src="//player.vimeo.com/video/' . $video_id . '?wmode=opaque' . $autoplay . '" allowfullscreen="" height="500" width="700"></iframe>
                    </div>';
			}

			return $output;
		}

		/**
		 * Build video detail info
		 *
		 * @param  array  $videos
		 * @param  string $type
		 *
		 * @return array
		 */
		public function build_result( $videos, $type ) {
			$post_id = $this->get_post_id();

			$video_retrieve = $video_result = array();

			$video_cache = get_post_meta( $post_id, $this->meta_name, true );

			if ( ! $video_cache ) {
				$video_cache = array();
			}

			foreach ( $videos as $key => $video ) {
				if ( ! array_key_exists( $video, $video_cache ) ) {
					$video_retrieve[] = $video;
				}
			}

			if ( ! empty( $video_retrieve ) ) {
				$video_detail = $this->get_video_detail( $videos, $type );
				$video_cache  = $video_detail + $video_cache;
				update_post_meta( $post_id, $this->meta_name, $video_detail );
			}

			foreach ( $videos as $key => $video ) {
				$video_result[] = $video_cache[ $video ];
			}

			return $video_result;
		}

		/**
		 * Get additional video detail
		 *
		 * @param  array  $videos
		 * @param  string $type
		 *
		 * @return array
		 */
		public function get_video_detail( $videos, $type ) {
			$vimeo = $youtube = $video_detail = array();

			foreach ( $videos as $key => $video ) {
				if ( $type == 'vimeo' ) {
					$video_detail[ $video ]['url']  = 'https://vimeo.com/' . $video;
					$video_detail[ $video ]['type'] = 'vimeo';
					$vimeo[ $key ]                  = $video;
				} elseif ( $type == 'youtube' ) {
					$video_detail[ $video ]['url']  = 'https://www.youtube.com/watch?v=' . $video;
					$video_detail[ $video ]['type'] = 'youtube';
					$youtube[ $key ]                = $video;
				}

				$video_detail[ $video ]['id'] = $video;
			}

			// proceed youtube
			if ( ! empty( $youtube ) ) {
				$url            = 'https://www.googleapis.com/youtube/v3/videos?id=' . implode( ',', $youtube ) . '&part=id,contentDetails,snippet&key=' . $this->youtube_api();
				$youtube_remote = wp_remote_get(
					$url,
					array(
						'timeout' => 60000,
					)
				);

				if ( ! is_wp_error( $youtube_remote ) && $youtube_remote['response']['code'] == '200' ) {
					$youtube_remote = json_decode( $youtube_remote['body'] );

					foreach ( $youtube_remote->items as $item ) {
						$video_detail[ $item->id ]['title']     = $item->snippet->title;
						$video_detail[ $item->id ]['thumbnail'] = $item->snippet->thumbnails->default->url;
						$video_detail[ $item->id ]['duration']  = $this->youtube_duration( $item->contentDetails->duration );
					}
				}
			}

			// proceed vimeo
			if ( ! empty( $vimeo ) ) {
				foreach ( $vimeo as $item ) {
					$url          = 'http://vimeo.com/api/v2/video/' . $item . '.json';
					$vimeo_remote = wp_remote_get(
						$url,
						array(
							'timeout' => 60000,
						)
					);

					if ( ! is_wp_error( $vimeo_remote ) && $vimeo_remote['response']['code'] == '200' ) {
						$vimeo_remote = json_decode( $vimeo_remote['body'] );

						$video_detail[ $vimeo_remote[0]->id ]['title']     = $vimeo_remote[0]->title;
						$video_detail[ $vimeo_remote[0]->id ]['thumbnail'] = $vimeo_remote[0]->thumbnail_medium;
						$video_detail[ $vimeo_remote[0]->id ]['duration']  = gmdate( 'H:i:s', intval( $vimeo_remote[0]->duration ) );
					}
				}
			}

			return $video_detail;
		}

		/**
		 * Get YouTube video duration
		 *
		 * @param  int $duration
		 *
		 * @return int
		 */
		public function youtube_duration( $duration ) {
			if ( ! empty( $duration ) ) {
				preg_match( '/(\d+)H/', $duration, $match );
				$h = count( $match ) ? filter_var( $match[0], FILTER_SANITIZE_NUMBER_INT ) : 0;

				preg_match( '/(\d+)M/', $duration, $match );
				$m = count( $match ) ? filter_var( $match[0], FILTER_SANITIZE_NUMBER_INT ) : 0;

				preg_match( '/(\d+)S/', $duration, $match );
				$s = count( $match ) ? filter_var( $match[0], FILTER_SANITIZE_NUMBER_INT ) : 0;

				$duration = gmdate( 'H:i:s', intval( $h * 3600 + $m * 60 + $s ) );
			}

			return $duration;
		}

		/**
		 * Build playlist data
		 *
		 * @param  array $playlist
		 *
		 * @return array
		 */
		public function explode_playlist( $playlist ) {
			$results = explode( ',', $playlist );
			$videos  = array();

			foreach ( $results as $result ) {
				$result = trim( $result );
				if ( ! empty( $result ) ) {
					$videos[] = $result;
				}
			}

			return $videos;
		}

		/**
		 * Build content playlist
		 *
		 * @param  array $results
		 *
		 * @return string
		 */
		public function build_playlist( $results ) {
			$output = '';

			foreach ( $results as $key => $post ) {
				$active = $key === 0 ? 'active' : '';

				$output .=
					"<div class=\"jeg_video_playlist_item_wrapper\">
					<a class=\"jeg_video_playlist_item {$active}\" href=\"" . $post['url'] . '" data-id="' . $key . "\">
                        <div class=\"jeg_video_playlist_thumbnail\">
                            <img src='{$post['thumbnail']}'/>
                        </div>
                        <div class=\"jeg_video_playlist_description\">
                            <h3 class=\"jeg_video_playlist_title\">" . $post['title'] . '</h3>
                            <span class="jeg_video_playlist_category">' . $post['duration'] . '</span>
                        </div>
                    </a>
					</div>';
			}

			return $output;
		}

		/**
		 * Build data script
		 *
		 * @param  array $results
		 *
		 * @return json string
		 */
		public function build_data( $results ) {
			$json = array();

			foreach ( $results as $key => $post ) {
				$json[ $key ] = array(
					'type' => $results[ $key ]['type'],
					'tag'  => $this->get_video_wrapper( $key, $results, true ),
				);
			}

			return wp_json_encode( $json );
		}

		/**
		 * Build content video playlist shortcode
		 *
		 * @param  array  $atts
		 * @param  string $type
		 *
		 * @return string
		 */
		public function jeg_build_video_playlist( $atts, $type ) {
			$this->generate_unique_id();

			if ( $type == 'youtube' ) {
				$videos = $this->explode_playlist( $atts['playlist_yt'] );
			} elseif ( $type == 'vimeo' ) {
				$videos = $this->explode_playlist( $atts['playlist_v'] );
			}

			$videos   = $this->build_result( $videos, $type );
			$playlist = $this->build_playlist( $videos );

			if ( ( SCRIPT_DEBUG || get_theme_mod( 'jnews_load_necessary_asset', false ) ) && ! is_user_logged_in() ) {
				wp_dequeue_style( 'jnews-scheme' );
				wp_enqueue_script( 'jnews-videoplaylist' );
				wp_enqueue_style( 'jnews-videoplaylist' );
				wp_enqueue_style( 'jnews-scheme' );
			}

			return "<div class=\"jeg_video_playlist jeg_col_12 jeg_vertical_playlist {$this->unique_id}\" data-unique='{$this->unique_id}'>
                        <div class=\"jeg_video_playlist_wrapper\">
                            <div class=\"jeg_video_playlist_video_content\">
                                <div class=\"jeg_video_holder\">
                                    {$this->get_video_wrapper( 0, $videos, $atts['playlist_auto_play'])}
                                </div>
                            </div><!-- jeg_video_playlist_video_content -->

                            <div class=\"jeg_video_playlist_list_wrapper\">
                                <div class=\"jeg_video_playlist_current\">
                                    <div class=\"jeg_video_playlist_play\">
                                        <div class=\"jeg_video_playlist_play_icon\">
                                            <i class=\"fa fa-play\"></i>
                                        </div>
                                        <span>" . jnews_return_translation( 'Currently Playing', 'jnews', 'currently_playing' ) . "</span>
                                    </div>
                                    <div class=\"jeg_video_playlist_current_info\">
                                        <h2><a href='{$videos[0]['url']}'>{$videos[0]['title']}</a></h2>
                                    </div>
                                </div>
                                <div class=\"jeg_video_playlist_list_inner_wrapper\">
                                    {$playlist}
                                </div>
                            </div><!-- jeg_video_playlist_list_wrapper -->
                            <div style=\"clear: both;\"></div>
                        </div><!-- jeg_video_playlist_wrapper -->
                        <script> var {$this->unique_id} = {$this->build_data($videos)}; </script>
                    </div>";
		}

		/**
		 * YouTube Video Playlist Shortcode
		 */
		public function jeg_youtube_video_playlist( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'playlist_title'     => '',
					'playlist_yt'        => '',
					'playlist_auto_play' => '',
				),
				$atts
			);

			return $this->jeg_build_video_playlist( $atts, 'youtube' );
		}

		/**
		 * Vimeo Video Playlist Shortcode
		 */
		public function jeg_vimeo_video_playlist( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'playlist_title'     => '',
					'playlist_v'         => '',
					'playlist_auto_play' => '',
				),
				$atts
			);

			return $this->jeg_build_video_playlist( $atts, 'vimeo' );
		}

		/**
		 * Button Shortcode
		 */
		public function jeg_button( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'color'  => '',
					'size'   => '',
					'type'   => '',
					'target' => '',
					'link'   => '',
				),
				$atts
			);

			return "<a class=\"vc_btn vc_btn-{$atts['color']} vc_btn-{$atts['size']} vc_btn_{$atts['type']}\" target=\"{$atts['target']}\" href=\"{$atts['link']}\">"
				   . do_shortcode( $content ) .
				   '</a>';
		}

	}
}
