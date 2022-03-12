<?php

$options = array();

$subscribe_post_refresh = array(
	'selector'        => '.jeg_push_notification.single_post',
	'render_callback' => function() {
		return JNews_Push_Notification::getInstance()->render_single_post_form();
	},
);

$subscribe_category_refresh = array(
	'selector'        => '.jeg_push_notification.single_category',
	'render_callback' => function() {
		$term = get_queried_object();

		return JNews_Push_Notification::getInstance()->render_single_category_form( $term );
	},
);

$options[] = array(
	'id'      => 'jnews_option[push_notification_post_section]',
	'type'    => 'jnews-header',
	'section' => 'jnews_push_notification_section',
	'label'   => esc_html__( 'Single Post Subscribe', 'jnews-push-notification' ),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_post_enable]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => '',
	'type'            => 'jnews-toggle',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Enable Post Subscribe', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Enable subscribe form on each single post.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_post_enable]' => $subscribe_post_refresh,
	),
	'postvar'         => array(
		array(
			'redirect' => 'single_post_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_post_description]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => '',
	'type'            => 'jnews-textarea',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Subscribe Description', 'jnews-push-notification' ),
	'description'     => esc_html__( 'You may use standard HTML tags and attributes for subscribe description.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_post_description]' => $subscribe_post_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_post_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'single_post_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_post_btn_subscribe]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Subscribe',
	'type'            => 'jnews-text',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Subscribe Button Text', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Insert text for subscribe button.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_post_btn_subscribe]' => $subscribe_post_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_post_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'single_post_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_post_btn_unsubscribe]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Unsubscribe',
	'type'            => 'jnews-text',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Unsubscribe Button Text', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Insert text for unsubscribe button.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_post_btn_unsubscribe]' => $subscribe_post_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_post_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'single_post_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_post_btn_processing]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Processing . . .',
	'type'            => 'jnews-text',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Processing Button Text', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Insert text for processing button.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_post_btn_processing]' => $subscribe_post_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_post_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'single_post_tag',
			'refresh'  => false,
		),
	),
);


$options[] = array(
	'id'      => 'jnews_option[push_notification_category__section]',
	'type'    => 'jnews-header',
	'section' => 'jnews_push_notification_section',
	'label'   => esc_html__( 'Single Category Subscribe', 'jnews-push-notification' ),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_category_enable]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => '',
	'type'            => 'jnews-toggle',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Enable Category Subscribe', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Enable subscribe form on each category page.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_category_enable]' => $subscribe_category_refresh,
	),
	'postvar'         => array(
		array(
			'redirect' => 'category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_category_description]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => '',
	'type'            => 'jnews-textarea',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Subscribe Description', 'jnews-push-notification' ),
	'description'     => esc_html__( 'You may use standard HTML tags and attributes for subscribe description.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_category_description]' => $subscribe_category_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_category_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_category_btn_subscribe]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Subscribe',
	'type'            => 'jnews-text',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Subscribe Button Text', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Insert text for subscribe button.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_category_btn_subscribe]' => $subscribe_category_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_category_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_category_btn_unsubscribe]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Unsubscribe',
	'type'            => 'jnews-text',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Unsubscribe Button Text', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Insert text for unsubscribe button.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_category_btn_unsubscribe]' => $subscribe_category_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_category_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => 'jnews_option[push_notification_category_btn_processing]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => 'Processing . . .',
	'type'            => 'jnews-text',
	'section'         => 'jnews_push_notification_section',
	'label'           => esc_html__( 'Processing Button Text', 'jnews-push-notification' ),
	'description'     => esc_html__( 'Insert text for processing button.', 'jnews-push-notification' ),
	'partial_refresh' => array(
		'jnews_option[push_notification_category_btn_processing]' => $subscribe_category_refresh,
	),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[push_notification_category_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'category_tag',
			'refresh'  => false,
		),
	),
);

return $options;
