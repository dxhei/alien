<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Publisher_Shortcode' ) ) {
	class JNews_Migration_Publisher_Shortcode {

		/**
		 * @var JNews_Migration_Publisher_Shortcode
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
		private $tabs_count = null;

		/**
		 * @var string
		 */
		private $tabs = null;

		/**
		 * @return JNews_Migration_Publisher_Shortcode
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Publisher_Shortcode constructor
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
			wp_enqueue_style( 'jnews-migration-publisher-style', JNEWS_MIGRATION_PUBLISHER_URL . '/assets/css/shortcode.css', null, JNEWS_MIGRATION_PUBLISHER_VERSION );

			if ( defined( 'WPB_VC_VERSION' ) ) {
				wp_enqueue_style( 'vc_tta_style', vc_asset_url( 'css/js_composer_tta.min.css' ), false, WPB_VC_VERSION );
				wp_enqueue_style( 'js_composer_front' );

				wp_enqueue_script( 'wpb_composer_front_js' );
				wp_enqueue_script( 'vc_accordion_script', vc_asset_url( 'lib/vc_accordion/vc-accordion.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
				wp_enqueue_script( 'vc_tabs_script', vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ), array( 'vc_accordion_script' ), WPB_VC_VERSION, true );
			}
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
					'name' => 'tabs',
					'func' => 'jeg_tabs',
				),
				array(
					'name' => 'tab',
					'func' => 'jeg_tab',
				),
				array(
					'name' => 'accordions',
					'func' => 'jeg_accordions',
				),
				array(
					'name' => 'accordion',
					'func' => 'jeg_accordion',
				),
			);

			return $shortcode;
		}

		/**
		 * Tabs Shortcode
		 */
		public function jeg_tabs( $atts, $content = null ) {
			$body   = $heading = array();
			$output = '';

			do_shortcode( $content );

			if ( isset( $this->tabs_count ) && is_array( $this->tabs ) ) {
				$count = 0;

				foreach ( $this->tabs as $tab ) {
					$count ++;

					$tab_class = ( $count == 1 ? 'vc_active' : '' );

					$tab_pane_class = ( $count == 1 ? ' class="active tab-pane"' : ' class="tab-pane"' );

					$heading[] =
							'<li class="vc_tta-tab ' . $tab_class . '" data-vc-tab>
                                <a href="#' . $tab['id'] . '" data-vc-tabs data-vc-container=".vc_tta">
                                    <span class="vc_tta-title-text">' . $tab['title'] . '</span>
                                </a>
                            </li>';

					$body[] =
						'<div class="vc_tta-panel ' . $tab_class . '" id="' . $tab['id'] . '" data-vc-content=".vc_tta-panel-body">
                            <div class="vc_tta-panel-heading">
                                <h4 class="vc_tta-panel-title">
                                    <a href="#' . $tab['id'] . '" data-vc-accordion data-vc-container=".vc_tta-container">
                                        <span class="vc_tta-title-text">' . $tab['title'] . '</span>
                                    </a>
                                </h4>
                            </div>
                            <div class="vc_tta-panel-body">
                                <div class="wpb_text_column wpb_content_element ">
                                    <div class="wpb_wrapper">
                                        ' . $tab['content'] . '
                                    </div>
                                </div>
                            </div>
                        </div>';
				}

				$output =
					'<div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-grey vc_tta-style-classic vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">
                                <ul class="vc_tta-tabs-list">' . implode( '', $heading ) . '</ul>
                            </div>
                            <div class="vc_tta-panels-container">
                                <div class="vc_tta-panels">
                                    ' . implode( "\n", $body ) . '
                                </div>
                            </div>
                        </div>
                    </div>';
			}

			$this->tabs_count = $this->tabs = null;

			return $output;
		}

		/**
		 * Tabs Shortcode
		 */
		public function jeg_tab( $atts, $content = null ) {
			if ( is_null( $this->tabs_count ) ) {
				$this->tabs_count = 0;
			}

			$atts = shortcode_atts(
				array(
					'title' => 'Tab %d',
				),
				$atts
			);

			$this->tabs[ $this->tabs_count ] = array(
				'title'   => sprintf( $atts['title'], $this->tabs_count ),
				'content' => do_shortcode( $content ),
				'id'      => 'tab-' . uniqid(),
			);

			$this->tabs_count ++;
		}

		/**
		 * Accordions Shortcode
		 */
		public function jeg_accordions( $atts, $content = null ) {
			$output =
				'<div class="vc_tta-container" data-vc-action="collapse">
                    <div class="vc_general vc_tta vc_tta-accordion vc_tta-color-grey vc_tta-style-classic vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-controls-align-left">
                        <div class="vc_tta-panels-container">
                            <div class="vc_tta-panels">' . do_shortcode( $content ) . '</div>
                        </div>
                    </div>
                </div>';

			return $output;
		}

		/**
		 * Accordion Shortcode
		 */
		public function jeg_accordion( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'title' => '',
					'load'  => 'hide',
				),
				$atts
			);

			$accordion_id = 'accordion-' . uniqid();
			$atts['load'] = ( $atts['load'] == 'show' ) ? 'vc_active' : '';

			$output =
				'<div class="vc_tta-panel ' . $atts['load'] . '" id="' . $accordion_id . '" data-vc-content=".vc_tta-panel-body">
                    <div class="vc_tta-panel-heading">
                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left">
                            <a href="#' . $accordion_id . '" data-vc-accordion data-vc-container=".vc_tta-container">
                                <span class="vc_tta-title-text">' . $atts['title'] . '</span>
                                <i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="vc_tta-panel-body">
                        <div class="wpb_text_column wpb_content_element ">
                            <div class="wpb_wrapper">
                                ' . do_shortcode( $content ) . '
                            </div>
                        </div>
                    </div>
                </div>';

			return $output;
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
			if ( isset( $atts['bgs_gallery_type'] ) && $atts['bgs_gallery_type'] === 'slider' ) {
				$out['jnewsslider']       = true;
				$out['jnewsslider_title'] = isset( $atts['bgs_gallery_title'] ) ? $atts['bgs_gallery_title'] : '';
			}

			return $out;
		}
	}
}
