<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Module\Element\Podcast;

use \JNEWS_PODCAST\Module\Element\Podcast_Element_Option_Abstract;

/**
 * Class Podcast_Option_Abstract
 */
abstract class Podcast_Option_Abstract extends Podcast_Element_Option_Abstract {

	/**
	 * Set element option
	 */
	public function set_options() {
		$dependency = array(
			'element' => 'podcast_base_on',
			'value'   => array(
				'random',
				'alphabet_asc',
				'alphabet_desc',
				'id_asc',
				'id_desc',
				'count_asc',
				'count_desc',
				'include_asc',
				'include_desc',
				'most_comment_day',
				'most_comment_week',
				'most_comment_month',
				'popular_post_day',
				'popular_post_week',
				'popular_post_month',
				'popular_post',
				'popular_post_jetpack_day',
				'popular_post_jetpack_week',
				'popular_post_jetpack_month',
				'popular_post_jetpack_all',
			),
		);

		parent::set_options();
		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'podcast_base_on',
			'heading'     => esc_html__( 'Show Base On', 'jnews-podcast' ),
			'description' => esc_html__( 'Choose how module will show a podcast.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => 'random',
			'value'       => array(
				esc_html__( 'Random', 'jnews-podcast' ) => 'random',
				esc_html__( 'Alphabet Asc', 'jnews-podcast' ) => 'alphabet_asc',
				esc_html__( 'Alphabet Desc', 'jnews-podcast' ) => 'alphabet_desc',
				esc_html__( 'Podcast ID Asc', 'jnews-podcast' ) => 'id_asc',
				esc_html__( 'Podcast ID Desc', 'jnews-podcast' ) => 'id_desc',
				esc_html__( 'Total Episode Asc', 'jnews-podcast' ) => 'count_asc',
				esc_html__( 'Total Episode Desc', 'jnews-podcast' ) => 'count_desc',
				esc_html__( 'Include Podcast Option Asc', 'jnews-podcast' ) => 'include_asc',
				esc_html__( 'Include Podcast Option Desc', 'jnews-podcast' ) => 'include_desc',
				esc_html__( 'Most Comment (1 Day - View Counter)', 'jnews-podcast' ) => 'most_comment_day',
				esc_html__( 'Most Comment (7 Days - View Counter)', 'jnews-podcast' ) => 'most_comment_week',
				esc_html__( 'Most Comment (30 Days - View Counter)', 'jnews-podcast' ) => 'most_comment_month',
				esc_html__( 'Popular Episode (1 Day - View Counter)', 'jnews-podcast' ) => 'popular_post_day',
				esc_html__( 'Popular Episode (7 Days - View Counter)', 'jnews-podcast' ) => 'popular_post_week',
				esc_html__( 'Popular Episode (30 Days - View Counter)', 'jnews-podcast' ) => 'popular_post_month',
				esc_html__( 'Popular Episode (All Time - View Counter)', 'jnews-podcast' ) => 'popular_post',
				esc_html__( 'Popular Episode (1 Day - Jetpack)', 'jnews-podcast' ) => 'popular_post_jetpack_day',
				esc_html__( 'Popular Episode (7 Days - Jetpack)', 'jnews-podcast' ) => 'popular_post_jetpack_week',
				esc_html__( 'Popular Episode (30 Days - Jetpack)', 'jnews-podcast' ) => 'popular_post_jetpack_month',
				esc_html__( 'Popular Episode (All Time - Jetpack)', 'jnews-podcast' ) => 'popular_post_jetpack_all',
			),
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'unique_content_podcast',
			'heading'     => esc_html__( 'Include into Unique Content Group', 'jnews-podcast' ),
			'description' => esc_html__( 'Choose unique content option, and this module will be included into unique content group. It won\'t duplicate content across the group. Ajax loaded content won\'t affect this unique content feature.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => 'disable',
			'value'       => array(
				esc_html__( 'Disable', 'jnews-podcast' ) => 'disable',
				esc_html__( 'Unique Content - Group 1', 'jnews-podcast' ) => 'unique1',
				esc_html__( 'Unique Content - Group 2', 'jnews-podcast' ) => 'unique2',
				esc_html__( 'Unique Content - Group 3', 'jnews-podcast' ) => 'unique3',
				esc_html__( 'Unique Content - Group 4', 'jnews-podcast' ) => 'unique4',
				esc_html__( 'Unique Content - Group 5', 'jnews-podcast' ) => 'unique5',
			),
			'dependency'  => $dependency,
		);

		foreach ( $this->options as $idx => $options ) {
			if ( in_array( $options['param_name'], array( 'unique_content', 'sort_by', 'include_episode', 'exclude_episode', 'include_author', 'exclude_author', 'include_category', 'exclude_category', 'include_tag', 'exclude_tag' ), true ) ) {
				unset( $this->options[ $idx ] );
			}
		}
	}
}
