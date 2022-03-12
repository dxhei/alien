<?php
/**
 * JNews Tiktok Class
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */

namespace JNews\Tiktok\Customizer;

/**
 * Class Customizer
 *
 * @package JNews\Tiktok\Customizer
 */
class Customizer {
	/**
	 * @var Customizer
	 */
	private static $instance;

	/**
	 * @var
	 */
	private $customizer;

	/**
	 * Customizer constructor.
	 */
	private function __construct() {
		// actions.
		add_action( 'jeg_register_customizer_option', array( $this, 'customizer_option' ) );

		// filters.
		add_filter( 'jeg_register_lazy_section', array( $this, 'autoload_section' ) );
	}

	/**
	 * @return Customizer
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register Customizer using jeg-framework
	 */
	public function customizer_option() {
		if ( class_exists( 'Jeg\Customizer\Customizer' ) ) {
			$this->customizer = \Jeg\Customizer\Customizer::get_instance();
			$this->set_section();
		}
	}

	/**
	 * Add new section in the panel
	 */
	public function set_section() {
		$tiktok_feed_section = array(
			'id'       => 'jnews_tiktok_feed_section',
			'title'    => esc_html__( 'Tiktok Feed Setting', 'jnews-tiktok' ),
			'panel'    => 'jnews_social_panel',
			'priority' => 252,
			'type'     => 'jnews-lazy-section',
		);

		$this->customizer->add_section( $tiktok_feed_section );
	}

	/**
	 * Load Customizer Option
	 *
	 * @param $result
	 *
	 * @return mixed
	 */
	public function autoload_section( $result ) {
		$result['jnews_tiktok_feed_section'][] = JNEWS_TIKTOK_DIR . 'class/customizer/options/customizer-option.php';

		return $result;
	}
}
