<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Soledad_Shortcode' ) ) {
	class JNews_Migration_Soledad_Shortcode {

		/**
		 * @var JNews_Migration_Soledad_Shortcode
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
		 * @return JNews_Migration_Soledad_Shortcode
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Soledad_Shortcode constructor
		 */
		private function __construct() {
			add_action( 'wp_print_styles', array( $this, 'load_assets' ) );

			$this->register_shortcode();
		}

		/**
		 * Load shortcode css and js assest
		 */
		public function load_assets() {
			wp_enqueue_style( 'jnews-migration-soledad-style', JNEWS_MIGRATION_SOLEDAD_URL . '/assets/css/shortcode.css', null, JNEWS_MIGRATION_SOLEDAD_VERSION );
		}

		public function get_shortcode_func() {
			return $this->prefix . $this->separator . $this->suffix;
		}

		/**
		 * Register shortcode
		 */
		public function register_shortcode() {
			remove_shortcode( 'penci_review' );
			remove_shortcode( 'penci_recipe' );

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
					'name' => 'penci_review',
					'func' => 'jeg_review',
				),
				array(
					'name' => 'penci_recipe',
					'func' => 'jeg_recipe',
				),
				array(
					'name' => 'columns',
					'func' => 'jeg_columns',
				),
				array(
					'name' => 'blockquote',
					'func' => 'jeg_blockquote',
				),
				array(
					'name' => 'icon',
					'func' => 'jeg_icon',
				),
			);

			return $shortcode;
		}

		/**
		 * Review Shortcode
		 */
		public function jeg_review( $atts, $content = null ) {
			return false;
		}

		/**
		 * Recipe Shortcode
		 */
		public function jeg_recipe( $atts, $content = null ) {
			return false;
		}

		/**
		 * Icon Shortcode
		 */
		public function jeg_icon( $atts, $content = null ) {
			extract(
				shortcode_atts(
					array(
						'name' => '',
					),
					$atts
				)
			);

			$return = '';

			if ( $name ) {
				$return .= '<i class="fa fa-' . $name . '"></i>';
			}

			return $return;
		}

		/**
		 * Blockquote Shortcode
		 */
		public function jeg_blockquote( $atts, $content = null ) {
			extract(
				shortcode_atts(
					array(
						'author' => '',
						'align'  => 'none',
					),
					$atts
				)
			);

			$author_html = '';
			if ( $author ) :
				$author_html = '<div class="author"><span>' . $author . '</span></div>';
endif;

			$return = '<div class="penci-pullqoute align-' . esc_attr( $align ) . '"><blockquote>' . $content . $author_html . '</blockquote></div>';

			return $return;
		}

		/**
		 * Columns Shortcode
		 */
		public function jeg_columns( $atts, $content = null ) {
			extract(
				shortcode_atts(
					array(
						'size' => '1/2',
						'last' => 'false',
					),
					$atts
				)
			);

			$col = array( 'penci-column' );

			$clearfix = '';
			if ( 'true' == $last ) {
				$col[]    = 'column-last';
				$clearfix = '<div class="clearfix"></div>';
			}

			if ( ! in_array( $size, array( '1/2', '1/3', '2/3', '1/4', '3/4' ) ) ) {
				 $size = '1/2';
			} else {
				$size = trim( $size );
			}

			$size  = str_replace( '/', '-', $size );
			$col[] = 'column-' . $size;

			$col = join( ' ', $col );

			$return  = '<div class="' . trim( $col ) . '">';
			$return .= do_shortcode( $content );
			$return .= '</div>' . $clearfix;

			return $return;
		}
	}
}
