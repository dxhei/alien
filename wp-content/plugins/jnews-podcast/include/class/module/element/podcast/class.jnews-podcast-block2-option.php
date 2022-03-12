<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast\Podcast_Option_Abstract;

/**
 * Class JNews_Podcast_Blockpodcast2_Option
 */
class JNews_Podcast_Blockpodcast2_Option extends Podcast_Option_Abstract {
	protected $default_number_post = 6;
	protected $show_excerpt        = true;
	protected $default_ajax_post   = 6;

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Podcast Block 2', 'jnews-podcast' );
	}

	/**
	 * Set element option
	 */
	public function set_options() {
		parent::set_options();
		foreach ( $this->options as $idx => $options ) {
			if ( 'excerpt_length' === $options['param_name'] ) {
				$this->options[ $idx ]['std'] = 16;
			}
		}
	}
}
