<?php

namespace JNews\Module\Widget;

use JNews\Module\Widget\WidgetOptionAbstract;

/**
 * JNews Tiktok Element
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-tiktok
 */
class Widget_Tiktok_Option extends WidgetOptionAbstract {
	public function get_module_name() {
		return esc_html__( 'JNews - Tiktok Widget', 'jnews-tiktok' );
	}
}
