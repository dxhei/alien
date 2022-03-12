<?php
require_once 'class.jnews-amp-option.php';

$options = array();

$options[] = array(
	'id'    => 'jnews_option[amp_ads_google_auto_section]',
	'type'  => 'jnews-header',
	'label' => esc_html__( 'Google Auto Advertisement', 'jnews-amp' ),
);

$options[] = array(
	'id'          => 'jnews_option[amp_ads_google_auto_enable]',
	'transport'   => 'postMessage',
	'option_type' => 'option',
	'default'     => '',
	'type'        => 'jnews-toggle',
	'label'       => esc_html__( 'Enable Google Auto Advertisement', 'jnews-amp' ),
	'description' => esc_html__( 'Enable Google auto advertisement on AMP pages.', 'jnews-amp' ),
);

$options[] = array(
	'id'              => 'jnews_option[amp_ads_google_auto_publisher]',
	'transport'       => 'postMessage',
	'option_type'     => 'option',
	'default'         => '',
	'type'            => 'jnews-text',
	'label'           => esc_html__( 'Google Ads Publisher ID', 'jnews-amp' ),
	'description'     => esc_html__( 'Insert data-ad-client / google_ad_client content.', 'jnews-amp' ),
	'active_callback' => array(
		array(
			'setting'  => 'jnews_option[amp_ads_google_auto_enable]',
			'operator' => '==',
			'value'    => true,
		),
	),
);

$above_header = new JNews_AMP_Option(
	array(
		'location'    => 'above_header',
		'label'       => 'Above Header',
		'description' => 'above header',
	)
);

$above_article = new JNews_AMP_Option(
	array(
		'location'    => 'above_article',
		'label'       => 'Above Article',
		'description' => 'above article',
	)
);

$above_content = new JNews_AMP_Option(
	array(
		'location'    => 'above_content',
		'label'       => 'Above Content',
		'description' => 'above content',
	)
);

$inline_content = new JNews_AMP_Option(
	array(
		'location'    => 'inline_content',
		'label'       => 'Inline Content',
		'description' => 'inline content',
	)
);

$below_content = new JNews_AMP_Option(
	array(
		'location'    => 'below_content',
		'label'       => 'Below Content',
		'description' => 'below content',
	)
);

$below_article = new JNews_AMP_Option(
	array(
		'location'    => 'below_article',
		'label'       => 'Below Article',
		'description' => 'below article',
	)
);

return array_merge(
	$options,
	$above_header->generate_option(),
	$above_article->generate_option(),
	$above_content->generate_option(),
	$inline_content->generate_option(),
	$below_content->generate_option(),
	$below_article->generate_option()
);

