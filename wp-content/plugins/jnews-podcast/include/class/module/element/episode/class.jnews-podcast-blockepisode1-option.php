<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Episode\Episode_Option_Abstract;

/**
 * Class JNews_Podcast_Blockepisode1_Option
 */
class JNews_Podcast_Blockepisode1_Option extends Episode_Option_Abstract {

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Podcast Block Episode 1', 'jnews-podcast' );
	}
}
