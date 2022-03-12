<?php
/**
 * @author : Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use JNews\Widget\AdditionalWidget;
use Jeg\Form\Form_Widget;

class JNews_Push_Notification_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'jnews_push_notification', // Base ID
			esc_html__( 'JNews - Push Notification', 'jnews' ), // Name
			array(
				'description'                 => esc_html__( 'Push Notification for JNews', 'jnews' ),
				'customize_selective_refresh' => true,
			), // Args
			null
		);
	}

	public function form( $instance ) {
		$options = array(
			'title'           => array(
				'title' => esc_html__( 'Title', 'jnews-push-notification' ),
				'desc'  => esc_html__( 'Title on widget header.', 'jnews-push-notification' ),
				'type'  => 'text',
				'group' => 'general-setting',
			),
			'description'     => array(
				'title' => esc_html__( 'Subscribe Description', 'jnews-push-notification' ),
				'desc'  => esc_html__( 'You may use standard HTML tags and attributes for subscribe description.', 'jnews-push-notification' ),
				'type'  => 'textarea',
				'group' => 'general-setting',
			),
			'btn_subscribe'   => array(
				'title' => esc_html__( 'Subscribe Button Text', 'jnews-push-notification' ),
				'desc'  => esc_html__( 'Insert text for subscribe button.', 'jnews-push-notification' ),
				'type'  => 'text',
				'group' => 'general-setting',
			),
			'btn_unsubscribe' => array(
				'title' => esc_html__( 'Unsubscribe Button Text', 'jnews-push-notification' ),
				'desc'  => esc_html__( 'Insert text for unsubscribe button.', 'jnews-push-notification' ),
				'type'  => 'text',
				'group' => 'general-setting',
			),
			'btn_processing'  => array(
				'title' => esc_html__( 'Processing Button Text', 'jnews-push-notification' ),
				'desc'  => esc_html__( 'Insert text for processing button.', 'jnews-push-notification' ),
				'type'  => 'text',
				'group' => 'general-setting',
			),
		);

		if ( ! is_customize_preview() ) {
			$id = $this->get_field_id( 'widget_news_element' );

			$segments[] = array(
				'id'   => 'general-setting',
				'name' => esc_html__( 'General Setting', 'jnews-push-notification' ),
			);

			$fields = $this->prepare_fields( $instance, $options );

			$additional_instance = AdditionalWidget::getInstance();
			$additional_field    = $additional_instance->prepare_fields( $this, $instance );
			$additional_segment  = $additional_instance->prepare_segments();

			if ( class_exists( 'Jeg\Form\Form_Widget' ) ) {
				Form_Widget::render_form( $id, array_merge( $segments, $additional_segment ), array_merge( $fields, $additional_field ) );
			}
		}
	}

	/**
	 * Init widget
	 *
	 * @param  array $args
	 * @param  array $instance
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );

		echo jnews_sanitize_output( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo jnews_sanitize_output( $args['before_title'] ) . wp_kses( $title, wp_kses_allowed_html() ) . $args['after_title'];
		}

		$this->render_content( $instance );

		echo jnews_sanitize_output( $args['after_widget'] );
	}

	public function prepare_fields( $instance, $options ) {
		$setting = array();
		$fields  = $options;

		foreach ( $fields as $key => $field ) {

			$setting[ $key ]              = array();
			$setting[ $key ]['id']        = $key;
			$setting[ $key ]['fieldID']   = $this->get_field_id( $key );
			$setting[ $key ]['fieldName'] = $this->get_field_name( $key );
			$setting[ $key ]['type']      = $field['type'];

			$setting[ $key ]['title']       = isset( $field['title'] ) ? $field['title'] : '';
			$setting[ $key ]['description'] = isset( $field['desc'] ) ? $field['desc'] : '';
			$setting[ $key ]['segment']     = isset( $field['group'] ) ? $field['group'] : '';
			$setting[ $key ]['default']     = isset( $field['default'] ) ? $field['default'] : '';
			$setting[ $key ]['priority']    = isset( $field['priority'] ) ? $field['priority'] : 10;
			$setting[ $key ]['options']     = isset( $field['options'] ) ? $field['options'] : array();
			$setting[ $key ]['dependency']  = isset( $field['dependency'] ) ? $field['dependency'] : '';
			$setting[ $key ]['multiple']    = isset( $field['multiple'] ) ? $field['multiple'] : 1;
			$setting[ $key ]['ajax']        = isset( $field['ajax'] ) ? $field['ajax'] : '';
			$setting[ $key ]['nonce']       = isset( $field['nonce'] ) ? $field['nonce'] : '';
			$setting[ $key ]['choices']     = isset( $field['choices'] ) ? $field['choices'] : '';
			$setting[ $key ]['value']       = $this->get_value( $key, $instance, $setting[ $key ]['default'] );
			$setting[ $key ]['fields']      = isset( $field['fields'] ) ? $field['fields'] : array();
			$setting[ $key ]['row_label']   = isset( $field['row_label'] ) ? $field['row_label'] : array();

			// only for image type
			if ( 'image' === $setting[ $key ]['type'] ) {
				$image = wp_get_attachment_image_src( $setting[ $key ]['value'], 'full' );
				if ( isset( $image[0] ) ) {
					$setting[ $key ]['imageUrl'] = $image[0];
				}
			}
		}

		return $setting;
	}

	public function get_value( $id, $value, $default ) {
		if ( isset( $value[ $id ] ) ) {
			return $value[ $id ];
		} else {
			return $default;
		}
	}

	/**
	 * Render widget content
	 *
	 * @param  array $instance
	 */
	public function render_content( $instance ) {
		if ( empty( $instance['btn_subscribe'] ) ) {
			$instance['btn_subscribe'] = jnews_return_translation( 'Subscribe', 'jnews-push-notification', 'push_notification_subscribe' );
		}

		if ( empty( $instance['btn_unsubscribe'] ) ) {
			$instance['btn_unsubscribe'] = jnews_return_translation( 'Unsubscribe', 'jnews-push-notification', 'push_notification_unsubscribe' );
		}

		if ( empty( $instance['btn_processing'] ) ) {
			$instance['btn_processing'] = jnews_return_translation( 'Processing . . .', 'jnews-push-notification', 'push_notification_processing' );
		}

		$description = isset( $instance['description'] ) ? $instance['description'] : '';

		$output = '<div class="jeg_push_notification loading">
                        <div class="jeg_push_notification_content">
                            <p>' . str_replace( PHP_EOL, '<br>', $description ) . "</p>
                            <div class=\"jeg_push_notification_button\">
                                <input type=\"hidden\" name=\"button-subscribe\" value=\"{$instance['btn_subscribe']}\">
                                <input type=\"hidden\" name=\"button-unsubscribe\" value=\"{$instance['btn_unsubscribe']}\">
                                <input type=\"hidden\" name=\"button-processing\" value=\"{$instance['btn_processing']}\">
                                <a data-action=\"subscribe\" class=\"button\" data-type=\"general\" href=\"#\">
                                    <i class=\"fa fa-bell-o\"></i>
                                    {$instance['btn_subscribe']}
                                </a>
                            </div>
                        </div>
                    </div>";

		if ( ! class_exists( 'OneSignal_Admin' ) ) {
			$output =
				'<div class="alert alert-error">
                    <strong>' . esc_html__( 'Plugin Install', 'jnews' ) . '</strong>' . ' : ' . esc_html__( 'Subscribe Push Notification need OneSignal plugin to be installed', 'jnews' ) .
				'</div>';
		}

		echo jnews_sanitize_output( $output );
	}
}
