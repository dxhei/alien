<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Episode\Episode_View_Abstract;


/**
 * Class JNews_Podcast_Blockepisode2_View
 */
class JNews_Podcast_Blockepisode2_View extends Episode_View_Abstract {

	/**
	 * @var string
	 */
	private $name = 'episode_2';

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
}
