<?php

use JNews\Module\ModuleOptionAbstract;

/**
 * JNews Tiktok Element
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */
class JNews_Footer_Tiktok_Option extends ModuleOptionAbstract {

	public function compatible_column() {
		return array( 4, 8, 12 );
	}

	public function get_module_name() {
		return esc_html__( 'JNews - Horizontal Tiktok', 'jnews-tiktok' );
	}

	public function get_category() {
		return esc_html__( 'JNews - Footer', 'jnews-tiktok' );
	}

	public function set_options() {
		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'footer_tiktok_type',
			'heading'     => esc_html__( 'Fetch Type', 'jnews-tiktok' ),
			'description' => esc_html__( 'Select feed type that you want to use.', 'jnews-tiktok' ),
			'std'         => 'username',
			'value'       => array(
				esc_attr__( 'Tiktok Username', 'jnews-tiktok' ) => 'username',
				esc_attr__( 'Tiktok Hastag', 'jnews-tiktok' )   => 'hastag',
			),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'footer_tiktok_username',
			'holder'      => 'span',
			'heading'     => esc_html__( 'Tiktok Username', 'jnews-tiktok' ),
			'description' => esc_html__( 'Insert your Tiktok username (without @)', 'jnews-tiktok' ),
			'dependency'  => array(
				'element' => 'footer_tiktok_type',
				'value'   => array( 'username' ),
			),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'footer_tiktok_hastag',
			'holder'      => 'span',
			'heading'     => esc_html__( 'Tiktok Hastag', 'jnews-tiktok' ),
			'description' => esc_html__( 'Insert hastag you want to add (without #).', 'jnews-tiktok' ),
			'dependency'  => array(
				'element' => 'footer_tiktok_type',
				'value'   => array( 'hastag' ),
			),
		);

		$this->options[] = array(
			'type'        => 'slider',
			'param_name'  => 'footer_tiktok_row',
			'heading'     => esc_html__( 'Number Of Rows', 'jnews-tiktok' ),
			'description' => esc_html__( 'Number of rows for footer Tiktok feed.', 'jnews-tiktok' ),
			'min'         => 1,
			'max'         => 2,
			'step'        => 1,
			'std'         => 1,
		);

		$this->options[] = array(
			'type'        => 'slider',
			'param_name'  => 'footer_tiktok_column',
			'heading'     => esc_html__( 'Number Of Columns', 'jnews-tiktok' ),
			'description' => esc_html__( 'Number of Tiktok feed columns.', 'jnews-tiktok' ),
			'min'         => 5,
			'max'         => 10,
			'step'        => 1,
			'std'         => 8,
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'footer_tiktok_sort_type',
			'heading'     => esc_html__( 'Sort Feed Type', 'jnews-tiktok' ),
			'description' => esc_html__( 'Sort the Tiktok feed in a set order.', 'jnews-tiktok' ),
			'std'         => 'most_recent',
			'value'       => array(
				esc_attr__( 'Most Recent', 'jnews-tiktok' ) => 'most_recent',
				esc_attr__( 'Least Recent', 'jnews-tiktok' ) => 'least_recent',
				esc_attr__( 'Most Liked', 'jnews-tiktok' ) => 'most_like',
				esc_attr__( 'Least Liked', 'jnews-tiktok' ) => 'least_like',
				esc_attr__( 'Most Commented ', 'jnews-tiktok' ) => 'most_comment',
				esc_attr__( 'Least Commented ', 'jnews-tiktok' ) => 'least_comment',
			),
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'footer_tiktok_hover_style',
			'heading'     => esc_html__( 'Hover Style', 'jnews-tiktok' ),
			'description' => esc_html__( 'Choose hover effect style.', 'jnews-tiktok' ),
			'std'         => 'zoom',
			'value'       => array(
				esc_attr__( 'Normal', 'jnews-tiktok' )    => 'normal',
				esc_attr__( 'Show Icon', 'jnews-tiktok' ) => 'icon',
				esc_attr__( 'Show Like Count', 'jnews-tiktok' ) => 'like',
				esc_attr__( 'Show Comment Count', 'jnews-tiktok' ) => 'comment',
				esc_attr__( 'Zoom', 'jnews-tiktok' )      => 'zoom',
				esc_html__( 'Zoom Rotate', 'jnews-tiktok' ) => 'zoom-rotate',
				esc_attr__( 'No Effect', 'jnews-tiktok' ) => ' ',
			),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'footer_tiktok_view_button',
			'heading'     => esc_html__( 'View Button Text', 'jnews-tiktok' ),
			'description' => esc_html__( 'Leave empty if you wont show it', 'jnews-tiktok' ),
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'footer_tiktok_layout',
			'heading'     => esc_html__( 'Layout type', 'jnews-tiktok' ),
			'description' => esc_html__( 'Choose layout type.', 'jnews-tiktok' ),
			'std'         => 'rectangle',
			'value'       => array(
				esc_attr__( 'Rectangle', 'jnews-tiktok' ) => 'rectangle',
				esc_attr__( 'Square', 'jnews-tiktok' )    => 'square',
			),
		);

		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'footer_tiktok_cover',
			'heading'     => esc_html__( 'Cover type', 'jnews-tiktok' ),
			'description' => esc_html__( 'Choose cover type.', 'jnews-tiktok' ),
			'std'         => 'cover',
			'value'       => array(
				esc_attr__( 'Cover', 'jnews-tiktok' )   => 'cover',
				esc_attr__( 'Origin', 'jnews-tiktok' )  => 'origin',
				esc_attr__( 'Play', 'jnews-tiktok' )    => 'play',
				esc_attr__( 'Dynamic', 'jnews-tiktok' ) => 'dynamic',
			),
		);

		$this->options[] = array(
			'type'       => 'checkbox',
			'param_name' => 'footer_tiktok_open',
			'heading'    => esc_html__( 'Open New Tab', 'jnews-tiktok' ),
			'std'        => false,
		);
	}
}
