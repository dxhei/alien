<?php

$options = array();

$options[] = array(
	'id'    => 'jnews_podcast_general_header',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'General Setting', 'jnews-podcast' ),
);

$options[] = array(
	'id'          => 'jnews_option[jnews_podcast][override_category_link]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => false,
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Override Default Category WordPress to Podcast Category', 'jnews-podcast' ),
	'description' => esc_html__( 'Enable this feature will override default category WordPress to podcast category.', 'jnews-podcast' ),
);

$options[] = array(
	'id'          => 'jnews_option[jnews_podcast][podcast_enable_player]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => false,
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Enable Podcast Player', 'jnews-podcast' ),
	'description' => esc_html__( 'Enable this feature will show podcast player.', 'jnews-podcast' ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_podcast][podcast_global_player]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => false,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Enable Global Player', 'jnews-podcast' ),
	'description'     => esc_html__( 'Enable this feature will show podcast player globaly.', 'jnews-podcast' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[jnews_podcast][podcast_enable_player]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

return $options;
