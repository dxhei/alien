<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_VIDEO\Module\Carousel;

use JNews\Module\Carousel\CarouselOptionAbstract;
use JNews\Util\Cache;

/**
 * Class Video_Carousel_Option_Abstract
 *
 * @package JNEWS_VIDEO\Module\Carousel
 */
abstract class Video_Carousel_Option_Abstract extends CarouselOptionAbstract {

	/**
	 * @var array
	 */
	private $custom_taxonomies;

	/**
	 * Set module option
	 */
	public function set_options() {
		$this->set_carousel_option();
		$this->set_header_option();
		$this->set_content_filter_option( $this->default_number );
		$this->set_setting_option();
		$this->set_style_option();

		if ( ! $this->custom_taxonomies = wp_cache_get( 'enable_custom_taxonomies', 'jnews-video' ) ) {
			$taxonomies = Cache::get_enable_custom_taxonomies();
			foreach ( $taxonomies as $key => $value ) {
				$this->custom_taxonomies[] = $key;
			}
			wp_cache_set( 'enable_custom_taxonomies', $this->custom_taxonomies, 'jnews-video' );
		}

		foreach ( $this->options as $idx => $options ) {
			if ( ! empty( $this->custom_taxonomies ) && in_array( $options['param_name'], $this->custom_taxonomies, true ) ) {
				unset( $this->options[ $idx ] );
			}

			if ( 'post_type' === $options['param_name'] || 'content_type' === $options['param_name'] ) {
				unset( $this->options[ $idx ] );
			}
			if ( 'include_category' === $options['param_name'] || 'exclude_category' === $options['param_name'] || 'include_tag' === $options['param_name'] || 'exclude_tag' === $options['param_name'] ) {
				if ( isset( $options['dependency'] ) ) {
					unset( $this->options[ $idx ]['dependency'] );
				}
			}
		}
	}

	/**
	 * Set header module option
	 */
	public function set_header_option() {

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'data_type',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Choose Data Type', 'jnews-video' ),
			'description' => esc_html__( 'Choose data for this block.', 'jnews-video' ),
			'std'         => 'custom',
			'value'       => array(
				esc_html__( 'User data', 'jnews-video' )   => 'user',
				esc_html__( 'Custom data', 'jnews-video' ) => 'custom',
			),
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'user_data',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Choose The User', 'jnews-video' ),
			'description' => esc_html__( 'Choose user that will be use as icon and title.', 'jnews-video' ),
			'value'       => jnews_get_all_author(),
			'dependency'  => array(
				'element' => 'data_type',
				'value'   => 'user',
			),
		);

		$this->options[] = array(
			'type'        => 'attach_image',
			'param_name'  => 'header_icon',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Header Icon', 'jnews-video' ),
			'description' => esc_html__( 'Choose an image for this block icon (recommend to use a square image).', 'jnews-video' ),
			'dependency'  => array(
				'element' => 'data_type',
				'value'   => 'custom',
			),
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'first_title',
			'holder'      => 'span',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Title', 'jnews-video' ),
			'description' => esc_html__( 'Main title of Module Block.', 'jnews-video' ),
			'dependency'  => array(
				'element' => 'data_type',
				'value'   => 'custom',
			),
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'section',
			'holder'      => 'span',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Section', 'jnews-video' ),
			'description' => esc_html__( 'Main title of Module Block.', 'jnews-video' ),
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'second_title',
			'holder'      => 'span',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Subtitle', 'jnews-video' ),
			'description' => esc_html__( 'Subtitle of Module Block.', 'jnews-video' ),
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'url',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Title URL', 'jnews-video' ),
			'description' => esc_html__( 'Insert URL of heading title.', 'jnews-video' ),
			'dependency'  => array(
				'element' => 'data_type',
				'value'   => 'custom',
			),
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'section_url',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Section URL', 'jnews-video' ),
			'description' => esc_html__( 'Insert URL of heading section.', 'jnews-video' ),
		);
		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'follow_button',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Enable Follow Button', 'jnews-video' ),
			'description' => esc_html__( 'Check this option to enable follow button.', 'jnews-video' ),
		);
		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'follow_user',
			'group'       => esc_html__( 'Header', 'jnews-video' ),
			'heading'     => esc_html__( 'Choose The User to Follow', 'jnews-video' ),
			'description' => wp_kses(
				sprintf( __( "Choose user that will be followed and make sure you already install <a href='%s' target='_blank'>BuddyPress Follow</a> plugin.", 'jnews-video' ), 'https://wordpress.org/plugins/buddypress-followers/' ),
				wp_kses_allowed_html()
			),
			'value'       => jnews_get_all_author(),
			'dependency'  => array(
				'element' => 'follow_button',
				'value'   => 'true',
			),
		);
	}

	/**
	 * Set content filter module option
	 *
	 * @param int  $number
	 * @param bool $hide_number_post
	 */
	public function set_content_filter_option( $number = 10, $hide_number_post = false ) {
		$this->options[] = array(
			'type'       => 'checkbox',
			'param_name' => 'video_only',
			'heading'    => esc_html__( 'Show Video Only', 'jnews-video' ),
			'value'      => array( esc_html__( 'Show video only on this module.', 'jnews-video' ) => 'yes' ),
			'group'      => esc_html__( 'Content Filter', 'jnews-video' ),
			'std'        => 'yes',
		);
		if ( jnews_is_bp_active() ) {
			$this->options[] = array(
				'type'       => 'checkbox',
				'param_name' => 'bp_member_only',
				'heading'    => esc_html__( 'Show Base on BuddyPress Member', 'jnews-video' ),
				'value'      => array( esc_html__( 'Show post for this module base on BuddyPress member page.', 'jnews-video' ) => 'yes' ),
				'group'      => esc_html__( 'Content Filter', 'jnews-video' ),
			);
		}
		parent::set_content_filter_option( $number, $hide_number_post ); // TODO: Change the autogenerated stub.
	}

	/**
	 * Set general option
	 */
	public function set_setting_option() {

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'post_meta_style',
			'heading'     => esc_html__( 'Choose Post Meta Style', 'jnews-video' ),
			'description' => esc_html__( 'Choose which post meta style that fit with your block.', 'jnews-video' ),
			'group'       => esc_html__( 'Settings', 'jnews-video' ),
			'std'         => 'style_1',
			'value'       => array(
				esc_html__( 'Style 1', 'jnews-video' ) => 'style_1',
				esc_html__( 'Style 2', 'jnews-video' ) => 'style_2',
			),
		);

		$this->options[] = array(
			'type'       => 'checkbox',
			'param_name' => 'author_avatar',
			'heading'    => esc_html__( 'Show Avatar', 'jnews-video' ),
			'value'      => array( esc_html__( 'Show avatar on the post meta.', 'jnews-video' ) => 'yes' ),
			'group'      => esc_html__( 'Settings', 'jnews-video' ),
			'std'        => 'yes',
			'dependency' => array(
				'element' => 'post_meta_style',
				'value'   => array( 'style_1' ),
			),
		);
	}
}
