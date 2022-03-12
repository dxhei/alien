<?php

use JNews\Module\ModuleViewAbstract;
use JNews\Tiktok\Util\JNews_Tiktok_Render;
use JNews\Tiktok\Util\Render;

/**
 * JNews Tiktok Element
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */
class JNews_Footer_Tiktok_View extends ModuleViewAbstract {

	public function render_module( $attr, $column_class ) {
		if ( ( isset( $attr['footer_tiktok_username'] ) && ! empty( $attr['footer_tiktok_username'] ) )
			 || ( isset( $attr['footer_tiktok_hastag'] ) && ! empty( $attr['footer_tiktok_hastag'] ) )
		) {
			$param = array(
				'row'      => $attr['footer_tiktok_row'],
				'column'   => $attr['footer_tiktok_column'],
				'type'     => $attr['footer_tiktok_type'],
				'username' => $attr['footer_tiktok_username'],
				'hastag'   => $attr['footer_tiktok_hastag'],
				'sort'     => $attr['footer_tiktok_sort_type'],
				'hover'    => $attr['footer_tiktok_hover_style'],
				'open'     => $attr['footer_tiktok_open'] ? 'target=\'_blank\'' : '',
				'layout'   => $attr['footer_tiktok_layout'],
				'button'   => $attr['footer_tiktok_view_button'],
				'cover'    => $attr['footer_tiktok_cover'],
			);

			$tiktok = new JNews_Tiktok_Render( $param );

			return $tiktok->generate_element( false );
		} else {
			return '';
		}
	}
}
