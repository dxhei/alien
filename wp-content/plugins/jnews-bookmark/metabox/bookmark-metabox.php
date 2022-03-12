<?php
/**
 * Bookmark Metabox Option
 *
 * @package JNews - Bookmark
 */

if ( function_exists( 'jnews_is_active' ) && jnews_is_active()->is_license_validated() ) {
	return array(
		'id'       => 'jnews_override_bookmark_settings',
		'types'    => array( 'post' ),
		'title'    => esc_html__( 'JNews : Override Bookmark Button', 'jnews-bookmark' ),
		'priority' => 'high',
		'template' => array(

			array(
				'type'        => 'toggle',
				'name'        => 'override_bookmark_button',
				'default'     => false,
				'label'       => jnews_return_translation( 'Override Bookmark Setting', 'jnews-bookmark', 'bookmark_override_setting' ),
				'description' => jnews_return_translation( 'Enable this option to override Bookmark setting', 'jnews-bookmark', 'bookmark_override_setting_desc' ),
			),
			array(

				'name'            => 'override_show_bookmark_button',
				'default'         => false,
				'type'            => 'toggle',
				'label'           => jnews_return_translation( 'Show Bookmark Button', 'jnews-bookmark', 'show_bookmark_button' ),
				'description'     => jnews_return_translation( 'Show Bookmark button on post meta container.', 'jnews-bookmark', 'show_bookmark_button_desc' ),
				'active_callback' => array(
					array(
						'field'    => 'override_bookmark_button',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

		),
	);
} else {
	return array(
		'id'       => 'jnews_override_bookmark_settings',
		'types'    => array( 'post' ),
		'title'    => esc_html__( 'JNews : Override Bookmark Button', 'jnews-bookmark' ),
		'priority' => 'high',
		'template' => array(
			array(
				'type'        => 'notebox',
				'name'        => 'activate_license',
				'status'      => 'error',
				'label'       => esc_html__( 'Activate License', 'jnews-bookmark' ),
				'description' => sprintf(
					wp_kses(
						__(
							'<span style="display: block;">Please activate your copy of JNews to unlock this feature. Click button bellow to activate:</span>
						<span class="jnews-notice-button">
							<a href="%s" class="button-primary jnews_customizer_activate_license">Activate Now</a>
						</span>',
							'jnews-bookmark'
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
