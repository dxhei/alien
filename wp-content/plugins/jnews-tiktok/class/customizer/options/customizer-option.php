<?php

/**
 * JNews Tiktok - Customizer Option
 */

$options = array();

$tiktok_feed_show_active_callback = array(
	'setting'  => 'jnews_option[tiktok_feed_enable]',
	'operator' => '!=',
	'value'    => 'hide',
);

$tiktok_feed_username_type_callback = array(
	'setting'  => 'jnews_option[footer_tiktok_feed_type]',
	'operator' => '==',
	'value'    => 'username',
);

$tiktok_feed_hastag_type_callback = array(
	'setting'  => 'jnews_option[footer_tiktok_feed_type]',
	'operator' => '==',
	'value'    => 'hastag',
);

$footer_tiktok_feed_refresh = array(
	'selector'        => '.jeg_footer_tiktok_wrapper',
	'render_callback' => function () {
		do_action( 'jnews_render_tiktok_feed_footer' );
	},
);

$options[] = array(
	'id'      => 'jnews_tiktok_feed_section',
	'type'    => 'jnews-header',
	'section' => 'jnews_tiktok_feed_section',
	'label'   => esc_html__( 'Tiktok Feed', 'jnews-tiktok' ),
);

$options[] = array(
	'id'          => 'jnews_footer_tiktok_alert',
	'type'        => 'jnews-alert',
	'default'     => 'info',
	'section'     => 'jnews_tiktok_feed_section',
	'label'       => esc_html__( 'Footer Tiktok Compatibility', 'jnews-tiktok' ),
	'description' => wp_kses( __( 'Footer Tiktok only compatible with <strong>Footer Type 5</strong> and <strong>Footer Type 6</strong>.', 'jnews-tiktok' ), wp_kses_allowed_html() ),
);

$options[] = array(
	'id'              => 'jnews_option[tiktok_feed_enable]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'hide',
	'type'            => 'jnews-select',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Enable Tiktok Feed', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Show the Tiktok feed only on header, footer or both.', 'jnews-tiktok' ),
	'multiple'        => 1,
	'choices'         => array(
		'show' => esc_attr__( 'Show', 'jnews-tiktok' ),
		'hide' => esc_attr__( 'Hide ', 'jnews-tiktok' ),
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_enable' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_feed_type]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'username',
	'type'            => 'jnews-select',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Feed Type', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Select feed type that you want to use.', 'jnews-tiktok' ),
	'multiple'        => 1,
	'choices'         => array(
		'username' => esc_attr__( 'Tiktok Username', 'jnews-tiktok' ),
		'hastag'   => esc_attr__( 'Tiktok Hastag', 'jnews-tiktok' ),
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_type' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_username]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jnews-text',
	'default'         => '',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Tiktok Username', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Insert your Tiktok username (without @).', 'jnews-tiktok' ),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
		$tiktok_feed_username_type_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_username' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_hastag]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jnews-text',
	'default'         => '',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Tiktok Hastag', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Insert hastag you want to add (without #).', 'jnews-tiktok' ),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
		$tiktok_feed_hastag_type_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_hastag' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_row]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 1,
	'type'            => 'jnews-slider',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Number Of Rows', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Number of rows for footer Tiktok feed.', 'jnews-tiktok' ),
	'choices'         => array(
		'min'  => '1',
		'max'  => '2',
		'step' => '1',
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_row' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_column]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 8,
	'type'            => 'jnews-slider',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Number Of Columns', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Number of Tiktok feed columns.', 'jnews-tiktok' ),
	'choices'         => array(
		'min'  => '5',
		'max'  => '10',
		'step' => '1',
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_column' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_sort_type]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'most_recent',
	'type'            => 'jnews-select',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Sort Feed Type', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Sort the Tiktok feed in a set order.', 'jnews-tiktok' ),
	'multiple'        => 1,
	'choices'         => array(
		'most_recent'   => esc_attr__( 'Most Recent', 'jnews-tiktok' ),
		'least_recent'  => esc_attr__( 'Least Recent', 'jnews-tiktok' ),
		'most_like'     => esc_attr__( 'Most Liked', 'jnews-tiktok' ),
		'least_like'    => esc_attr__( 'Least Liked', 'jnews-tiktok' ),
		'most_comment'  => esc_attr__( 'Most Commented ', 'jnews-tiktok' ),
		'least_comment' => esc_attr__( 'Least Commented ', 'jnews-tiktok' ),
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_sort_type' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_hover_style]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'zoom',
	'type'            => 'jnews-select',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Hover Style', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Choose hover effect style.', 'jnews-tiktok' ),
	'multiple'        => 1,
	'choices'         => array(
		'normal'      => esc_attr__( 'Normal', 'jnews-tiktok' ),
		'icon'        => esc_attr__( 'Show Icon', 'jnews-tiktok' ),
		'like'        => esc_attr__( 'Show Like Count', 'jnews-tiktok' ),
		'comment'     => esc_attr__( 'Show Comment Count', 'jnews-tiktok' ),
		'zoom'        => esc_attr__( 'Zoom', 'jnews-tiktok' ),
		'zoom-rotate' => esc_html__( 'Zoom Rotate', 'jnews-tiktok' ),
		' '           => esc_attr__( 'No Effect', 'jnews-tiktok' ),
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_hover_style' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_view_button]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jnews-text',
	'default'         => '',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'View Button Text', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Leave empty if you wont show it.', 'jnews-tiktok' ),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_view_button' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_layout]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jnews-select',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Layout type', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Choose layout type.', 'jnews-tiktok' ),
	'choices'         => array(
		'rectangle' => esc_attr__( 'Rectangle', 'jnews-tiktok' ),
		'square'    => esc_attr__( 'Square', 'jnews-tiktok' ),
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_layout' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_cover]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jnews-select',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Cover type', 'jnews-tiktok' ),
	'description'     => esc_html__( 'Choose cover type.', 'jnews-tiktok' ),
	'choices'         => array(
		'cover'   => esc_attr__( 'Cover', 'jnews-tiktok' ),
		'origin'  => esc_attr__( 'Origin', 'jnews-tiktok' ),
		'play'    => esc_attr__( 'Play', 'jnews-tiktok' ),
		'dynamic' => esc_attr__( 'Dynamic', 'jnews-tiktok' ),
	),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_cover' => $footer_tiktok_feed_refresh,
	),
);

$options[] = array(
	'id'              => 'jnews_option[footer_tiktok_open]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'type'            => 'jnews-toggle',
	'section'         => 'jnews_tiktok_feed_section',
	'label'           => esc_html__( 'Open New Tab', 'jnews-tiktok' ),
	'active_callback' => array(
		$tiktok_feed_show_active_callback,
	),
	'partial_refresh' => array(
		'jnews_footer_tiktok_open' => $footer_tiktok_feed_refresh,
	),
);

return $options;
