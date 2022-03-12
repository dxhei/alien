<?php
/**
 * @author : Jegtheme
 */

use JNews\Module\ModuleViewAbstract;

class JNews_Video_Videoheader_View extends ModuleViewAbstract {
	public function render_module( $attr, $column_class ) {

		// Heading
		if ( 'user' === $attr['data_type'] ) {
			$author      = $attr['user_data'];
			$author_url  = get_author_posts_url( $author );
			$author_name = get_the_author_meta( 'display_name', $author );
			$title       = $author_name;
			$url         = $author_url;
			$avatar_args = array(
				'size' => 75,
			);
			$image_url   = get_avatar_url( $author, $avatar_args );
		} else {
			$title          = $attr['first_title'];
			$url            = $attr['url'];
			$header_icon_id = isset( $attr['header_icon']['id'] ) ? ( ! empty( $attr['header_icon']['id'] ) ? $attr['header_icon']['id'] : '' ) : ( ! empty( $attr['header_icon'] ) ? $attr['header_icon'] : '' );
			$image          = ! empty( $header_icon_id ) ? wp_get_attachment_image_src( $header_icon_id, 'jnews-75x75' ) : '';
			$image_url      = is_array( $image ) ? $image[0] : '';
		}
		/** Heading */
		$subtitle      = ! empty( $attr['second_title'] ) ? "<span class=\"subtitle\">{$attr['second_title']}</span>" : '';
		$heading_image = $image_url;
		$heading_icon  = ! empty( $heading_image ) ? "<img src='{$heading_image}' alt='{$attr['first_title']}' data-pin-no-hover=\"true\">" : '';
		$heading_icon  = ! empty( $attr['url'] ) ? "<a href='{$attr['url']}'>{$heading_icon}</a>" : $heading_icon;
		$section       = ! empty( $attr['section'] ) ? '<span ' . ( empty( $attr['section_url'] ) ? "class='tag_content'" : '' ) . " >{$attr['section']}</span>" : '';
		$section       = ! empty( $attr['section_url'] ) ? "<a href='{$attr['section_url']}' class='tag_content' >{$section}</a>" : $section;
		$heading_title = "<span>{$title}</span>";
		$heading_title = ! empty( $url ) ? "<a href='{$url}'>{$heading_title}</a>" : $heading_title;
		$heading_title = "<h3 class=\"jeg_block_title\">{$heading_title}</h3>";
		$follow_button = ! empty( $attr['follow_button'] ) ? ( ! empty( $attr['follow_user'] ) ? jnews_video_render_subscribe_member_actions( $attr['follow_user'] ) : '' ) : '';
		$follow_button = ! empty( $follow_button ) ? '<div class="follow-wrapper">' . $follow_button . '<div class="jnews-spinner" style="display: none"><i class="fa fa-spinner fa-pulse active"></i></div></div>' : '';
		$heading       = "<div class='jeg_video_block_heading'>{$heading_icon}{$heading_title}{$section}{$subtitle}{$follow_button}</div>";

		// Now Render Output
		$output =
			"<div {$this->element_id($attr)} class=\"jnews_video jeg_video_block_heading_wrapper {$this->unique_id} {$this->get_vc_class_name()} {$this->color_scheme()} {$attr['el_class']}\">
                    {$heading}
                </div>";

		return $output;
	}

}
