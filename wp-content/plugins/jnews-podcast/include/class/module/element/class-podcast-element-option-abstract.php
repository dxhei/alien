<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Module\Element;

use JNews\Module\Block\BlockOptionAbstract;

/**
 * Class Podcast_Element_Option_Abstract
 *
 * @package JNEWS_PODCAST\Module\Element
 */
abstract class Podcast_Element_Option_Abstract extends BlockOptionAbstract {

	protected $default_number_post = 4;
	protected $show_excerpt        = false;
	protected $default_ajax_post   = 4;

	/**
	 * @return string
	 */
	public function get_category() {
		return esc_html__( 'JNews - Podcast', 'jnews-podcast' );
	}

	public function set_options() {
		$this->set_header_option();
		$this->set_content_filter_option( $this->default_number_post );
		$this->set_content_setting_option( $this->show_excerpt );
		$this->set_ajax_filter_option( $this->default_ajax_post );
		$this->set_style_option();

		foreach ( $this->options as $idx => $options ) {
			if ( in_array( $options['param_name'], array( 'date_format', 'date_format_custom', '' ) ) ) {
				unset( $this->options[ $idx ] );
			}
		}
	}

	/**
	 * @param int  $number
	 * @param bool $hide_number_post
	 */
	public function set_content_filter_option( $number = 10, $hide_number_post = false ) {
		$dependency = array(
			'element' => 'sort_by',
			'value'   => array(
				'post_type',
				'latest',
				'oldest',
				'alphabet_asc',
				'alphabet_desc',
				'random',
				'random_week',
				'random_month',
				'most_comment',
				'most_comment_day',
				'most_comment_week',
				'most_comment_month',
				'popular_post_day',
				'popular_post_week',
				'popular_post_month',
				'popular_post',
				'rate',
				'like',
				'share',
			),
		);

		if ( ! $hide_number_post ) {
			$this->options[] = array(
				'type'        => 'slider',
				'param_name'  => 'number_post',
				'heading'     => esc_html__( 'Number of Podcast', 'jnews-podcast' ),
				'description' => esc_html__( 'Show number of podcast on this module.', 'jnews-podcast' ),
				'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
				'min'         => 1,
				'max'         => 30,
				'step'        => 1,
				'std'         => $number,
			);
		}

		$this->options[] = array(
			'type'        => 'number',
			'param_name'  => 'post_offset',
			'heading'     => esc_html__( 'Podcast Offset', 'jnews-podcast' ),
			'description' => esc_html__( 'Number of podcast offset (start of content).', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'min'         => 0,
			'max'         => 9999,
			'step'        => 1,
			'std'         => 0,
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'unique_content',
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

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'sort_by',
			'heading'     => esc_html__( 'Sort by', 'jnews-podcast' ),
			'description' =>
				wp_kses( __( 'Sort post by this option<br/>* <strong>View Counter :</strong> Need <strong>JNews View Counter</strong> plugin enabled.<br/>* <strong>Jetpack :</strong> Need <strong>Jetpack</strong> plugin & Stat module enabled.<br/>* Like and share only count real like and share.', 'jnews-podcast' ), wp_kses_allowed_html() ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => 'latest',
			'value'       => array(
				esc_html__( 'Latest Episode - Published Date', 'jnews-podcast' )           => 'latest',
				esc_html__( 'Latest Episode - Modified Date', 'jnews-podcast' )            => 'latest_modified',
				esc_html__( 'Oldest Episode - Published Date', 'jnews-podcast' )           => 'oldest',
				esc_html__( 'Oldest Episode - Modified Date', 'jnews-podcast' )            => 'oldest_modified',
				esc_html__( 'Alphabet Asc', 'jnews-podcast' )                              => 'alphabet_asc',
				esc_html__( 'Alphabet Desc', 'jnews-podcast' )                             => 'alphabet_desc',
				esc_html__( 'Random Episode', 'jnews-podcast' )                            => 'random',
				esc_html__( 'Random Episode (7 Days)', 'jnews-podcast' )                   => 'random_week',
				esc_html__( 'Random Episode (30 Days)', 'jnews-podcast' )                  => 'random_month',
				esc_html__( 'Most Comment', 'jnews-podcast' )                              => 'most_comment',
				esc_html__( 'Most Comment (1 Day - View Counter)', 'jnews-podcast' )       => 'most_comment_day',
				esc_html__( 'Most Comment (7 Days - View Counter)', 'jnews-podcast' )      => 'most_comment_week',
				esc_html__( 'Most Comment (30 Days - View Counter)', 'jnews-podcast' )     => 'most_comment_month',
				esc_html__( 'Popular Episode (1 Day - View Counter)', 'jnews-podcast' )    => 'popular_post_day',
				esc_html__( 'Popular Episode (7 Days - View Counter)', 'jnews-podcast' )   => 'popular_post_week',
				esc_html__( 'Popular Episode (30 Days - View Counter)', 'jnews-podcast' )  => 'popular_post_month',
				esc_html__( 'Popular Episode (All Time - View Counter)', 'jnews-podcast' ) => 'popular_post',
				esc_html__( 'Popular Episode (1 Day - Jetpack)', 'jnews-podcast' )         => 'popular_post_jetpack_day',
				esc_html__( 'Popular Episode (7 Days - Jetpack)', 'jnews-podcast' )        => 'popular_post_jetpack_week',
				esc_html__( 'Popular Episode (30 Days - Jetpack)', 'jnews-podcast' )       => 'popular_post_jetpack_month',
				esc_html__( 'Popular Episode (All Time - Jetpack)', 'jnews-podcast' )      => 'popular_post_jetpack_all',
				esc_html__( 'Most Like (Thumb up)', 'jnews-podcast' )                      => 'like',
				esc_html__( 'Most Share', 'jnews-podcast' )                                => 'share',
			),
		);
		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jnews_podcast_find_episode',
			'options'     => 'jnews_podcast_get_episode_option',
			'nonce'       => wp_create_nonce( 'jnews_podcast_find_episode' ),
			'param_name'  => 'include_episode',
			'heading'     => esc_html__( 'Include Episode ID', 'jnews-podcast' ),
			'description' => wp_kses( __( 'Tips :<br/> - You can search episode id by inputing title, clicking search title, and you will have your episode id.<br/>- You can also directly insert your episode id, and click enter to add it on the list.', 'jnews-podcast' ), wp_kses_allowed_html() ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
			'dependency'  => $dependency,
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jnews_podcast_find_episode',
			'options'     => 'jnews_podcast_get_episode_option',
			'nonce'       => wp_create_nonce( 'jnews_podcast_find_episode' ),
			'param_name'  => 'exclude_episode',
			'heading'     => esc_html__( 'Exclude Episode ID', 'jnews-podcast' ),
			'description' => wp_kses( __( 'Tips :<br/> - You can search episode id by inputing title, clicking search title, and you will have your episode id.<br/>- You can also directly insert your episode id, and click enter to add it on the list.', 'jnews-podcast' ), wp_kses_allowed_html() ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
			'dependency'  => $dependency,
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'param_name'  => 'include_category',
			'heading'     => esc_html__( 'Include Category', 'jnews-podcast' ),
			'description' => esc_html__( 'Choose which category you want to show on this module.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'param_name'  => 'exclude_category',
			'heading'     => esc_html__( 'Exclude Category', 'jnews-podcast' ),
			'description' => esc_html__( 'Choose excluded category for this modules.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_author',
			'options'     => 'jeg_get_author_option',
			'nonce'       => wp_create_nonce( 'jeg_find_author' ),
			'param_name'  => 'include_author',
			'heading'     => esc_html__( 'Author', 'jnews-podcast' ),
			'description' => esc_html__( 'Write to search post author.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
			'dependency'  => $dependency,
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_tag',
			'options'     => 'jeg_get_tag_option',
			'nonce'       => wp_create_nonce( 'jeg_find_tag' ),
			'param_name'  => 'include_tag',
			'heading'     => esc_html__( 'Include Tags', 'jnews-podcast' ),
			'description' => esc_html__( 'Write to search post tag.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_tag',
			'options'     => 'jeg_get_tag_option',
			'nonce'       => wp_create_nonce( 'jeg_find_tag' ),
			'param_name'  => 'exclude_tag',
			'heading'     => esc_html__( 'Exclude Tags', 'jnews-podcast' ),
			'description' => esc_html__( 'Write to search post tag.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jnews_podcast_find_podcast',
			'options'     => 'jnews_podcast_get_podcast_option',
			'nonce'       => wp_create_nonce( 'jnews_podcast_find_podcast' ),
			'param_name'  => 'include_podcast',
			'heading'     => esc_html__( 'Include Podcast', 'jnews-podcast' ),
			'description' => esc_html__( 'Write to search podcast.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);

		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jnews_podcast_find_podcast',
			'options'     => 'jnews_podcast_get_podcast_option',
			'nonce'       => wp_create_nonce( 'jnews_podcast_find_podcast' ),
			'param_name'  => 'exclude_podcast',
			'heading'     => esc_html__( 'Exclude Podcast', 'jnews-podcast' ),
			'description' => esc_html__( 'Write to search podcast.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);
	}

	public function set_style_option() {
		$this->set_boxed_option();
		parent::set_style_option();
	}

	/**
	 * Additional Style
	 */
	public function additional_style() {
		parent::additional_style();

		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'play_button_icon',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'Play Button Icon Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the play button icon color', 'jnews-podcast' ),
		);
		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'play_button_text',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'Play / Subscribe Button Text Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the play button text color', 'jnews-podcast' ),
		);
		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'play_button_border',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'Play / Subscribe Button Border Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the play button border color', 'jnews-podcast' ),
		);
		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'play_button_background',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'Play / Subscribe Button Background Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the play button background color', 'jnews-podcast' ),
		);

		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'more_button_icon',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'More Button Icon Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the more button icon color', 'jnews-podcast' ),
		);
		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'more_button_border',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'More Button Border Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the more button border color', 'jnews-podcast' ),
		);
		$this->options[] = array(
			'type'        => 'colorpicker',
			'param_name'  => 'more_button_background',
			'group'       => esc_html__( 'Design', 'jnews-podcast' ),
			'heading'     => esc_html__( 'More Button Background Color', 'jnews-podcast' ),
			'description' => esc_html__( 'Change the more button background color', 'jnews-podcast' ),
		);
	}

	/**
	 * @param \Elementor\Sub_Controls_Stack $instance
	 *
	 * @return bool|void
	 */
	public function set_typography_option( $instance ) {

		$instance->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'        => 'title_typography',
				'label'       => esc_html__( 'Title Typography', 'jnews-podcast' ),
				'description' => esc_html__( 'Set typography for post title', 'jnews-podcast' ),
				'selector'    => '{{WRAPPER}} .jeg_post_title > a',
			)
		);

		$instance->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'        => 'meta_typography',
				'label'       => esc_html__( 'Meta Typography', 'jnews-podcast' ),
				'description' => esc_html__( 'Set typography for post meta', 'jnews-podcast' ),
				'selector'    => '{{WRAPPER}} .jeg_post_meta, {{WRAPPER}} .jeg_post_meta .fa, {{WRAPPER}}.jeg_postblock .jeg_subcat_list > li > a:hover, {{WRAPPER}} .jeg_pl_md_card .jeg_post_category a, {{WRAPPER}}.jeg_postblock .jeg_subcat_list > li > a.current, {{WRAPPER}} .jeg_pl_md_5 .jeg_post_meta, {{WRAPPER}} .jeg_pl_md_5 .jeg_post_meta .fa, {{WRAPPER}} .jeg_post_category a',
			)
		);
	}
}
