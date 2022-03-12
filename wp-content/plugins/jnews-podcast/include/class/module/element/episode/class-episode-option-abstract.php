<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Module\Element\Episode;

use \JNEWS_PODCAST\Module\Element\Podcast_Element_Option_Abstract;

/**
 * Class Episode_Option_Abstract
 */
abstract class Episode_Option_Abstract extends Podcast_Element_Option_Abstract {
	protected $default_number_post = 6;
	protected $default_ajax_post   = 6;

	/**
	 * Set options
	 */
	public function set_options() {
		$this->set_header_option();
		$this->set_content_filter_option( $this->default_number_post );
		$this->set_ajax_filter_option( $this->default_ajax_post );
		$this->set_style_option();
	}

	/**
	 * Set content filter
	 *
	 * @param int  $number
	 * @param bool $hide_number_post
	 */
	public function set_content_filter_option( $number = 10, $hide_number_post = false ) {

		$this->options[] = array(
			'type'        => 'select',
			'ajax'        => 'jnews_find_podcast',
			'options'     => 'jnews_get_podcast_option',
			'nonce'       => wp_create_nonce( 'jnews_find_podcast' ),
			'param_name'  => 'include_podcast_episode',
			'heading'     => esc_html__( 'Choose Podcast', 'jnews-podcast' ),
			'description' => esc_html__( 'Choose which podcast you want to show on this module.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => 'none',
		);

		parent::set_content_filter_option( $number, $hide_number_post );

		foreach ( $this->options as $idx => $options ) {
			if ( 'unique_content' === $options['param_name'] ) {
				unset( $this->options[ $idx ] );
			}
		}
	}

}
