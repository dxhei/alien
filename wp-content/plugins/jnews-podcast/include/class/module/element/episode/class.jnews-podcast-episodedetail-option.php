<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Episode\Episode_Option_Abstract;

/**
 * Class JNews_Podcast_Episodedetail_Option
 */
class JNews_Podcast_Episodedetail_Option extends Episode_Option_Abstract {

	/**
	 * show_excerpt
	 *
	 * @var bool
	 */
	protected $show_excerpt = true;

	/**
	 * @return string
	 */
	public function get_module_name() {
		return esc_html__( 'JNews - Episode Detail', 'jnews-podcast' );
	}

	/**
	 * Set options
	 */
	public function set_options() {
		$this->set_header_option();
		$this->set_content_filter_option( $this->default_number_post );
		$this->set_content_setting_option( $this->show_excerpt );
		$this->set_ajax_filter_option( $this->default_ajax_post );
		$this->set_style_option();
	}

	public function set_content_setting_option( $show_excerpt = false ) {
		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'enable_post_excerpt',
			'heading'     => esc_html__( 'Enable Post Excerpt', 'jnews' ),
			'description' => esc_html__( 'Show post excerpt on this block.', 'jnews' ),
			'group'       => esc_html__( 'Content Setting', 'jnews' ),
		);

		parent::set_content_setting_option( $show_excerpt );

		foreach ( $this->options as $idx => $options ) {
			if ( in_array( $options['param_name'], array( 'excerpt_length', 'excerpt_ellipsis' ) ) ) {
				if ( ! isset( $options['dependency'] ) ) {
					$this->options[ $idx ]['dependency'] = array(
						'element' => 'enable_post_excerpt',
						'value'   => 'true',
					);
				}
			}
		}
	}
}
