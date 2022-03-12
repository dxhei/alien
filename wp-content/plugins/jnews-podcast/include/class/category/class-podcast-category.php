<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Category;

/**
 * Class Podcast_Category
 *
 * @package JNEWS_PODCAST\Category
 */
class Podcast_Category {

	/**
	 * Instance of BuddyPress.
	 *
	 * @var Podcast_Category
	 */
	private static $instance;

	/**
	 * Podcast_Category constructor.
	 */
	private function __construct() {
		$this->setup_hook();
	}

	/**
	 * Setup Podcast_Category hook
	 */
	public function setup_hook() {
		if ( defined( 'JNEWS_OPTION_CUSTOMIZER' ) ) {
			add_action( 'before_override_option_category', array( $this, 'option_category_load' ) );
		} else {
			add_action( 'after_setup_theme', array( $this, 'option_category_load' ) );
		}
		$this->override_category_link();
	}

	public function override_category_link() {
		if ( jnews_podcast_option( 'override_category_link', false ) ) {
			add_filter( 'term_link', 'jnews_podcast_pre_category_link', 10, 3 );
		}
	}

	/**
	 * Singleton page for Podcast_Category Class
	 *
	 * @return Podcast_Category
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register new category option
	 */
	public function option_category_load() {
		Podcast_Option_Category::getInstance();
	}
}
