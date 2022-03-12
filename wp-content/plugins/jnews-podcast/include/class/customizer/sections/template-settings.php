<?php

use JNEWS_PODCAST\Series\Single_Series;

$single_series['true'] = array(
	'redirect' => 'single_series',
	'refresh'  => true,
);

$single_series['false'] = array(
	'redirect' => 'single_series',
	'refresh'  => false,
);

$postmeta_refresh['option'] = array(
	'selector'        => '.jeg_option_container',
	'render_callback' => function () {
		$single = Single_Series::get_instance();
		$single->render_post_option();
	},
);

$postmeta_refresh['meta'] = array(
	'selector'        => '.jeg_meta_container',
	'render_callback' => function () {
		$single = Single_Series::get_instance();
		$single->render_post_meta();
	},
);

$postmeta_callback['option'] = array(
	'setting'  => 'jnews_option[jnews_podcast][single_podcast_show_post_option]',
	'operator' => '==',
	'value'    => true,
);
$postmeta_callback['meta']   = array(
	'setting'  => 'jnews_option[jnews_podcast][single_podcast_show_post_meta]',
	'operator' => '==',
	'value'    => true,
);

$options   = array();
$options[] = array(
	'id'    => 'jnews_podcast_single_podcast',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Single Podcast Template & Layout Setting', 'jnews-podcast' ),
);

$options[] = array(
	'id'          => 'jnews_option[jnews_podcast][single_podcast_template]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => '2',
	'type'        => 'jnews-radio-image',
	'label'       => esc_html__( 'Single Podcast Template', 'jnews-podcast' ),
	'description' => esc_html__( 'Choose your single podcast template.', 'jnews-podcast' ),
	'choices'     => array(
		'1' => '',
		'2' => '',
	),
	'postvar'     => array(
		$single_series['true'],
	),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_layout]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'left-sidebar',
	'type'            => 'jnews-radio-image',
	'label'           => esc_html__( 'Single Podcast Layout', 'jnews-podcast' ),
	'description'     => esc_html__( 'Choose your single podcast layout', 'jnews-podcast' ),
	'choices'         => array(
		'left-sidebar'  => '',
		'right-sidebar' => '',
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[jnews_podcast][single_podcast_template]',
			'operator' => '==',
			'value'    => '1',
		),
	),
	'postvar'         => array(
		$single_series['true'],
	),
);


$options[] = array(
	'id'    => 'jnews_podcast_single_podcast_element_settings',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Single Podcast Element', 'jnews-podcast' ),
);

$options[] = array(
	'id'          => 'jnews_option[jnews_podcast][single_podcast_show_featured]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => true,
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Show Featured Image', 'jnews-podcast' ),
	'description' => esc_html__( 'Show featured image single podcast.', 'jnews-podcast' ),
	'postvar'     => array( $single_series['true'] ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_show_post_meta]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Podcast Meta', 'jnews-podcast' ),
	'description'     => esc_html__( 'Show Podcast meta on podcast aside.', 'jnews-podcast' ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_podcast_show_post_meta]' => $postmeta_refresh['meta'],
	),
	'postvar'         => array( $single_series['false'] ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_show_podcast_author]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Podcast Author', 'jnews-podcast' ),
	'description'     => esc_html__( 'Show podcast author on podcast meta container.', 'jnews-podcast' ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_podcast_show_podcast_author]' => $postmeta_refresh['meta'],
	),
	'active_callback' => array( $postmeta_callback['meta'] ),
	'postvar'         => array( $single_series['false'] ),
	'wrapper_class'   => array( 'first_child' ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_playlist_show_playlist_author_image]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => false,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Podcast Author Image', 'jnews-podcast' ),
	'description'     => esc_html__( 'Show podcast author image on podcast meta container.', 'jnews-podcast' ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_playlist_show_playlist_author_image]' => $postmeta_refresh['meta'],
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[jnews_podcast][single_podcast_show_podcast_author]',
			'operator' => '==',
			'value'    => true,
		),
		$postmeta_callback['meta'],
	),
	'postvar'         => array( $single_series['false'] ),
	'wrapper_class'   => array( 'first_child' ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_show_podcast_total_episode]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Episode Counter', 'jnews-podcast' ),
	'description'     => wp_kses( __( 'Show or hide episode counter', 'jnews-podcast' ), wp_kses_allowed_html() ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_podcast_show_podcast_total_episode]' => $postmeta_refresh['meta'],
	),
	'active_callback' => array( $postmeta_callback['meta'] ),
	'postvar'         => array( $single_series['false'] ),
	'wrapper_class'   => array( 'first_child' ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_show_post_option]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Podcast Meta Option', 'jnews-podcast' ),
	'description'     => esc_html__( 'Show Podcast meta option on podcast aside.', 'jnews-podcast' ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_podcast_show_post_option]' => $postmeta_refresh['option'],
	),
	'postvar'         => array( $single_series['false'] ),
);


$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_show_subscribe]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Subscribe Button', 'jnews-podcast' ),
	'description'     => esc_html__( 'Show subscribe button on option container.', 'jnews-podcast' ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_podcast_show_subscribe]' => $postmeta_refresh['option'],
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[jnews_podcast][single_podcast_show_post_option]',
			'operator' => '==',
			'value'    => true,
		),
		$postmeta_callback['option'],
	),
	'postvar'         => array( $single_series['false'] ),
	'wrapper_class'   => array( 'first_child' ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][single_podcast_show_share_button]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Show Share Button', 'jnews-podcast' ),
	'description'     => esc_html__( 'Show share button on option container', 'jnews-podcast' ),
	'partial_refresh' => array(
		'jnews_option[jnews_podcast][single_podcast_show_share_button]' => $postmeta_refresh['option'],
	),
	'active_callback' => array( $postmeta_callback['option'] ),
	'postvar'         => array( $single_series['false'] ),
	'wrapper_class'   => array( 'first_child' ),
);

$options[] = array(
	'id'    => 'jnews_podcast_single_podcast_content_settings',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Single Podcast Content Setting', 'jnews-podcast' ),
);

$options[] = array(
	'id'          => 'jnews_option[jnews_podcast][enable_post_excerpt]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => false,
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Enable Post Excerpt', 'jnews-podcast' ),
	'description' => esc_html__( 'Show post excerpt on this block.', 'jnews-podcast' ),
	'postvar'     => array(
		$single_series['true'],
	),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][excerpt_length]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 20,
	'type'            => 'jnews-number',
	'label'           => esc_html__( 'Excerpt Length', 'jnews' ),
	'description'     => esc_html__( 'Set the word length of excerpt on post.', 'jnews' ),
	'choices'         => array(
		'min'  => '0',
		'max'  => '200',
		'step' => '1',
	),
	'postvar'         => array(
		$single_series['true'],
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[jnews_podcast][enable_post_excerpt]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][excerpt_ellipsis]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => '...',
	'type'            => 'jnews-text',
	'label'           => esc_html__( 'Excerpt Ellipsis', 'jnews' ),
	'description'     => esc_html__( 'Define excerpt ellipsis', 'jnews' ),
	'postvar'         => array(
		$single_series['true'],
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[jnews_podcast][enable_post_excerpt]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

return $options;
