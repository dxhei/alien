<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Jannah_Shortcode' ) ) {
	class JNews_Migration_Jannah_Shortcode {

		/**
		 * @var JNews_Migration_Jannah_Shortcode
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
		 * @var array
		 */
		protected $options = array();

		/**
		 * @return JNews_Migration_Jannah_Shortcode
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Jannah_Shortcode constructor
		 */
		private function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

			$this->register_shortcode();
			$this->migration_option_config();
		}

		/**
		 * Assign config get option theme
		 */
		public function migration_option_config() {
			$this->options['lightbox_skin'] = $this->migration_get_option( 'lightbox_skin' );
			if ( empty( $this->options['lightbox_skin'] ) ) {
				$this->options['lightbox_skin'] = 'dark';
			}

			$this->options['lightbox_all']         = $this->migration_get_option( 'lightbox_all' );
			$this->options['lightbox_gallery']     = $this->migration_get_option( 'lightbox_gallery' );
			$this->options['lightbox_thumb']       = $this->migration_get_option( 'lightbox_thumb' );
			$this->options['lightbox_arrows']      = $this->migration_get_option( 'lightbox_arrows' );
			$this->options['woocommerce_lightbox'] = $this->migration_get_option( 'woocommerce_lightbox' );
			$this->options['ads1_shortcode']       = $this->migration_get_option( 'ads1_shortcode' );
		}

		/**
		 * Get theme option
		 *
		 * @param  string $name [description]
		 *
		 * @return false | mixed value
		 */
		public function migration_get_option( $name ) {
			$options = get_option( 'tie_options' );

			if ( ! empty( $options[ $name ] ) ) {
				 return $options[ $name ];
			}

			return false;
		}

		/**
		 * Load shortcode css and js assest
		 */
		public function load_assets() {
			wp_enqueue_style( 'jnews-migration-jannah-style', JNEWS_MIGRATION_JANNAH_URL . '/assets/css/shortcode.css', null, JNEWS_MIGRATION_JANNAH_VERSION );
			wp_enqueue_style( 'jnews-migration-jannah-skin', JNEWS_MIGRATION_JANNAH_URL . '/assets/css/ilightbox/' . $this->options['lightbox_skin'] . '-skin/skin.css', null, JNEWS_MIGRATION_JANNAH_VERSION );

			wp_enqueue_script( 'jnews-migration-jannah-tipsy', JNEWS_MIGRATION_JANNAH_URL . '/assets/js/vendor/tipsy.min.js', array( 'jquery' ), JNEWS_MIGRATION_JANNAH_VERSION );
			wp_enqueue_script( 'jnews-migration-jannah-lightbox', JNEWS_MIGRATION_JANNAH_URL . '/assets/js/vendor/ilightbox.packed.js', array( 'jquery' ), JNEWS_MIGRATION_JANNAH_VERSION );

			wp_register_script( 'jnews-migration-jannah-tabs', JNEWS_MIGRATION_JANNAH_URL . '/assets/js/vendor/tabs.min.js', array( 'jquery' ), JNEWS_MIGRATION_JANNAH_VERSION );
			wp_register_script( 'jnews-migration-jannah-cycle', JNEWS_MIGRATION_JANNAH_URL . '/assets/js/vendor/jquery.cycle.all.js', array( 'jquery' ), JNEWS_MIGRATION_JANNAH_VERSION );

			wp_enqueue_script( 'jnews-migration-jannah-script', JNEWS_MIGRATION_JANNAH_URL . '/assets/js/shortcode.js', array( 'jquery' ), JNEWS_MIGRATION_JANNAH_VERSION );
			wp_localize_script( 'jnews-migration-jannah-script', 'jnewsmigration', $this->migration_localize_script() );
		}

		/**
		 * Localize script var
		 *
		 * @return array
		 */
		public function migration_localize_script() {
			$option = array();

			$option['lightbox_skin']        = $this->options['lightbox_skin'];
			$option['lightbox_all']         = ! empty( $this->option['lightbox_all'] ) ? $this->option['lightbox_all'] : '';
			$option['lightbox_gallery']     = ! empty( $this->option['lightbox_gallery'] ) ? $this->option['lightbox_gallery'] : '';
			$option['lightbox_thumb']       = ! empty( $this->option['lightbox_thumb'] ) ? $this->option['lightbox_thumb'] : '';
			$option['lightbox_arrows']      = ! empty( $this->option['lightbox_arrows'] ) ? $this->option['lightbox_arrows'] : '';
			$option['woocommerce_lightbox'] = ! empty( $this->option['woocommerce_lightbox'] ) ? $this->option['woocommerce_lightbox'] : '';

			return $option;
		}

		public function get_shortcode_func() {
			return $this->prefix . $this->separator . $this->suffix;
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
					'name' => 'box',
					'func' => 'jeg_box',
				),
				array(
					'name' => 'button',
					'func' => 'jeg_button',
				),
				array(
					'name' => 'divider',
					'func' => 'jeg_divider',
				),
				array(
					'name' => 'toggle',
					'func' => 'jeg_toggle',
				),
				array(
					'name' => 'tab',
					'func' => 'jeg_tab',
				),
				array(
					'name' => 'tabs',
					'func' => 'jeg_tabs',
				),
				array(
					'name' => 'tabs_head',
					'func' => 'jeg_tabs_head',
				),
				array(
					'name' => 'tab_title',
					'func' => 'jeg_tab_title',
				),
				array(
					'name' => 'author',
					'func' => 'jeg_author',
				),
				array(
					'name' => 'dropcap',
					'func' => 'jeg_dropcap',
				),
				array(
					'name' => 'tooltip',
					'func' => 'jeg_tooltip',
				),
				array(
					'name' => 'tie_slideshow',
					'func' => 'jeg_slideshow',
				),
				array(
					'name' => 'tie_slide',
					'func' => 'jeg_slide',
				),
				array(
					'name' => 'flickr',
					'func' => 'jeg_flickr',
				),
				array(
					'name' => 'facebook',
					'func' => 'jeg_facebook',
				),
				array(
					'name' => 'digg',
					'func' => 'jeg_digg',
				),
				array(
					'name' => 'stumble',
					'func' => 'jeg_stumble',
				),
				array(
					'name' => 'Google',
					'func' => 'jeg_google',
				),
				array(
					'name' => 'pinterest',
					'func' => 'jeg_pinterest',
				),
				array(
					'name' => 'follow',
					'func' => 'jeg_follow',
				),
				array(
					'name' => 'googlemap',
					'func' => 'jeg_googlemap',
				),
				array(
					'name' => 'feed',
					'func' => 'jeg_feed',
				),
				array(
					'name' => 'lightbox',
					'func' => 'jeg_lightbox',
				),
				array(
					'name' => 'tie_full_img',
					'func' => 'jeg_full_img',
				),
				array(
					'name' => 'padding',
					'func' => 'jeg_padding',
				),
				array(
					'name' => 'highlight',
					'func' => 'jeg_highlight',
				),
				array(
					'name' => 'tie_list',
					'func' => 'jeg_list',
				),
				array(
					'name' => 'ads1',
					'func' => 'jeg_ads1',
				),
				array(
					'name' => 'ads2',
					'func' => 'jeg_ads2',
				),
				array(
					'name' => 'is_logged_in',
					'func' => 'jeg_is_logged_in',
				),
				array(
					'name' => 'is_guest',
					'func' => 'jeg_is_guest',
				),
				array(
					'name' => 'one_third',
					'func' => 'jeg_one_third',
				),
				array(
					'name' => 'one_third_last',
					'func' => 'jeg_one_third_last',
				),
				array(
					'name' => 'two_third',
					'func' => 'jeg_two_third',
				),
				array(
					'name' => 'two_third_last',
					'func' => 'jeg_two_third_last',
				),
				array(
					'name' => 'one_half',
					'func' => 'jeg_one_half',
				),
				array(
					'name' => 'one_half_last',
					'func' => 'jeg_one_half_last',
				),
				array(
					'name' => 'one_fourth',
					'func' => 'jeg_one_fourth',
				),
				array(
					'name' => 'one_fourth_last',
					'func' => 'jeg_one_fourth_last',
				),
				array(
					'name' => 'three_fourth',
					'func' => 'jeg_three_fourth',
				),
				array(
					'name' => 'three_fourth_last',
					'func' => 'jeg_three_fourth_last',
				),
				array(
					'name' => 'one_fifth',
					'func' => 'jeg_one_fifth',
				),
				array(
					'name' => 'one_fifth_last',
					'func' => 'jeg_one_fifth_last',
				),
				array(
					'name' => 'two_fifth',
					'func' => 'jeg_two_fifth',
				),
				array(
					'name' => 'two_fifth_last',
					'func' => 'jeg_two_fifth_last',
				),
				array(
					'name' => 'three_fifth',
					'func' => 'jeg_three_fifth',
				),
				array(
					'name' => 'three_fifth_last',
					'func' => 'jeg_three_fifth_last',
				),
				array(
					'name' => 'four_fifth',
					'func' => 'jeg_four_fifth',
				),
				array(
					'name' => 'four_fifth_last',
					'func' => 'jeg_four_fifth_last',
				),
				array(
					'name' => 'one_sixth',
					'func' => 'jeg_one_sixth',
				),
				array(
					'name' => 'one_sixth_last',
					'func' => 'jeg_one_sixth_last',
				),
				array(
					'name' => 'five_sixth',
					'func' => 'jeg_five_sixth',
				),
				array(
					'name' => 'five_sixth_last',
					'func' => 'jeg_five_sixth_last',
				),
			);

			return $shortcode;
		}

		/**
		 * Box Shortcode
		 */
		public function jeg_box( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'type'  => '',
					'align' => '',
					'class' => '',
					'width' => '',
				),
				$atts
			);

			$atts['width'] = ! empty( $atts['width'] ) ? "style=\"width:{$atts['width']}\"" : '';

			return "<div class=\"box {$atts['type']} {$atts['align']} {$atts['class']} \" {$atts['width']}>
                        <div class=\"box-inner-block\"><i class=\"fa tie-shortcode-boxicon\"></i>
                        " . do_shortcode( $content ) . '
                        </div>
                    </div>';
		}

		/**
		 * Button Shortcode
		 */
		public function jeg_button( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'size'          => '',
					'color'         => '',
					'link'          => '',
					'align'         => '',
					'icon'          => '',
					'button_target' => '',
				),
				$atts
			);

			$atts['icon'] = ! empty( $atts['icon'] ) ? '<i class="fa ' . $atts['icon'] . '"></i>' : '';

			$atts['button_target'] = ! empty( $atts['button_target'] ) ? 'target="_blank"' : '';

			return "<a href=\"{$atts['link']}\" {$atts['button_target']} class=\"shortc-button {$atts['size']} {$atts['color']} {$atts['align']}\">
                        " . $atts['icon'] . ' 
                        ' . do_shortcode( $content ) . ' 
                    </a>';
		}

		/**
		 * Divider Shortcode
		 */
		public function jeg_divider( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'style'  => '',
					'top'    => '',
					'bottom' => '',
				),
				$atts
			);

			return "<div class=\"clear\"></div>
                    <div style=\"margin-top:{$atts['top']}px; margin-bottom:{$atts['bottom']}px;\" class=\"divider divider-{$atts['style']}\"></div>";
		}

		/**
		 * Toggle Shortcode
		 */
		public function jeg_toggle( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'state' => '',
					'title' => '',
				),
				$atts
			);

			return "<div class=\"clear\"></div>
                    <div class=\"toggle {$atts['state']}\">
                        <h3 class=\"toggle-head-open\">
                            {$atts['title']}
                            <i class=\"fa fa-angle-up\"></i>
                        </h3>
                        <h3 class=\"toggle-head-close\">
                            {$atts['title']}
                            <i class=\"fa fa-angle-down\"></i>
                        </h3>
                        <div class=\"toggle-content\">
                            " . do_shortcode( $content ) . '
                        </div>
                    </div>';
		}

		/**
		 * Tabs Shortcode
		 */
		public function jeg_tabs( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'type' => '',
				),
				$atts
			);

			$class_type = ( $atts['type'] == 'vertical' ) ? 'post-tabs-ver' : 'post-tabs';

			wp_enqueue_script( 'jnews-migration-jannah-tabs' );

			return "<script type=\"text/javascript\"> jQuery(document).on('ready', function($){ jQuery(\"ul.tabs-nav\").tabs(\"> .pane\"); }); </script>
                        <div class=\"{$class_type}\">
                            " . do_shortcode( $content ) . '
                        </div>';
		}

		/**
		 * Tab Shortcode
		 */
		public function jeg_tab( $atts, $content = null ) {
			return '<div class="pane">
                        ' . do_shortcode( $content ) . '
                    </div>';
		}

		/**
		 * Tab Head Shortcode
		 */
		public function jeg_tabs_head( $atts, $content = null ) {
			return '<ul class="tabs-nav">
                        ' . do_shortcode( $content ) . '
                    </ul>';
		}

		/**
		 * Tab Title Shortcode
		 */
		public function jeg_tab_title( $atts, $content = null ) {
			return '<li>
                        ' . do_shortcode( $content ) . '
                    </li>';
		}

		/**
		 * Author Shortcode
		 */
		public function jeg_author( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'title' => '',
					'image' => '',
				),
				$atts
			);

			return "<div class=\"clear\"></div>
                        <div class=\"author-info\">
                            <img class=\"author-img\" src=\"{$atts['image']}\" alt=\"{$atts['title']}\" />
                            <div class=\"author-info-content\">
                                <h3>{$atts['title']}</h3>
                                " . do_shortcode( $content ) . '
                            </div>
                        </div>';
		}

		/**
		 * Dropcap Shortcode
		 */
		public function jeg_dropcap( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'type' => '',
				),
				$atts
			);

			return "<span class=\"tie-dropcap {$atts['type']}\">" . do_shortcode( $content ) . '</span>';
		}

		/**
		 * Tooltip Shortcode
		 */
		public function jeg_tooltip( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'text'    => '',
					'gravity' => '',
				),
				$atts
			);

			return "<span class=\"post-tooltip tooltip-{$atts['gravity']}\" title=\"" . do_shortcode( $content ) . "\">{$atts['text']}</span>";
		}

		/**
		 * Slideshow Shortcode
		 */
		public function jeg_slideshow( $atts, $content = null ) {
			$this->generate_unique_id();

			wp_enqueue_script( 'jnews-migration-jannah-cycle' );

			$output = '
                <script type="text/javascript">
                    jQuery(window).load(function() {
                    
                            jQuery( "#post-slideshow-' . $this->unique_id . '" ).cycle({
                                fx:     "scrollHorz",
                                timeout: 0,
                                pager:  ".slideshow-nav-' . $this->unique_id . '",
                                after:  onBefore,
                                containerResize  : false,
                                slideResize: false,
                                fit:           1, 
                                slideExpr: ".post-content-slide",
                                speed: 400,
                                prev:   ".prev-' . $this->unique_id . '", 
                                next:   ".next-' . $this->unique_id . '",
                            });
                    
                        function onBefore() { 
                            var h = jQuery(this).outerHeight() ;
                            jQuery(this).parent().height( h ); 
                        }
                    });
                </script>

                <div class="post-content-slideshow-outer">
                    <div id="post-slideshow-' . $this->unique_id . '" class="post-content-slideshow">
                    
                        <div class="post-tslideshow-nav-outer">
                            <div class="slideshow-nav-' . $this->unique_id . ' post-slideshow-nav"></div>
                            <a class="next-' . $this->unique_id . ' post-slideshow-next" href="#"> ' . esc_html__( 'Next', 'jnews-migration-jannah' ) . ' <i class="fa fa-angle-right"></i></a>
                            <a class="prev-' . $this->unique_id . ' post-slideshow-prev" href="#"><i class="fa fa-angle-left"></i> ' . esc_html__( 'Prev', 'jnews-migration-jannah' ) . '</a>
                        </div>
                        ' . do_shortcode( $content ) . '
                        <div class="post-tslideshow-nav-outer-bottom">
                            <div class="slideshow-nav-' . $this->unique_id . ' post-slideshow-nav"></div>
                            <a class="next-' . $this->unique_id . ' post-slideshow-next" href="#">' . esc_html__( 'Next', 'jnews-migration-jannah' ) . ' <i class="fa fa-angle-right"></i></a>
                            <a class="prev-' . $this->unique_id . ' post-slideshow-prev" href="#"><i class="fa fa-angle-left"></i> ' . esc_html__( 'Prev', 'jnews-migration-jannah' ) . '</a>
                        </div>
                        
                    </div>
                </div>';

			return $output;
		}

		/**
		 * Slide Shortcode
		 */
		public function jeg_slide( $atts, $content = null ) {
			return '<div class="post-content-slide">
                        ' . do_shortcode( $content ) . '
                    </div>';
		}

		/**
		 * Flickr Shortcode
		 */
		public function jeg_flickr( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'number'  => '',
					'orderby' => '',
					'id'      => '',
				),
				$atts
			);

			$port = is_ssl() ? 'https' : 'http';

			return "<div class=\"flickr-wrapper\">
                        <script type=\"text/javascript\" src=\"{$port}://www.flickr.com/badge_code_v2.gne?count={$atts['number']}&amp;display={$atts['orderby']}&amp;size=s&amp;layout=x&amp;source=user&amp;user={$atts['id']}\"></script>
                    </div>";
		}

		/**
		 * Facebook Shortcode
		 */
		public function jeg_facebook( $atts, $content = null ) {
			global $post;
			$port = is_ssl() ? 'https' : 'http';

			return '<iframe src="' . $port . '://www.facebook.com/plugins/like.php?href=' . get_permalink( $post->ID ) . '&amp;layout=box_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;font&amp;colorscheme=light&amp;height=65" scrolling="no" style="border:none; overflow:hidden; width:50px; height:65px;" allowTransparency="true"></iframe>';
		}

		/**
		 * Digg Shortcode
		 */
		public function jeg_digg( $atts, $content = null ) {
			global $post;
			$port = is_ssl() ? 'https' : 'http';

			return "<script type='text/javascript'>
                        (function() {
                            var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
                            s.type = 'text/javascript';
                            s.async = true;
                            s.src = '" . $port . "://widgets.digg.com/buttons.js';
                            s1.parentNode.insertBefore(s, s1);
                        })();
                    </script>
                    <a class='DiggThisButton DiggMedium' href='http://digg.com/submit?url" . get_permalink( $post->ID ) . '=&amp;title=' . get_the_title( $post->ID ) . "'></a>";
		}

		/**
		 * Stumble Shortcode
		 */
		public function jeg_stumble( $atts, $content = null ) {
			global $post;

			return "<su:badge layout='5' location='" . get_permalink( $post->ID ) . "'></su:badge>
                    <script type='text/javascript'>
                      (function() {
                        var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
                        li.src = 'https://platform.stumbleupon.com/1/widgets.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
                      })();
                    </script>";
		}

		/**
		 * Google Shortcode
		 */
		public function jeg_google( $atts, $content = null ) {
			global $post;

			return "<g:plusone size='tall'></g:plusone>
                    <script type='text/javascript'>
                      (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                    </script>";
		}

		/**
		 * Pinterest Shortcode
		 */
		public function jeg_pinterest( $atts, $content = null ) {
			global $post;

			return '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
                    <a href="http://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&amp;media= class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
		}

		/**
		 * Follow Shortcode
		 */
		public function jeg_follow( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'count' => '',
					'size'  => '',
					'id'    => '',
				),
				$atts
			);

			$atts['size']  = ( $atts['size'] == 'large' ) ? 'data-size="large"' : '';
			$atts['count'] = ( $atts['count'] == 'true' ) ? 'true' : 'false';
			$port          = is_ssl() ? 'https' : 'http';

			return '<a href="' . $port . '://twitter.com/' . $atts['id'] . '" class="twitter-follow-button" data-show-count="' . $atts['count'] . '" ' . $atts['size'] . '>Follow @' . $atts['id'] . '</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
		}

		/**
		 * Google Map Shortcode
		 */
		public function jeg_googlemap( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'width'  => '620',
					'height' => '440',
					'align'  => '',
					'src'    => '',
				),
				$atts
			);

			return '<div class="google-map ' . $atts['align'] . '"><iframe width="' . $atts['width'] . '" height="' . $atts['height'] . '" scrolling="no" marginheight="0" marginwidth="0" src="' . $atts['src'] . '&amp;output=embed"></iframe></div>';
		}

		/**
		 * Feed Shortcode
		 */
		public function jeg_feed( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'number' => '5',
					'url'    => '',
				),
				$atts
			);

			include_once ABSPATH . WPINC . '/feed.php';

			$jeg_rss = @fetch_feed( $atts['url'] );

			if ( ! is_wp_error( $jeg_rss ) ) {
				$jeg_maxitems  = $jeg_rss->get_item_quantity( $atts['number'] );
				$jeg_rss_items = $jeg_rss->get_items( 0, $jeg_maxitems );
			}

			if ( empty( $jeg_maxitems ) ) {
				$output = '<ul><li>' . esc_html__( 'No items.', 'jnews-migration-jannah' ) . '</li></ul>';
			} else {
				$output = '<ul>';

				foreach ( $jeg_rss_items as $jeg_item ) :
					$output .= '<li><a target="_blank" href="' . esc_url( $jeg_item->get_permalink() ) . '" title="' . esc_html__( 'Posted ', 'tie' ) . $jeg_item->get_date( 'j F Y | g:i a' ) . '">' . esc_html( $jeg_item->get_title() ) . '</a></li>';
				endforeach;
				$output .= '</ul>';
			}

			return $output;
		}

		/**
		 * Lightbox Shortcode
		 */
		public function jeg_lightbox( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'full'  => '5',
					'title' => '',
				),
				$atts
			);

			$port = is_ssl() ? 'https' : 'http';

			$jeg_video_link = @parse_url( $atts['full'] );

			if ( $jeg_video_link['host'] == 'www.youtube.com' || $jeg_video_link['host'] == 'youtube.com' ) {
				parse_str( @parse_url( $atts['full'], PHP_URL_QUERY ), $array );

				$video            = $array['v'];
				$jeg_video_output = $port . '://www.youtube.com/embed/' . $video . '?rel=0&wmode=opaque&autohide=1&border=0&egm=0&showinfo=0';

			} elseif ( $jeg_video_link['host'] == 'www.youtu.be' || $jeg_video_link['host'] == 'youtu.be' ) {
				$video            = substr( @parse_url( $atts['full'], PHP_URL_PATH ), 1 );
				$jeg_video_output = $port . '://www.youtube.com/embed/' . $video . '?rel=0&wmode=opaque&autohide=1&border=0&egm=0&showinfo=0';

			} elseif ( $jeg_video_link['host'] == 'www.vimeo.com' || $jeg_video_link['host'] == 'vimeo.com' ) {
				$video            = (int) substr( @parse_url( $atts['full'], PHP_URL_PATH ), 1 );
				$jeg_video_output = $port . '://player.vimeo.com/video/' . $video . '?wmode=opaque';

			} else {
				$jeg_video_output = $atts['full'];
			}

			return '<a class="lightbox-enabled" href="' . $jeg_video_output . '" data-caption="' . $atts['title'] . '" title="' . $atts['title'] . '">' . do_shortcode( $content ) . '</a>';
		}

		/**
		 * Full Image Shortcode
		 */
		public function jeg_full_img( $atts, $content = null ) {
			return '<div class="tie-full-width-img">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * Padding Shortcode
		 */
		public function jeg_padding( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'left'  => '100%',
					'right' => '100%',
				),
				$atts
			);

			return '<div class="tie-padding" style="padding-left:' . $atts['left'] . '; padding-right:' . $atts['right'] . ';">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * Highlight Shortcode
		 */
		public function jeg_highlight( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'color' => 'yellow',
				),
				$atts
			);

			return '<span class="migration-highlight migration-highlight-' . $atts['color'] . '">' . do_shortcode( $content ) . '</span>';
		}

		/**
		 * List Shortcode
		 */
		public function jeg_list( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'type' => 'checklist',
				),
				$atts
			);

			return '<div class="' . $atts['type'] . ' tie-list-shortcode">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * Ads 1 Shortcode
		 */
		public function jeg_ads1( $atts, $content = null ) {
			return '<div class="e3lan e3lan-in-post1">' . htmlspecialchars_decode( $this->options['ads1_shortcode'] ) . '</div>';
		}

		/**
		 * Ads 2 Shortcode
		 */
		public function jeg_ads2( $atts, $content = null ) {
			return '<div class="e3lan e3lan-in-post2">' . htmlspecialchars_decode( $this->options['ads2_shortcode'] ) . '</div>';
		}

		/**
		 * For Logged User Only Shortcode
		 */
		public function jeg_is_logged_in( $atts, $content = null ) {
			global $user_ID;

			if ( $user_ID ) {
				return do_shortcode( $content );
			}
		}

		/**
		 * For Guest User Shortcode
		 */
		public function jeg_is_guest( $atts, $content = null ) {
			global $user_ID;

			if ( ! $user_ID ) {
				return do_shortcode( $content );
			}
		}

		/**
		 * One - Third Shortcode
		 */
		public function jeg_one_third( $atts, $content = null ) {
			return '<div class="one_third">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * One - Third Last Shortcode
		 */
		public function jeg_one_third_last( $atts, $content = null ) {
			return '<div class="one_third last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * Two - Third Shortcode
		 */
		public function jeg_two_third( $atts, $content = null ) {
			return '<div class="two_third">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * Two - Third Last Shortcode
		 */
		public function jeg_two_third_last( $atts, $content = null ) {
			return '<div class="two_third last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * One - Half Shortcode
		 */
		public function jeg_one_half( $atts, $content = null ) {
			return '<div class="one_half">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * One - Half Last Shortcode
		 */
		public function jeg_one_half_last( $atts, $content = null ) {
			return '<div class="one_half last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * One - Fourth Shortcode
		 */
		public function jeg_one_fourth( $atts, $content = null ) {
			return '<div class="one_fourth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * One - Fourth Last Shortcode
		 */
		public function jeg_one_fourth_last( $atts, $content = null ) {
			return '<div class="one_fourth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * Three - Fourth Shortcode
		 */
		public function jeg_three_fourth( $atts, $content = null ) {
			return '<div class="three_fourth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * Three - Fourth Last Shortcode
		 */
		public function jeg_three_fourth_last( $atts, $content = null ) {
			return '<div class="three_fourth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * One - Fifth Shortcode
		 */
		public function jeg_one_fifth( $atts, $content = null ) {
			return '<div class="one_fifth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * One - Fifth Last Shortcode
		 */
		public function jeg_one_fifth_last( $atts, $content = null ) {
			return '<div class="one_fifth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * Two - Fifth Shortcode
		 */
		public function jeg_two_fifth( $atts, $content = null ) {
			return '<div class="two_fifth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * Two - Fifth Last Shortcode
		 */
		public function jeg_two_fifth_last( $atts, $content = null ) {
			return '<div class="two_fifth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * Three - Fifth Shortcode
		 */
		public function jeg_three_fifth( $atts, $content = null ) {
			return '<div class="three_fifth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * Three - Fifth Last Shortcode
		 */
		public function jeg_three_fifth_last( $atts, $content = null ) {
			return '<div class="three_fifth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * Four - Fifth Shortcode
		 */
		public function jeg_four_fifth( $atts, $content = null ) {
			return '<div class="four_fifth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * Four - Fifth Last Shortcode
		 */
		public function jeg_four_fifth_last( $atts, $content = null ) {
			return '<div class="four_fifth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * One - Sixth Shortcode
		 */
		public function jeg_one_sixth( $atts, $content = null ) {
			return '<div class="one_sixth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * One - Sixth Last Shortcode
		 */
		public function jeg_one_sixth_last( $atts, $content = null ) {
			return '<div class="one_sixth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}

		/**
		 * Five - Sixth Shortcode
		 */
		public function jeg_five_sixth( $atts, $content = null ) {
			return '<div class="five_sixth">'
					. do_shortcode( $content ) .
				 '</div>';
		}

		/**
		 * Five - Sixth Last Shortcode
		 */
		public function jeg_five_sixth_last( $atts, $content = null ) {
			return '<div class="five_sixth last">'
					. do_shortcode( $content ) .
				 '</div><div class="clear"></div>';
		}
	}
}
