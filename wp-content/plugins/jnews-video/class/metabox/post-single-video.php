<?php
/**
 * @see \JNews\Util\ValidateLicense::is_license_validated
 * @since 8.0.0
 */
if ( function_exists( 'jnews_is_active' ) && jnews_is_active()->is_license_validated() ) {
	return array(
		'id'       => 'jnews_video_option',
		'types'    => array( 'post' ),
		'title'    => 'JNews : Video Option',
		'priority' => 'high',
		'template' => array(
			array(
				'type'        => 'textbox',
				'name'        => 'video_duration',
				'label'       => esc_html__( 'Duration', 'jnews-video' ),
				'description' => esc_html__( 'Human-read time value, ex. mm:ss.', 'jnews-video' ),
			),
			array(
				'type'        => 'upload',
				'name'        => 'video_preview',
				'label'       => esc_html__( '3 Second Video Preview', 'jnews-video' ),
				'description' => esc_html__( 'Upload 3 Second Video Preview. Only Support WEBP format.', 'jnews-video' ),
			),
		),
	);
} else {
	return array(
		'id'       => 'jnews_video_option',
		'types'    => array( 'post' ),
		'title'    => 'JNews : Video Option',
		'priority' => 'high',
		'template' => array(
			array(
				'type'        => 'notebox',
				'name'        => 'activate_license',
				'status'      => 'error',
				'label'       => esc_html__( 'Activate License', 'jnews-video' ),
				'description' => sprintf(
					wp_kses(
						__(
							'<span style="display: block;">Please activate your copy of JNews to unlock this feature. Click button bellow to activate:</span>
						<span class="jnews-notice-button">
							<a href="%s" class="button-primary jnews_customizer_activate_license">Activate Now</a>
						</span>',
							'jnews-video'
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
