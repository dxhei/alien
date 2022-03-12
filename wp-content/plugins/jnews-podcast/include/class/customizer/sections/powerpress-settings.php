<?php

$options = array();

if ( jnews_is_podcast_extension_active( 'powerpress' ) ) {
	$options[] = array(
		'id'    => 'jnews_podcast_powerpress_header',
		'type'  => 'jnews-header',
		'label' => esc_html__( 'PowerPress Import Setting', 'jnews-podcast' ),
	);

	$options[] = array(
		'id'          => 'jnews_option[jnews_podcast][powerpress_import_override_term_taxonomy_detail]',
		'option_type' => 'option',
		'transport'   => 'postMessage',
		'default'     => false,
		'type'        => 'jnews-toggle',
		'label'       => esc_html__( 'Override Podcast Terms Detail', 'jnews-podcast' ),
		'description' => esc_html__(
			'Enable this feature will make PowerPress override podcast terms detail when importing podcast.',
			'jnews-podcast'
		),
	);

	$options[] = array(
		'id'          => 'jnews_option[jnews_podcast][powerpress_import_override_post_detail]',
		'option_type' => 'option',
		'transport'   => 'postMessage',
		'default'     => true,
		'type'        => 'jnews-toggle',
		'label'       => esc_html__( 'Override Post Detail', 'jnews-podcast' ),
		'description' => esc_html__(
			'Enable this feature will make PowerPress override post detail with imported podcast when importing podcast.',
			'jnews-podcast'
		),
	);

	$options[] = array(
		'id'              => 'jnews_option[jnews_podcast][powerpress_import_use_taxonomy_image]',
		'option_type'     => 'option',
		'transport'       => 'postMessage',
		'default'         => false,
		'type'            => 'jnews-toggle',
		'label'           => esc_html__( 'Use Terms Default Image', 'jnews-podcast' ),
		'description'     => esc_html__(
			'Enable this feature will make PowerPress use terms default image to imported podcast when importing podcast.',
			'jnews-podcast'
		),
		'active_callback' => array(
			array(
				'setting'  => 'jnews_option[jnews_podcast][powerpress_import_override_post_detail]',
				'operator' => '==',
				'value'    => true,
			),
		),
	);

	$options[] = array(
		'id'          => 'jnews_option[jnews_podcast][powerpress_import_override_post_category]',
		'option_type' => 'option',
		'transport'   => 'postMessage',
		'default'     => true,
		'type'        => 'jnews-toggle',
		'label'       => esc_html__( 'Override Post Category', 'jnews-podcast' ),
		'description' => esc_html__(
			'Enable this feature will make PowerPress override post category with imported podcast category when importing podcast.',
			'jnews-podcast'
		),
	);
} else {
	$options[] = array(
		'id'          => 'jnews_podcast_powerpress_alert',
		'type'        => 'jnews-alert',
		'default'     => 'warning',
		'label'       => esc_html__( 'Attention', 'jnews-podcast' ),
		'description' => wp_kses(
			__(
				'<ul>
				<li>Please activate Blubrry PowerPress to see the option</li>
			</ul>',
				'jnews-podcast'
			),
			wp_kses_allowed_html()
		),
	);
}

return $options;
