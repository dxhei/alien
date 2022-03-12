<?php
/**
 * @see \JNews\Util\ValidateLicense::is_license_validated
 * @since 8.0.0
 */
if ( function_exists( 'jnews_is_active' ) && jnews_is_active()->is_license_validated() ) {
	return array(
		'id'       => 'jnews_podcast_option',
		'types'    => array( 'post' ),
		'title'    => 'JNews : Podcast Option',
		'priority' => 'high',
		'template' => array(
			array(
				'type'        => 'toggle',
				'name'        => 'enable_podcast',
				'label'       => esc_html__( 'Podcast Post', 'jnews-podcast' ),
				'description' => esc_html__( 'Enable podcast post', 'jnews-podcast' ),
			),
			array(
				'type'        => 'textbox',
				'name'        => 'podcast_duration',
				'label'       => esc_html__( 'Duration', 'jnews-podcast' ),
				'description' => esc_html__( 'Human-read time value, ex. mm:ss.', 'jnews-podcast' ),
			),
			array(
				'type'        => 'textbox',
				'name'        => 'upload',
				'label'       => esc_html__( 'Media URL', 'jnews-podcast' ),
				'description' => esc_html__( 'Fill this option with the media url.', 'jnews-podcast' ),
			),
		),
	);
} else {
	return array(
		'id'       => 'jnews_podcast_option',
		'types'    => array( 'post' ),
		'title'    => 'JNews : Podcast Option',
		'priority' => 'high',
		'template' => array(
			array(
				'type'        => 'notebox',
				'name'        => 'activate_license',
				'status'      => 'error',
				'label'       => esc_html__( 'Activate License', 'jnews-podcast' ),
				'description' => sprintf(
					wp_kses(
						__(
							'<span style="display: block;">Please activate your copy of JNews to unlock this feature. Click button bellow to activate:</span>
						<span class="jnews-notice-button">
							<a href="%s" class="button-primary jnews_customizer_activate_license">Activate Now</a>
						</span>',
							'jnews-podcast'
						),
						array(
							'strong' => array(),
							'span'   => array(
								'style' => true,
								'class' => true,
							),
							'a'      => array(
								'href'  => true,
								'class' => true,
							),
						)
					),
					get_admin_url()
				),
			),
		),
	);
}
