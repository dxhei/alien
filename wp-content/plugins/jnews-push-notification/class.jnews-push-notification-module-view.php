<?php
/**
 * @author : Jegtheme
 */

class JNews_Element_Push_Notification_View extends \JNews\Module\ModuleViewAbstract {

	public function render_module( $attr, $column_class ) {
		if ( function_exists( 'is_amp_endpoint()' ) && is_amp_endpoint() ) {
			return;
		}

		$output = '<div class="jeg_push_notification">
                            <div class="jeg_push_notification_content">
                                <p>' . str_replace( PHP_EOL, '<br>', $attr['description'] ) . "</p>
                                <div class=\"jeg_push_notification_button\">
                                    <input type=\"hidden\" name=\"button-subscribe\" value=\"{$attr['btn_subscribe']}\">
                                    <input type=\"hidden\" name=\"button-unsubscribe\" value=\"{$attr['btn_unsubscribe']}\">
                                    <input type=\"hidden\" name=\"button-processing\" value=\"{$attr['btn_processing']}\">
                                    <a data-action=\"subscribe\" class=\"button\" data-type=\"general\" href=\"#\">
                                        <i class=\"fa fa-bell-o\"></i>
                                        {$attr['btn_subscribe']}
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

		return $output;
	}
}
