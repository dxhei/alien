<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Series;

use Jeg\Form\Form_Archive;
use JNews\Archive\Builder\OptionAbstract;

/**
 * Class Option_Series
 *
 * @package JNEWS_PODCAST\Series
 */
class Option_Series extends OptionAbstract {

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @var string
	 */
	protected $slug;

	/**
	 * Option_Series constructor.
	 *
	 * @param $prefix
	 */
	public function __construct( $prefix ) {
		$this->slug   = $prefix;
		$this->prefix = 'jnews_' . $prefix . '_';
		$this->setup_hook();
	}

	/**
	 * Setup Option_Series hook
	 */
	protected function setup_hook() {
		$taxonomy = $this->slug;
		add_action( "{$taxonomy}_edit_form", array( $this, 'render_options' ) );
		add_action( "edit_{$taxonomy}", array( $this, 'save_series' ) );
	}

	/**
	 * Prepare segment to be loaded on Menu
	 *
	 * @return array
	 */
	public function prepare_segments() {
		$segments = array();

		$segments[] = array(
			'id'   => 'media-' . $this->slug . '-setting',
			'name' => esc_html__( 'Media Settings', 'jnews-podcast' ),
		);

		return $segments;
	}

	/**
	 * Save new category option
	 */
	public function save_series() {
		if ( isset( $_POST['taxonomy'] ) && $this->slug === sanitize_key( $_POST['taxonomy'] ) ) {
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
			'segment' => 'media-' . $this->slug . '-setting',
			'title'   => esc_html__( 'Image', 'jnews-podcast' ),
			'type'    => 'image',
		);

		$options['podcast_image'] = array(
			'segment' => 'media-' . $this->slug . '-setting',
			'title'   => esc_html__( 'Default Podcast Image', 'jnews-podcast' ),
			'type'    => 'image',
		);

		return $options;
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
