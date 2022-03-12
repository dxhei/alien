<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Series\Category_Series_Archive;

$prefix      = 'jnews_podcast_archive_category_';
$options     = array();
$all_sidebar = apply_filters( 'jnews_get_sidebar_widget', null );

$content_layout = apply_filters(
	"{$prefix}get_content_layout_customizer",
	array(
		'podcast_1' => '',
		'podcast_2' => '',
		'podcast_3' => '',
		'podcast_4' => '',
	)
);
// sidebar section
$options[] = array(
	'id'    => "{$prefix}sidebar_section",
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Podcast Category Page Layout', 'jnews-podcast' ),
);

$options[] = array(
	'id'          => "{$prefix}page_layout",
	'transport'   => 'postMessage',
	'default'     => 'right-sidebar',
	'type'        => 'jnews-radio-image',
	'label'       => esc_html__( 'Page Layout', 'jnews-podcast' ),
	'description' => esc_html__( 'Choose your page layout.', 'jnews-podcast' ),
	'choices'     => array(
		'right-sidebar'        => '',
		'left-sidebar'         => '',
		'right-sidebar-narrow' => '',
		'left-sidebar-narrow'  => '',
		'double-sidebar'       => '',
		'double-right-sidebar' => '',
		'no-sidebar'           => '',
	),
	'postvar'     => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => true,
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}sidebar",
	'transport'       => 'postMessage',
	'default'         => 'default-sidebar',
	'type'            => 'jnews-select',
	'label'           => esc_html__( 'Sidebar', 'jnews-podcast' ),
	'description'     => wp_kses( __( 'Choose your sidebar. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.', 'jnews-podcast' ), wp_kses_allowed_html() ),
	'multiple'        => 1,
	'choices'         => $all_sidebar,
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => true,
		),
	),
	'active_callback' => array(
		array(
			'setting'  => "{$prefix}page_layout",
			'operator' => '!=',
			'value'    => 'no-sidebar',
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}second_sidebar",
	'transport'       => 'postMessage',
	'default'         => 'default-sidebar',
	'type'            => 'jnews-select',
	'label'           => esc_html__( 'Second Sidebar', 'jnews-podcast' ),
	'description'     => wp_kses( __( 'Choose your second sidebar. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.', 'jnews-podcast' ), wp_kses_allowed_html() ),
	'multiple'        => 1,
	'choices'         => $all_sidebar,
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => true,
		),
	),
	'active_callback' => array(
		array(
			'setting'  => "{$prefix}page_layout",
			'operator' => 'in',
			'value'    => array( 'double-sidebar', 'double-right-sidebar' ),
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}sticky_sidebar",
	'transport'       => 'postMessage',
	'default'         => true,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Sticky Sidebar', 'jnews-podcast' ),
	'description'     => esc_html__( 'Enable sticky sidebar on result page.', 'jnews-podcast' ),
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => true,
		),
	),
	'active_callback' => array(
		array(
			'setting'  => "{$prefix}page_layout",
			'operator' => '!=',
			'value'    => 'no-sidebar',
		),
	),
);


// content type.
$options[] = array(
	'id'    => "{$prefix}content_section",
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Podcast Category Content', 'jnews-podcast' ),
);

$options[] = array(
	'id'              => "{$prefix}content",
	'transport'       => 'postMessage',
	'default'         => '3',
	'type'            => 'jnews-radio-image',
	'label'           => esc_html__( 'Content Layout', 'jnews-podcast' ),
	'description'     => esc_html__( 'Choose your page content layout.', 'jnews-podcast' ),
	'multiple'        => 1,
	'choices'         => $content_layout,
	'partial_refresh' => array(
		"{$prefix}content" => array(
			'selector'        => '.jnews_podcast_content_wrapper',
			'render_callback' => function () {
				$single = new Category_Series_Archive();
				echo jnews_sanitize_output( $single->render_content() );
			},
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}posts_per_page",
	'transport'       => 'postMessage',
	'default'         => get_option( 'posts_per_page' ),
	'type'            => 'jnews-number',
	'label'           => esc_html__( 'Posts Per Page', 'jnews-podcast' ),
	'description'     => esc_html__( 'Number of posts per page.', 'jnews-podcast' ),
	'choices'         => array(
		'min'  => '0',
		'max'  => '200',
		'step' => '1',
	),
	'partial_refresh' => array(
		"{$prefix}posts_per_page" => array(
			'selector'        => '.jnews_podcast_content_wrapper',
			'render_callback' => function () {
				$single = new Category_Series_Archive();
				echo jnews_sanitize_output( $single->render_content() );
			},
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}boxed",
	'transport'       => 'postMessage',
	'default'         => false,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Enable Boxed', 'jnews-podcast' ),
	'description'     => esc_html__( 'This option will turn the module into boxed.', 'jnews-podcast' ),
	'partial_refresh' => array(
		"{$prefix}boxed" => array(
			'selector'        => '.jnews_podcast_content_wrapper',
			'render_callback' => function () {
				$single = new Category_Series_Archive();
				echo jnews_sanitize_output( $single->render_content() );
			},
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}boxed_shadow",
	'transport'       => 'postMessage',
	'default'         => false,
	'type'            => 'jnews-toggle',
	'label'           => esc_html__( 'Enable Shadow', 'jnews-podcast' ),
	'description'     => esc_html__( 'Enable shadow on the module template.', 'jnews-podcast' ),
	'partial_refresh' => array(
		"{$prefix}boxed_shadow" => array(
			'selector'        => '.jnews_podcast_content_wrapper',
			'render_callback' => function () {
				$single = new Category_Series_Archive();
				echo jnews_sanitize_output( $single->render_content() );
			},
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => false,
		),
	),
	'active_callback' => array(
		array(
			'setting'  => "{$prefix}boxed",
			'operator' => '==',
			'value'    => true,
		),
	),
);

$options[] = array(
	'id'              => "{$prefix}content_excerpt",
	'transport'       => 'postMessage',
	'default'         => 20,
	'type'            => 'jnews-number',
	'label'           => esc_html__( 'Excerpt Length', 'jnews-podcast' ),
	'description'     => esc_html__( 'Set the word length of excerpt on post.', 'jnews-podcast' ),
	'choices'         => array(
		'min'  => '0',
		'max'  => '200',
		'step' => '1',
	),
	'partial_refresh' => array(
		"{$prefix}content_excerpt" => array(
			'selector'        => '.jnews_podcast_content_wrapper',
			'render_callback' => function () {
				$single = new Category_Series_Archive();
				echo jnews_sanitize_output( $single->render_content() );
			},
		),
	),
	'postvar'         => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => false,
		),
	),
);

$options[] = array(
	'id'          => "{$prefix}content_pagination",
	'transport'   => 'postMessage',
	'default'     => 'scrollload',
	'type'        => 'jnews-select',
	'label'       => esc_html__( 'Choose Pagination Mode', 'jnews-podcast' ),
	'description' => esc_html__( 'Choose which pagination mode that fit with your block.', 'jnews-podcast' ),
	'multiple'    => 1,
	'choices'     => array(
		'loadmore'   => esc_attr__( 'Load More', 'jnews-podcast' ),
		'scrollload' => esc_attr__( 'Auto Load on Scroll', 'jnews-podcast' ),
	),
	'postvar'     => array(
		array(
			'redirect' => 'podcast_category_tag',
			'refresh'  => false,
		),
	),
);

return apply_filters( "{$prefix}custom_customizer_option", $options, $prefix, null );
