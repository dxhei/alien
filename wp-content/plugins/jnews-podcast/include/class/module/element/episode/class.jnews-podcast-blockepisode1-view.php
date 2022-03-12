<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Episode\Episode_View_Abstract;

/**
 * Class JNews_Podcast_Blockepisode1_View
 */
class JNews_Podcast_Blockepisode1_View extends Episode_View_Abstract {

	/**
	 * @var string
	 */
	private $name = 'episode_1';

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
}
