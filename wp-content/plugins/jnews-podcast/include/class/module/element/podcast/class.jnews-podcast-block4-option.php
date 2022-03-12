<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast\Podcast_Option_Abstract;

/**
 * Class JNews_Podcast_Blockpodcast4_Option
 */
class JNews_Podcast_Blockpodcast4_Option extends Podcast_Option_Abstract {
	protected $default_number_post = 3;
	protected $show_excerpt        = true;
	protected $default_ajax_post   = 3;

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Podcast Block 4', 'jnews-podcast' );
	}
}
