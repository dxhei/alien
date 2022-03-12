<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast\Podcast_Option_Abstract;

/**
 * Class JNews_Podcast_Blockpodcast1_Option
 */
class JNews_Podcast_Blockpodcast1_Option extends Podcast_Option_Abstract {
	protected $default_number_post = 4;
	protected $default_ajax_post   = 4;

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Podcast Block 1', 'jnews-podcast' );
	}
}
