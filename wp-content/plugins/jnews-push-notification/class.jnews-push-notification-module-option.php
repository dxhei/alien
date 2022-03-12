<?php
/**
 * @author : Jegtheme
 */

class JNews_Element_Push_Notification_Option extends \JNews\Module\ModuleOptionAbstract {

	public function get_category() {
		return esc_html__( 'JNews - Element', 'jnews-push-notification' );
	}

	public function compatible_column() {
		return array( 4, 8, 12 );
	}

	public function get_module_name() {
		 return esc_html__( 'JNews - Push Notification', 'jnews-push-notification' );
	}

	public function set_options() {
		 $this->set_option();
	}

	public function set_option() {
		$this->options[] = array(
			'type'        => 'textarea',
			'param_name'  => 'description',
			'heading'     => esc_html__( 'Subscribe Description', 'jnews-push-notification' ),
			'description' => esc_html__( 'You may use standard HTML tags and attributes for subscribe description.', 'jnews-push-notification' ),
			'std'         => '',
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'btn_subscribe',
			'heading'     => esc_html__( 'Subscribe Button Text', 'jnews-push-notification' ),
			'description' => esc_html__( 'Insert text for subscribe button.', 'jnews-push-notification' ),
			'std'         => '',
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'btn_unsubscribe',
			'heading'     => esc_html__( 'Unsubscribe Button Text', 'jnews-push-notification' ),
			'description' => esc_html__( 'Insert text for unsubscribe button.', 'jnews-push-notification' ),
			'std'         => '',
		);
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'btn_processing',
			'heading'     => esc_html__( 'Processing Button Text', 'jnews-push-notification' ),
			'description' => esc_html__( 'Insert text for processing button.', 'jnews-push-notification' ),
			'std'         => '',
		);
	}
}
