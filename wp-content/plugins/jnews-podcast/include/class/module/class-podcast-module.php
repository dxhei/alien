<?php

/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Module;

use JNEWS_PODCAST\Player\Player;

/**
 * Class Podcast_Module
 *
 * @package JNEWS_PODCAST\Module
 */
class Podcast_Module {


	/**
	 * Instance of Podcast_Module
	 *
	 * @var Podcast_Module
	 */
	private static $instance;

	/**
	 * Instance of Player
	 *
	 * @var Player
	 */
	private $player;

	/**
	 * @var string
	 */
	private $tag = 'jnews_podcast_archive_category_';

	/**
	 * @var array
	 */
	private $module_array = array();

	/**
	 * Podcast_Module constructor.
	 */
	public function __construct() {
		$this->player = Player::get_instance();
		$this->populate_module();
		add_action( 'jnews_module_widget', array( $this, 'register_module_widget' ) );
		add_action( 'jnews_module_elementor', array( $this, 'register_module_elementor' ) );
		add_action( 'jnews_ajax_get_episode_data', array( $this, 'get_episode_data' ) );
		add_action( 'jnews_ajax_get_episode_data_by_series', array( $this, 'get_episode_data_by_series' ) );
		add_filter( 'jnews_module_list', array( $this, 'module_element' ) );
		add_action( 'elementor/init', array( $this, 'register_group' ), 11 );
		$this->load_module();
		$this->do_shortcode();
	}

	/**
	 * Get list module
	 *
	 * @return array|mixed
	 */
	public function populate_module() {
		if ( empty( $this->module_array ) ) {
			$this->module_array = include JNEWS_PODCAST_CLASSPATH . 'module/modules.php';
		}

		return $this->module_array;
	}

	/**
	 * Load module podcast
	 */
	public function load_module() {
		$elements = $this->populate_module();
		foreach ( $elements as $element ) {
			// Content Layout.
			add_filter(
				'jnews_get_content_layout',
				function ( $value ) use ( $element ) {
					$mod_element = explode( '_', $value );
					if ( ! empty( $mod_element[3] ) ) {
						$alias = implode( '_', array( $mod_element[2], $mod_element[3] ) );
						if ( isset( $element['alias'] ) && $element['alias'] === $alias ) {
							$value = $element['name'];
						}
					}

					return $value;
				}
			);

			add_action(
				'jnews_load_all_module_option',
				function () use ( $element ) {
					require_once JNEWS_PODCAST_DIR . $element['view'];
					require_once JNEWS_PODCAST_DIR . $element['option'];
				}
			);
			add_filter(
				'jnews_module_elementor_get_option_class',
				function ( $option_class ) use ( $element ) {
					if ( $this->check_class( $option_class, $element['name'] ) ) {
						require_once JNEWS_PODCAST_DIR . $element['option'];

						return $element['name'] . '_Option';
					}

					return $option_class;
				}
			);
			add_filter(
				'jnews_module_elementor_get_view_class',
				function ( $view_class ) use ( $element ) {
					if ( $this->check_class( $view_class, $element['name'] ) ) {
						require_once JNEWS_PODCAST_DIR . $element['view'];

						return $element['name'] . '_View';
					}

					return $view_class;
				}
			);
		}
	}

	/**
	 * Check Module class
	 *
	 * @param $class
	 * @param $element_name
	 *
	 * @return bool
	 */
	private function check_class( $class, $element_name ) {
		$mod_class = explode( '\\', $class );
		if ( ! empty( $mod_class[4] ) ) {
			$mod         = explode( '_', $mod_class[4] );
			$mod_element = explode( '_', $element_name );
			if ( $mod[0] . '_' . $mod[1] === $mod_element[1] . '_' . $mod_element[2] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Register module video shortcode
	 */
	public function do_shortcode() {
		$elements = $this->populate_module();
		foreach ( $elements as $element ) {
			add_filter(
				'jnews_get_option_class_from_shortcode',
				function ( $class, $module ) use ( $element ) {
					if ( $module === $element['name'] ) {
						return $element['name'] . '_Option';
					}

					return $class;
				},
				null,
				2
			);
			add_filter(
				'jnews_get_view_class_from_shortcode',
				function ( $class, $module ) use ( $element ) {
					if ( $module === $element['name'] ) {
						return $element['name'] . '_View';
					}

					if ( str_replace( '_view', '', $module ) === strtolower( $element['name'] ) ) {
						require_once JNEWS_PODCAST_DIR . $element['view'];

						return $element['name'] . '_View';
					}

					return $class;
				},
				null,
				2
			);
			add_filter(
				'jnews_get_shortcode_name_from_option',
				function ( $module, $class ) use ( $element ) {
					if ( $class === $element['name'] . '_Option' ) {
						return strtolower( $element['name'] );
					}

					return $module;
				},
				null,
				2
			);
			add_action(
				'jnews_build_shortcode_' . strtolower( $element['name'] ) . '_view',
				function () use ( $element ) {
					require_once JNEWS_PODCAST_DIR . $element['view'];
					require_once JNEWS_PODCAST_DIR . $element['option'];
				}
			);
			add_filter(
				'jnews_get_shortcode_name_from_view',
				function ( $module ) use ( $element ) {
					if ( str_replace( '_view', '', $module ) === strtolower( $element['name'] ) ) {
						require_once JNEWS_PODCAST_DIR . $element['option'];
					}

					return $module;
				}
			);
		}
	}

	/**
	 * Singleton page of Podcast_Module class
	 *
	 * @return Podcast_Module
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register Elementor Group
	 */
	public function register_group() {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$groups = array(
				'jnews-podcast' => esc_html__( 'JNews - Podcast', 'jnews-podcast' ),
			);

			foreach ( $groups as $key => $value ) {
				\Elementor\Plugin::$instance->elements_manager->add_category( $key, array( 'title' => $value ) );
			}
		}
	}

	public function register_module_widget() {
		include JNEWS_PODCAST_CLASSPATH . 'module/module-widget.php';
	}

	public function register_module_elementor() {
		include JNEWS_PODCAST_CLASSPATH . 'module/module-elementor.php';
	}

	/**
	 * List module element
	 *
	 * @param $module
	 *
	 * @return mixed
	 */
	public function module_element( $module ) {
		$elements = $this->populate_module();
		foreach ( $elements as $element ) {
			$module[] = $element;
		}

		return $module;
	}

	/**
	 * ajax get podcast data
	 *
	 * @return mixed
	 */
	public function get_episode_data() {
		if ( isset( $_POST['post_id'] ) ) {
			$post_id = (int) sanitize_text_field( $_POST['post_id'] );
			$args    = array(
				'post_id' => $post_id,
			);
			$result  = $this->player->set_player_data( $args );
			if ( ! empty( $result ) ) {
				wp_send_json( $result );
			}
		}
		wp_send_json( false );
		die();
	}

	/**
	 * ajax get podcast data by series
	 *
	 * @return mixed
	 */
	public function get_episode_data_by_series() {
		if ( isset( $_POST['post_id'] ) ) {
			$data    = array();
			$post_id = (int) sanitize_text_field( $_POST['post_id'] );
			$podcast = get_posts(
				array(
					'post_type'   => 'post',
					'numberposts' => - 1,
					'tax_query'   => array(
						array(
							'taxonomy' => 'jnews-series',
							'field'    => 'term_id',
							'terms'    => $post_id,
						),
					),
				)
			);
			if ( $podcast ) {
				foreach ( $podcast as $key => $value ) {
					$data[] = array(
						'post_id' => $value->ID,
					);
				}
				$result = $this->player->set_player_data( $data );
				if ( ! empty( $result ) ) {
					wp_send_json( $result );
				}
			}
		}
		wp_send_json( false );
		die();
	}
}
