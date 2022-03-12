<?php
/**
 * @author : Jegtheme
 */

/**
 * Class JNews_Element_Subscribe_View
 */
class JNews_Element_Subscribe_View extends \JNews\Module\ModuleViewAbstract {
	public function render_module( $attr, $column_class ) {

		if ( ! class_exists( 'JNews_Subscribe_Element' ) ) {
			require_once JNEWS_SUBSCRIBE_DIR . '/class/Module/Element/class.jnews-subscribe-form.php';
		}
		if (
			( isset( $attr['file_id'] ) && ! empty( $attr['file_id'] ) ) &&
			( isset( $attr['add_action'] ) && ! empty( $attr['add_action'] ) )
		) {
			$param     = array(
				'form_id'              => $this->unique_id,
				'element_id'           => $this->element_id( $attr ),
				'get_vc_class_name'    => $this->get_vc_class_name(),
				'el_class'             => $attr['el_class'],
				'download_type'        => $attr['download_type'],
				'file_id'              => $attr['file_id'],
				'add_action'           => $attr['add_action'],
				'heading_type'         => $attr['heading_type'],
				'heading_text'         => $attr['heading_text'],
				'button_placeholder'   => $attr['button_placeholder'],
				'text'                 => $attr['text'],
				'button_icon'          => $attr['button_icon'],
				'button_text'          => $attr['button_text'],
				'button_download_icon' => $attr['button_download_icon'],
				'button_download_text' => $attr['button_download_text'],
				'agreement'            => $attr['agreement'],
				'agreement_1'          => $attr['agreement_1'],
				'agreement_2'          => $attr['agreement_2'],
			);
			$subscribe = new \JNEWS_SUBSCRIBE\Module\JNews_Subscribe_Element( $param );

			return $subscribe->generate_element( false );
		} else {
			return '';
		}
	}
}
