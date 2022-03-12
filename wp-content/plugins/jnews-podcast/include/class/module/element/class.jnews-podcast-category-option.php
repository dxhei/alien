<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast_Element_Option_Abstract;

/**
 * Class JNews_Podcast_Category_Option
 */
class JNews_Podcast_Category_Option extends Podcast_Element_Option_Abstract {
	protected $default_number_post = 6;

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Podcast Category', 'jnews-podcast' );
	}

	public function set_options() {
		$this->set_header_option();
		$this->set_content_filter_option( $this->default_number_post );
		$this->set_style_option();
	}

	/**
	 * @param int  $number
	 * @param bool $hide_number_post
	 */
	public function set_content_filter_option( $number = 10, $hide_number_post = false ) {
		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'param_name'  => 'include_category',
			'heading'     => esc_html__( 'Include Category', 'jnews-podcast' ),
			'description' => esc_html__( 'Choose which category you want to show on this module.', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'std'         => '',
		);

		if ( ! $hide_number_post ) {
			$this->options[] = array(
				'type'        => 'slider',
				'param_name'  => 'number_category',
				'heading'     => esc_html__( 'Number of Category', 'jnews-podcast' ),
				'description' => esc_html__( 'Show number of category on this module.', 'jnews-podcast' ),
				'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
				'min'         => 1,
				'max'         => 30,
				'step'        => 1,
				'std'         => $number,
			);
		}

		$this->options[] = array(
			'type'        => 'number',
			'param_name'  => 'category_offset',
			'heading'     => esc_html__( 'Category Offset', 'jnews-podcast' ),
			'description' => esc_html__( 'Number of category offset (start of content).', 'jnews-podcast' ),
			'group'       => esc_html__( 'Content Filter', 'jnews-podcast' ),
			'min'         => 0,
			'max'         => 9999,
			'step'        => 1,
			'std'         => 0,
		);
	}
}
