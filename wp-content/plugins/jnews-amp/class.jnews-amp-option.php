<?php
/**
 * @author : Jegtheme
 */

/**
 * Class Theme JNews Option
 */
class JNews_AMP_Option {

	private $option;

	/**
	 * @var array
	 */
	private $ad_size = array(
		'auto'    => 'Auto',
		'120x90'  => '120 x 90',
		'120x240' => '120 x 240',
		'120x600' => '120 x 600',
		'125x125' => '125 x 125',
		'160x90'  => '160 x 90',
		'160x600' => '160 x 600',
		'180x90'  => '180 x 90',
		'180x150' => '180 x 150',
		'200x90'  => '200 x 90',
		'200x200' => '200 x 200',
		'234x60'  => '234 x 60',
		'250x250' => '250 x 250',
		'320x100' => '320 x 100',
		'300x250' => '300 x 250',
		'300x600' => '300 x 600',
		'320x50'  => '320 x 50',
		'336x280' => '336 x 280',
		'468x15'  => '468 x 15',
		'468x60'  => '468 x 60',
		'240x400' => '240 x 400',
		'250x360' => '250 x 360',
	);

	public function __construct( $option ) {
		$this->option = $option;
	}

	public function generate_option() {
		$options = array();

		$options[] = array(
			'id'    => 'jnews_option[amp_ads_' . $this->option['location'] . '_section]',
			'type'  => 'jnews-header',
			'label' => sprintf( esc_html__( '%s Advertisement', 'jnews-amp' ), $this->option['label'] ),
		);

		$options[] = array(
			'id'          => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
			'transport'   => 'postMessage',
			'option_type' => 'option',
			'default'     => '',
			'type'        => 'jnews-toggle',
			'label'       => sprintf( esc_html__( 'Enable %s Advertisement', 'jnews-amp' ), $this->option['label'] ),
			'description' => sprintf( esc_html__( 'Enable advertisement on %s.', 'jnews-amp' ), $this->option['description'] ),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_type]',
			'transport'       => 'postMessage',
			'option_type'     => 'option',
			'default'         => 'googleads',
			'type'            => 'jnews-radio',
			'label'           => sprintf( esc_html__( '%s : Advertisement type', 'jnews-amp' ), $this->option['label'] ),
			'description'     => esc_html__( 'Choose which type of advertisement you want to use.', 'jnews-amp' ),
			'multiple'        => 1,
			'choices'         => array(
				'googleads' => esc_attr__( 'Google Ads', 'jnews-amp' ),
				'custom'    => esc_attr__( 'Custom Ads', 'jnews-amp' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		if ( $this->option['location'] === 'inline_content' ) {
			$options[] = array(
				'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_paragraph_random]',
				'transport'       => 'postMessage',
				'option_type'     => 'option',
				'default'         => '',
				'type'            => 'jnews-toggle',
				'label'           => sprintf( esc_html__( '%s : Random Ads Position', 'jnews-amp' ), $this->option['label'] ),
				'description'     => esc_html__( 'Random on which paragraph ads will show.', 'jnews-amp' ),
				'active_callback' => array(
					array(
						'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			$options[] = array(
				'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_paragraph]',
				'transport'       => 'postMessage',
				'option_type'     => 'option',
				'default'         => '3',
				'type'            => 'jnews-slider',
				'label'           => sprintf( esc_html__( '%s : After Paragraph', 'jnews-amp' ), $this->option['label'] ),
				'description'     => esc_html__( 'After which paragraph you want this advertisement to show.', 'jnews-amp' ),
				'choices'         => array(
					'min'  => '0',
					'max'  => '20',
					'step' => '1',
				),
				'active_callback' => array(
					array(
						'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			);
		}

		$options[] = array(
			'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_google_publisher]',
			'transport'       => 'postMessage',
			'option_type'     => 'option',
			'default'         => '',
			'type'            => 'jnews-text',
			'label'           => sprintf( esc_html__( '%s : Publisher ID', 'jnews-amp' ), $this->option['label'] ),
			'description'     => esc_html__( 'Insert data-ad-client / google_ad_client content.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_type]',
					'operator' => '==',
					'value'    => 'googleads',
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_google_id]',
			'transport'       => 'postMessage',
			'option_type'     => 'option',
			'default'         => '',
			'type'            => 'jnews-text',
			'label'           => sprintf( esc_html__( '%s : Ad Slot ID', 'jnews-amp' ), $this->option['label'] ),
			'description'     => esc_html__( 'Insert data-ad-slot / google_ad_slot content.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_type]',
					'operator' => '==',
					'value'    => 'googleads',
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_size]',
			'transport'       => 'postMessage',
			'option_type'     => 'option',
			'default'         => 'auto',
			'type'            => 'jnews-select',
			'label'           => sprintf( esc_html__( '%s : Ads Size', 'jnews-amp' ), $this->option['label'] ),
			'description'     => esc_html__( 'Choose ads size, recommended to use auto instead.', 'jnews-amp' ),
			'choices'         => $this->ad_size,
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_type]',
					'operator' => '==',
					'value'    => 'googleads',
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_ads_' . $this->option['location'] . '_custom]',
			'transport'       => 'postMessage',
			'option_type'     => 'option',
			'default'         => '',
			'type'            => 'jnews-textarea',
			'sanitize'        => 'jnews_sanitize_by_pass',
			'label'           => sprintf( esc_html__( '%s : Ad Code', 'jnews-amp' ), $this->option['label'] ),
			'description'     => esc_html__( 'Insert your custom ad code.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_enable]',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'jnews_option[amp_ads_' . $this->option['location'] . '_type]',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		);

		return $options;
	}
}
