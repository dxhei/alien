<?php

/**
 * JNews Paywall Class
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-paywall
 */

namespace JNews\Paywall\Metabox;

use Jeg\Form\Form_Meta_Box;

/**
 * Class Metabox
 *
 * @package JNews\Paywall\Metabox
 */
class Metabox {
	/**
	 * @var Metabox
	 */
	private static $instance;

	/**
	 * Metabox constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'initialize_metabox' ) );
		$this->initialize_metabox();
	}

	/**
	 * Intialize meta box using jeg-framework
	 */
	public function initialize_metabox() {
		$segments = $this->metabox_segments();
		$fields   = $this->metabox_fields();

		$option = array(
			'id'        => 'jnews_paywall_metabox',
			'title'     => esc_html__( 'JNews : Paywall Single Post', 'jnews-paywall' ),
			'post_type' => 'post',
			'type'      => 'normal',
			'segments'  => $segments,
			'fields'    => $fields,
		);

		if ( class_exists( 'Jeg\Form\Form_Meta_Box' ) ) {
			new Form_Meta_Box( $option );
		}
	}

	/**
	 * Create meta box segments
	 *
	 * @return array
	 */
	protected function metabox_segments() {
		$segments = array();

		$segments['paywall_setting'] = array(
			'name'     => esc_html__( 'Paywall Setting', 'jnews-paywall' ),
			'priority' => 1,
		);

		return $segments;
	}

	/**
	 * Create meta box fields
	 *
	 * @return array
	 */
	protected function metabox_fields() {
		$fields = array();

		/* Paywall Setting */
		$fields['enable_premium_post'] = array(
			'type'        => 'checkbox',
			'segment'     => 'paywall_setting',
			'title'       => esc_html__( 'Set as Premium Post', 'jnews-paywall' ),
			'description' => esc_html__( 'Check this option to set this post as premium.', 'jnews-paywall' ),
			'default'     => false,
		);

		$fields['paragraph_limit'] = array(
			'type'        => 'number',
			'segment'     => 'paywall_setting',
			'title'       => esc_html__( 'Paragraph Limit', 'jnews-paywall' ),
			'description' => esc_html__( 'Total paragraphs that will be showed for free user.', 'jnews-paywall' ),
			'default'     => '2',
			'options'     => array(
				'min'  => '1',
				'max'  => '9999',
				'step' => '1',
			),
		);

		return $fields;
	}

	/**
	 * @return Metabox
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
