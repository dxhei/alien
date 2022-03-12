<?php
/**
 * @see \JNews\Util\ValidateLicense::is_license_validated
 * @since 8.0.0
 */
if ( function_exists( 'jnews_is_active' ) && jnews_is_active()->is_license_validated() ) {
	return array(
		'id'       => 'jnews_podcast_series',
		'types'    => array( 'post' ),
		'title'    => esc_html__( 'Podcast Series', 'jnews-podcast' ),
		'priority' => 'high',
		'context'  => 'side',
		'template' => array(

			array(
				'type'        => 'singletermhierarchy',
				'name'        => 'id',
				'label'       => esc_html__( 'Podcast Series', 'jnews-podcast' ),
				'description' => wp_kses( __( 'You can search the post series by <strong>inputting the series name</strong>, clicking search result, and you will have your post series.<br>', 'jnews-podcast' ), wp_kses_allowed_html() ),
				'multiple'    => false,
				'items'       => array(
					'data' => array(
						array(
							'source' => 'function',
							'value'  => 'jnews_get_podcast_selectize',
						),
					),
				),
			),

		),
	);
} else {
	return array(
		'id'       => 'jnews_podcast_series',
		'types'    => array( 'post' ),
		'title'    => esc_html__( 'Podcast Series', 'jnews-podcast' ),
		'priority' => 'high',
		'context'  => 'side',
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
