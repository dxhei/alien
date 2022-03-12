<?php
/**
 * Bookmark Option
 *
 * @package JNews - Bookmark
 */

$options = array();

$single_post_tag = array(
	'redirect' => 'single_post_tag',
	'refresh'  => false,
);

$options[] = array(
	'id'      => 'jnews_option[jnews_bookmark][jnews_single_bookmark_header]',
	'type'    => 'jnews-header',
	'section' => 'jnews_bookmark_section',
	'label'   => jnews_return_translation( 'Bookmark Button Option', 'jnews-bookmark', 'bookmark_button_option' ),
);

$options[] = array(
	'id'              => 'jnews_option[jnews_bookmark][jnews_single_bookmark]',
	'option_type'     => 'option',
	'transport'       => 'postMessage',
	'default'         => false,
	'type'            => 'jnews-toggle',
	'section'         => 'jnews_bookmark_section',
	'label'           => jnews_return_translation( 'Show Bookmark Button', 'jnews-bookmark', 'show_bookmark_button' ),
	'description'     => jnews_return_translation( 'Show Bookmark button on post meta container.', 'jnews-bookmark', 'show_bookmark_button_desc' ),
	'partial_refresh' => array(
		'jnews_option[jnews_bookmark][jnews_single_bookmark]' => array(
			'selector'        => '.jeg_meta_container',
			'render_callback' => function () {
				$single = \JNews\Single\SinglePost::getInstance();
				$single->render_post_meta();
			},
		),
	),
	'postvar'         => array( $single_post_tag ),
);

return $options;
