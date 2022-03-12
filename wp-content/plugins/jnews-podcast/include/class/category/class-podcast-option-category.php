<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Category;

use JNews\Archive\Builder\OptionAbstract;

/**
 * Class Podcast_Option_Category
 *
 * @package JNEWS_PODCAST\Category
 */
class Podcast_Option_Category extends OptionAbstract {

	/**
	 * @var string
	 */
	protected $prefix = 'jnews_category_';

	/**
	 * Prepare segment to be loaded on Menu
	 *
	 * @return array
	 */
	public function prepare_segments() {
		$segments = array();

		$segments[] = array(
			'id'   => 'media-category-setting',
			'name' => esc_html__( 'Media Settings', 'jnews-option-category' ),
		);

		return $segments;
	}

	/**
	 * Save new category option
	 */
	public function save_category() {
		if ( isset( $_POST['taxonomy'] ) && 'category' === sanitize_key( $_POST['taxonomy'] ) ) {
			$options = $this->get_options();
			$this->do_save( $options, (int) sanitize_text_field( $_POST['tag_ID'] ) );
		}
	}

	/**
	 * Get new category option
	 *
	 * @return array
	 */
	protected function get_options() {
		$options = array();

		$options['term_image'] = array(
			'segment' => 'media-category-setting',
			'title'   => esc_html__( 'Image', 'jnews-option-category' ),
			'type'    => 'image',
		);

		return $options;
	}

	/**
	 * Setup Podcast_Option_Category hook
	 */
	protected function setup_hook() {
		if ( ! defined( 'JNEWS_VIDEO' ) ) {
			$taxonomy = 'category';
			add_action( "{$taxonomy}_edit_form", array( $this, 'render_options' ) );
			add_action( "edit_{$taxonomy}", array( $this, 'save_category' ) );
		}
	}

	/**
	 * Get term id
	 *
	 * @param $tag
	 *
	 * @return null|int
	 */
	protected function get_id( $tag ) {
		if ( ! empty( $tag->term_id ) ) {
			return $tag->term_id;
		}

		return null;
	}
}
