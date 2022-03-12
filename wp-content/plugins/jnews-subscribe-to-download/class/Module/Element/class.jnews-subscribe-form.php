<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_SUBSCRIBE\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class JNews_Subscribe_Element
 *
 * @package JNEWS_SUBSCRIBE\Module
 */
class JNews_Subscribe_Element {
	/**
	 * @var string
	 */
	private $form_id;
	private $element_id;
	private $get_vc_class_name;
	private $el_class;
	private $download_type;
	private $file_id;
	private $add_action;
	private $heading_type;
	private $heading_text;
	private $text;
	private $button_icon;
	private $button_text;
	private $button_placeholder;
	private $button_download_icon;
	private $button_download_text;
	private $agreement;
	private $agreement_1;
	private $agreement_2;

	/**
	 * JNews_Subscribe_Element constructor.
	 *
	 * @param $param
	 * @param int   $row
	 */
	public function __construct( $param, $row = 1 ) {
		$this->form_id              = $param['form_id'];
		$this->element_id           = $param['element_id'];
		$this->get_vc_class_name    = $param['get_vc_class_name'];
		$this->el_class             = $param['el_class'];
		$this->download_type        = $param['download_type'];
		$this->file_id              = $param['file_id'];
		$this->add_action           = $param['add_action'];
		$this->heading_type         = $param['heading_type'];
		$this->heading_text         = $param['heading_text'];
		$this->text                 = $param['text'];
		$this->button_icon          = $param['button_icon'];
		$this->button_text          = $param['button_text'];
		$this->button_placeholder   = $param['button_placeholder'];
		$this->button_download_icon = $param['button_download_icon'];
		$this->button_download_text = $param['button_download_text'];
		$this->agreement            = $param['agreement'];
		$this->agreement_1          = $param['agreement_1'];
		$this->agreement_2          = $param['agreement_2'];
	}

	/**
	 * Generate element for Subscribe Element
	 */
	public function generate_element( $echo = true ) {
		$popup_button          = '';
		$close_button          = '';
		$popup_form            = '';
		$agreement_content     = '';
		$after_submit          = json_encode( $this->add_action );
		$file_id               = (int) $this->file_id;
		$jnews_subscribe_nonce = esc_attr( wp_create_nonce( 'jnews-subscribe-nonce' ) );

		$heading_type         = ( '' !== $this->heading_type ) ? $this->heading_type : 'h2';
		$heading_text         = ( '' !== $this->heading_text ) ? sprintf( __( '<%1$s class="jeg_subscribe_heading">%2$s</%1$s>', 'jnews-subscribe' ), $heading_type, $this->heading_text ) : false;
		$text                 = ( '' !== $this->text ) ? sprintf( __( '<p>%1$s</p>', 'jnews-subscribe' ), $this->text ) : false;
		$button_icon          = ( '' !== $this->button_icon ) ? sprintf( __( '<i class="fa %1$s"></i>', 'jnews-subscribe' ), $this->button_icon ) : false;
		$button_text          = ( '' !== $this->button_text ) ? sprintf( esc_html__( '%1$s', 'jnews-subscribe' ), $this->button_text ) : false;
		$button_placeholder   = ( '' !== $this->button_placeholder ) ? sprintf( esc_html__( '%1$s', 'jnews-subscribe' ), $this->button_placeholder ) : false;
		$button_download_icon = ( '' !== $this->button_download_icon ) ? sprintf( __( '<i class="fa %1$s"></i>', 'jnews-subscribe' ), $this->button_download_icon ) : false;
		$button_download_text = ( '' !== $this->button_download_text ) ? sprintf( esc_html__( '%1$s', 'jnews-subscribe' ), $this->button_download_text ) : false;
		$agreement            = ( '' !== $this->agreement ) ? $this->agreement : false;
		$agreement_1          = ( '' !== $this->agreement_1 ) ? sprintf( esc_html__( '%1$s', 'jnews-subscribe' ), $this->agreement_1 ) : false;
		$agreement_2          = ( '' !== $this->agreement_2 ) ? sprintf( esc_html__( '%1$s', 'jnews-subscribe' ), $this->agreement_2 ) : false;
		$disabled             = ( $agreement ) ? 'disabled="disabled"' : '';

		if ( 'button' === $this->download_type ) {
			$popup_button = "<button class=\"btn default dl-btn\" data-subscribe-id=\"{$this->form_id}\">{$button_download_icon}{$button_download_text}</button>";
			$popup_form   = 'form-pop-up';
			$close_button = '<div class="modal-header nopad">
										<button type="button" class="close grey" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
									</div>';
		}
		if ( $agreement ) {
			$agreement_content_1 = '';
			$agreement_content_2 = '';
			if ( $agreement_1 ) {
				$agreement_content_1 = "<div class=\"checkbox\">
						                                <input type=\"checkbox\" name=\"agree_terms\" id=\"agree_terms\">
						                                <label for=\"agree_terms\">{$agreement_1}</label>
						                            </div>";
			}
			if ( $agreement_2 ) {
				$agreement_content_2 = "<div class=\"checkbox\">
						                                <input type=\"checkbox\" name=\"agree_subscribe\" id=\"agree_subscribe\">
						                                <label for=\"agree_subscribe\">{$agreement_2}</label>
						                            </div>";
			}
			$agreement_content = sprintf( __( '<div class="bottom_links clearfix">%1$s %2$s</div>', 'jnews-subscribe' ), $agreement_content_1, $agreement_content_2 );
		}
		$output = "<div class='jeg_subscribe clearfix {$this->form_id}'>
                        {$popup_button}
                        <div class=\"jeg_subscribe_form {$popup_form}\" data-subscribe-id=\"{$this->form_id}\">
                        	 <div class=\"modal-dialog\" role=\"document\">
						        <div class=\"modal-content\">
						        {$close_button}
						        <div class=\"modal-body\">
						                	<form method=\"post\" accept-charset=\"utf-8\">
											    {$heading_text}
											    {$text}
											    <div class=\"input_field\">
											        <span class=\"input_wrap\"><input type=\"email\" name=\"email\" placeholder=\"{$button_placeholder}\" value=\"\"></span>
											        <button class=\"button\" type=\"submit\" {$disabled} data-process=\"Process...\">{$button_icon} {$button_text}</button>
											    </div>
											    <div class=\"form-message\"></div>
											    <div class=\"submit\">
											    	<input type=\"hidden\" name=\"after_submit\" value={$after_submit}>
											    	<input type=\"hidden\" name=\"file_id\" value={$file_id}>
											        <input type=\"hidden\" name=\"action\" value=\"jnews_subscribe_handler\">
											        <input type=\"hidden\" name=\"jnews-subscribe-nonce\" value=\"{$jnews_subscribe_nonce}\">
											    </div>
											    {$agreement_content}
											</form>
						            </div>
						        </div>
						    </div>
						</div>
                    </div>";

		if ( $echo ) {
			echo $output;
		}

		return $output;
	}

}
