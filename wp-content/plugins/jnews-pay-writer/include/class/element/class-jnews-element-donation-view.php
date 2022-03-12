<?php
/**
 * @author : Jegtheme
 */


use JNews\Module\ModuleViewAbstract;

class JNews_Element_Donation_View extends ModuleViewAbstract {

	/**
	 * Render Module
	 */
	public function render_module( $attr, $column_class ) {
		return $this->render_element( $attr, $column_class );
	}

	/**
	 * Render element
	 */
	public function render_element( $attr, $column_class ) {
		if ( ( ! empty( $attr['paypal_account'] ) || ! empty( $attr['paypal_account'] ) ) ) {
			global $post;
			if ( ! class_exists( 'JNews_Donation_Element' ) ) {
				require_once JNEWS_PAY_WRITER_DIR . 'include/class/element/class-jnews-donation.php';
			}

			$args     = array(
				'type'                          => 'widget',
				'button_text'                   => $attr['button_text'],
				'button_text_color'             => isset( $attr['override_button_color'] ) && $attr['override_button_color'] ? $attr['button_text_color'] : '',
				'button_color'                  => isset( $attr['override_button_color'] ) && $attr['override_button_color'] ? $attr['button_color'] : '',
				'donation_widget_title'         => isset( $attr['donation_widget_title'] ) ? esc_html( $attr['donation_widget_title'] ) : '',
				'donation_widget_description'   => isset( $attr['donation_widget_description'] ) ? esc_html( $attr['donation_widget_description'] ) : '',
				'paypal_account'                => $attr['paypal_account'],
				'donation_checkout_description' => isset( $attr['donation_checkout_description'] ) ? $attr['donation_checkout_description'] : '',
				'donation_currency'             => $attr['donation_currency'],
				'donation_amount'               => isset( $attr['enable_fix_amount'] ) ? $attr['fix_amount_donation'] : '',
				'cancel_return'                 => get_permalink( $post->ID ),
			);
			$donation = new \JNews\PAY_WRITER\Element\JNews_Donation_Element( $args );
			return $donation->generate_element( $args );
		}
	}
}
