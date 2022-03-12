<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_JMagz_Shortcode' ) ) {
	class JNews_Migration_JMagz_Shortcode {

		/**
		 * @var JNews_Migration_JMagz_Shortcode
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
		 * Accordion shortcode - panel id
		 *
		 * @var integer
		 */
		private $panelgroupid = 0;

		/**
		 * Accordion shortcode - element id
		 *
		 * @var integer
		 */
		private $uniqueid = 0;

		/**
		 * @return JNews_Migration_JMagz_Shortcode
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_JMagz_Shortcode constructor
		 */
		private function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

			add_filter( 'the_content', array( $this, 'the_content_filter' ) );
			add_filter( 'shortcode_atts_gallery', array( $this, 'migration_gallery_filter' ), 10, 3 );

			$this->register_shortcode();
		}

		/**
		 * Load shortcode css and js assest
		 */
		public function load_assets() {
			wp_enqueue_style( 'jnews-migration-jmagz-shortcode-style', JNEWS_MIGRATION_JMAGZ_URL . '/assets/css/shortcode.css', null, JNEWS_MIGRATION_JMAGZ_VERSION );

			wp_enqueue_script( 'jnews-migration-jmagz-shortcode-script', JNEWS_MIGRATION_JMAGZ_URL . '/assets/js/shortcode.js', array( 'jquery' ), JNEWS_MIGRATION_JMAGZ_VERSION );
			wp_enqueue_script( 'tooltipster' );
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
			if ( isset( $atts['jmagzslider'] ) ) {
				$out['jnewsslider'] = true;
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
					'name' => 'intro',
					'func' => 'jeg_intro',
				),
				array(
					'name' => 'row',
					'func' => 'jeg_row',
				),
				array(
					'name' => 'column',
					'func' => 'jeg_column',
				),
				array(
					'name' => 'dropcap',
					'func' => 'jeg_dropcap',
				),
				array(
					'name' => 'pullquote',
					'func' => 'jeg_pullquote',
				),
				array(
					'name' => 'highlight',
					'func' => 'jeg_highlight',
				),
				array(
					'name' => 'tooltip',
					'func' => 'jeg_tooltip',
				),
				array(
					'name' => 'spacing',
					'func' => 'jeg_spacing',
				),
				array(
					'name' => 'singleicon',
					'func' => 'jeg_singleicon',
				),
				array(
					'name' => 'iconlistwrapper',
					'func' => 'jeg_iconlist_wrapper',
				),
				array(
					'name' => 'iconlist',
					'func' => 'jeg_iconlist',
				),
				array(
					'name' => 'googlemap',
					'func' => 'jeg_googlemap',
				),
				array(
					'name' => 'alert',
					'func' => 'jeg_alert',
				),
				array(
					'name' => 'button',
					'func' => 'jeg_button',
				),
				array(
					'name' => 'accordion',
					'func' => 'jeg_accordion',
				),
				array(
					'name' => 'accordion-element',
					'func' => 'jeg_accordion_element',
				),
				array(
					'name' => 'tab-heading',
					'func' => 'jeg_tab_heading',
				),
				array(
					'name' => 'tab-heading-wrapper',
					'func' => 'jeg_tab_heading_wrapper',
				),
				array(
					'name' => 'tab-content',
					'func' => 'jeg_tab_content',
				),
				array(
					'name' => 'tab-content-wrapper',
					'func' => 'jeg_tab_content_wrapper',
				),
			);

			return $shortcode;
		}

		/**
		 * Clean up shortcode
		 *
		 * @param  string $content
		 *
		 * @return string
		 */
		public function the_content_filter( $content ) {
			$block = join(
				'|',
				array(
					'relatedpost',
					'row',
					'column',
					'intro',
					'spacing',
					'singleicon',
					'iconlistwrapper',
					'iconlist',
					'googlemap',
					'alert',
					'button',
					'accordion',
					'accordion-element',
					'tab-heading-wrapper',
					'tab-heading',
					'tab-content-wrapper',
					'tab-content',
				)
			);

			$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", '[$2$3]', $content );
			$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", '[/$2]', $rep );

			return $rep;
		}

		/**
		 * Intro shortcode
		 */
		public function jeg_intro( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);

			return "<p class='migration-intro-text {$atts['class']}'>"
						. do_shortcode( $content ) .
					'</p>';
		}

		/**
		 * Row shortcode
		 */
		public function jeg_row( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);

			return "<div class='row clearfix {$atts['class']}'>"
						. do_shortcode( $content ) .
					'</div>';
		}

		/**
		 * Column shortcode
		 */
		public function jeg_column( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'size'   => '',
					'offset' => '0',
					'class'  => '',
				),
				$atts
			);

			return "<div class='col-md-{$atts['size']} {$atts['class']} col-xs-offset-{$atts['offset']}'>"
						. do_shortcode( $content ) .
					'</div>';
		}

		/**
		 * Dropcap shortcode
		 */
		public function jeg_dropcap( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);

			return "<span class='migration-dropcaps {$atts['class']} '>"
						. do_shortcode( $content ) .
					'</span>';
		}

		/**
		 * Pull quote shortcode
		 */
		public function jeg_pullquote( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'position' => '',
					'class'    => '',
				),
				$atts
			);

			return "<blockquote class='migration-pullquote-{$atts['position']} {$atts['class']}'>
                        <span>" . do_shortcode( $content ) . '</span>
                    </blockquote>';
		}

		/**
		 * Highlight shortcode
		 */
		public function jeg_highlight( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'text_color' => '#fff',
					'bg_color'   => '#000',
					'class'      => '',
				),
				$atts
			);

			return "<span class='migration-highlight {$atts['class']}' style='background-color: {$atts['bg_color']}; color: {$atts['text_color']};'>"
						. do_shortcode( $content ) .
					'</span>';
		}

		/**
		 * Tooltip shortcode
		 */
		public function jeg_tooltip( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'text'  => '',
					'url'   => '',
					'class' => '',
				),
				$atts
			);

			return "<a data-original-title='{$atts['text']}' href='{$atts['url']}' data-toggle='tooltip' data-animation='fade' class=' {$atts['class']}'>"
						. do_shortcode( $content ) .
					'</a>';
		}

		/**
		 * Spacing shortcode
		 */
		public function jeg_spacing( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'size'  => '10',
					'class' => '',
				),
				$atts
			);

			return "<div class='clearfix {$atts['class']}' style='padding-bottom: {$atts['size']}px'></div>";
		}

		/**
		 * Single icon shortcode
		 */
		public function jeg_singleicon( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'id'    => '',
					'color' => '',
					'size'  => '',
					'class' => '',
				),
				$atts
			);

			$additionalstyle = '';

			if ( ! empty( $atts['color'] ) ) {
				$additionalstyle .= "color : {$atts['color']};";
			}
			if ( ! empty( $atts['size'] ) ) {
				$additionalstyle .= "font-size : {$atts['size']}em;";
			}

			return "<i class='fa {$atts['class']} {$atts['id']}' style='{$additionalstyle}'></i>";
		}

		/**
		 * Icon list wrapper shortcode
		 */
		public function jeg_iconlist_wrapper( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);

			return "<ul class='fa-ul {$atts['class']}'>"
						. do_shortcode( $content ) .
					'</ul>';
		}

		/**
		 * Icon list shortcode
		 */
		public function jeg_iconlist( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
					'id'    => '',
					'spin'  => '',
					'color' => '',
				),
				$atts
			);

			$additionalstyle = '';

			if ( ! empty( $atts['color'] ) ) {
				$additionalstyle .= "color : {$atts['color']};";
			}

			$spinclass = '';

			if ( $atts['spin'] === 'true' ) {
				$spinclass = 'fa-spin';
			}

			return "<li><i class='fa fa-fw {$spinclass} {$atts['id']}' style='$additionalstyle'></i> "
						. do_shortcode( $content ) .
					'</li>';
		}

		/**
		 * Google map shortcode
		 */
		public function jeg_googlemap( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'title' => '',
					'lat'   => '',
					'lng'   => '',
					'zoom'  => '14',
					'ratio' => '0.1',
					'popup' => '',
					'class' => '',
				),
				$atts
			);

			return "<div id='" . uniqid() . "' class='jrmap {$atts['class']}' data-lat='{$atts['lat']}' data-lng='{$atts['lng']}' data-zoom='{$atts['zoom']}' data-ratio='{$atts['ratio']}' data-showpopup='{$atts['popup']}' data-title='{$atts['title']}'><div class='contenthidden'>"
					. do_shortcode( $content ) .
				'</div></div>';
		}

		/**
		 * Alert shortcode
		 */
		public function jeg_alert( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'type'        => 'success',
					'main_text'   => '',
					'second_text' => '',
					'show_close'  => 'false',
					'class'       => '',
				),
				$atts
			);

			$closebutton = ( $atts['show_close'] === 'true' ) ? "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" : '';

			return "<div class='{$atts['class']} alert alert-{$atts['type']} alert-dismissable'>
                        {$closebutton}
                        <strong>{$atts['main_text']}</strong> {$atts['second_text']}
                    </div>";
		}

		/**
		 * Button shortcode
		 */
		public function jeg_button( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'type'         => 'default',
					'text'         => '',
					'url'          => '#',
					'open_new_tab' => 'false',
					'class'        => '',
				),
				$atts
			);

			$target = ( $atts['open_new_tab'] === 'true' ) ? 'target="_blank"' : '';

			return "<a href='{$atts['url']}' {$target} class='migration-btn {$atts['class']} migration-btn-{$atts['type']}'>{$atts['text']}</a>";
		}

		/**
		 * Accordion wrapper shortcode
		 */
		public function jeg_accordion( $atts, $content = null ) {
			$this->panelgroupid = $this->panelgroupid + 1;

			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);

			return "<div class='panel-group {$atts['class']}' id='panel_group_" . $this->panelgroupid . "'>"
						. do_shortcode( $content ) .
					'</div>';
		}

		/**
		 * Accordion element shortcode
		 */
		public function jeg_accordion_element( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'title'     => 'Accordion Title',
					'collapsed' => 'false',
					'class'     => '',
				),
				$atts
			);

			$this->uniqueid = $this->uniqueid + 1;

			$collapsed = ( $atts['collapsed'] === 'true' ) ? 'in' : '';

			return "<div class='panel panel-default {$atts['class']}'>
                        <div class='panel-heading'>
                            <h4 class='panel-title'>
                                <a class='accordion-toggle' data-toggle='collapse' data-parent='#panel_group_{$this->panelgroupid}' href='#accordion_{$this->uniqueid}'>
                                    {$atts['title']}
                                </a>
                            </h4>
                        </div>
                        <div id='accordion_{$this->uniqueid}' class='panel-collapse collapse {$collapsed}'>
                            <div class='panel-body'>
                                " . do_shortcode( $content ) . '
                            </div>
                        </div>
                    </div>';
		}

		/**
		 * Tab heading wrapper shortcode
		 */
		public function jeg_tab_heading_wrapper( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);
			return "<ul class='nav nav-tabs {$atts['class']}'>"
						. do_shortcode( $content ) .
					'</ul>';
		}

		/**
		 * Tab heading shortcode
		 */
		public function jeg_tab_heading( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'id'     => '',
					'active' => 'false',
					'title'  => '',
				),
				$atts
			);

			$active = ( $atts['active'] === 'true' ) ? 'active' : '';

			return "<li class='{$active }'><a href='#{$atts['id']}' data-toggle='tab'>{$atts['title']}</a></li>";
		}

		/**
		 * Tab content wrapper shortcode
		 */
		public function jeg_tab_content_wrapper( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'class' => '',
				),
				$atts
			);
			return "<div class='tab-content {$atts['class']}'>"
						. do_shortcode( $content ) .
					'</div>';
		}

		/**
		 * Tab content shortcode
		 */
		public function jeg_tab_content( $atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'id'     => '',
					'active' => 'false',
				),
				$atts
			);

			$active = ( $atts['active'] === 'true' ) ? ' in active ' : '';

			return "<div class='tab-pane fade {$active}' id='{$atts['id']}'><p>"
						. do_shortcode( $content ) .
					'</p></div>';
		}

	}
}
