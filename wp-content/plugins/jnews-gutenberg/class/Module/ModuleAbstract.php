<?php
/**
 * @author : Jegtheme
 */
namespace JNEWS_GUTENBERG\Module;

abstract class ModuleAbstract {

	private $class;

	public function __construct() {
		$this->class = get_class( $this );
	}

	public function render( $attributes ) {
		if ( defined( 'JNEWS_THEME_URL' ) ) {
			$args     = array();
			$name     = jnews_get_view_class_from_shortcode( $this->class );
			$instance = jnews_get_module_instance( $name );

			foreach ( $this->attribute() as $key => $value ) {
				if ( $key === 'compatible_column_notice' ) {
					continue;
				}

				if ( $key === 'className' ) {
					$args['el_class'] = $attributes[ $key ];
				} else {
					$args[ $key ] = $attributes[ $key ];
				}
			}

			return $this->build_module( $instance, $args );
		}

		return $this->fallback_notice();
	}

	public function attribute() {
		$options = array();

		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ( isset( $_POST['action'] ) && 'vc_edit_form' === $_POST['action'] ) ) {
			return $options;
		}

		if ( defined( 'JNEWS_THEME_URL' ) ) {
			$name     = jnews_get_option_class_from_shortcode( $this->class );
			$instance = jnews_get_module_instance( $name );

			foreach ( $instance->get_options() as $option ) {
				$type = ( in_array( $option['type'], array( 'slider', 'number', 'attach_image' ) ) ) ? 'number' : 'string';

				$options[ $option['param_name'] ] = array(
					'type'    => $type,
					'default' => isset( $option['std'] ) && $option['std'] ? $option['std'] : '',
				);

				if ( $option['type'] === 'attach_image' ) {
					$options[ $option['param_name'] . '_url' ] = array(
						'type' => 'string',
					);
				}
			}
		}

		return $options;
	}

	public function build_module( $instance, $args ) {
		return $instance->build_module( $args );
	}

	public function fallback_notice() {
		return '<div class="jnews_gutenberg_fallback">
                <div class="alert alert-success">
                    <strong>' . esc_html__( 'Notice', 'jnews-gutenberg' ) . '</strong> : ' . esc_html__( 'JNews Gutenberg module element need JNews theme to be installed.', 'jnews-gutenberg' ) . '
                </div>
            </div>';
	}
}
