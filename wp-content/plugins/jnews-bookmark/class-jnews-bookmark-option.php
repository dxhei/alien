<?php
/**
 * Class JNews Bookmark Option
 *
 * @author : Jegtheme
 * @package JNews - Bookmark
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme JNews Option
 */
class JNews_Bookmark_Option {

	/**
	 * $instance variable
	 *
	 * @var JNews_Bookmark_Option
	 */
	private static $instance;

	/**
	 * $customizer variable
	 *
	 * @var Jeg\Customizer\Customizer
	 */
	private $customizer;

	/**
	 * Get Instance
	 *
	 * @return JNews_Bookmark_Option
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Construct
	 */
	public function __construct() {
		if ( class_exists( 'Jeg\Customizer\Customizer' ) ) {
			$this->customizer = Jeg\Customizer\Customizer::get_instance();

			$this->set_section();
		}
	}

	/**
	 * Set option section
	 */
	public function set_section() {
		$bookmark_section = array(
			'id'       => 'jnews_bookmark_section',
			'title'    => esc_html__( 'Bookmark Option', 'jnews-bookmark' ),
			'panel'    => 'jnews_single_post_panel',
			'priority' => 200,
			'type'     => 'jnews-lazy-section',
		);

		$this->customizer->add_section( $bookmark_section );
	}
}
