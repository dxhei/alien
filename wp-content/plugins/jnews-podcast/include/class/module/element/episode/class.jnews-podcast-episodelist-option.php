<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Episode\Episode_Option_Abstract;

/**
 * Class JNews_Podcast_Episodelist_Option
 */
class JNews_Podcast_Episodelist_Option extends Episode_Option_Abstract {

	protected $default_number_post = 5;
	protected $show_excerpt        = true;
	protected $default_ajax_post   = 2;

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Episode List', 'jnews-podcast' );
	}

	/**
	 * Set options
	 */
	public function set_options() {
		$this->set_header_option();
		$this->set_content_filter_option( $this->default_number_post );
		$this->set_content_setting_option( $this->show_excerpt );
		$this->set_ajax_filter_option( $this->default_ajax_post );
		$this->set_style_option();
		foreach ( $this->options as $idx => $options ) {
			if ( in_array( $options['param_name'], array( 'include_episode', 'exclude_episode', 'include_author', 'exclude_author', 'include_category', 'exclude_category', 'include_tag', 'exclude_tag' ), true ) ) {
				unset( $this->options[ $idx ] );
			}
		}
	}
}
