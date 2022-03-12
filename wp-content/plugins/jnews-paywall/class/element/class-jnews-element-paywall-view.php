<?php

use JNews\Module\ModuleViewAbstract;

/**
 * JNews Paywall Element
 *
 * @author Jegtheme
 * @since 1.0.0
 * @package jnews-paywall
 */
class JNews_Element_Paywall_View extends ModuleViewAbstract {

	public function render_module( $attr, $column_class ) {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}
		$output = $wrapper_class = $text_below_price = '';

		$attr['paywall_button'] = empty( $attr['paywall_button'] ) ? esc_html__( 'Buy', 'jnews-paywall' ) : $attr['paywall_button'];

		if ( $attr['paywall_list'] ) {
			$items = explode( ',', $attr['paywall_list'] );

			if ( count( $items ) <= 5 ) {
				$wrapper_class = 'col_' . count( $items );
			} else {
				$wrapper_class = 'col_' . apply_filters( 'jnews_paywall_default_column', 1 );
			}
			foreach ( $items as $item ) {
				$product = wc_get_product( $item );

				if ( $product ) {
					$total          = get_post_meta( $item, '_jpw_total', true );
					$duration       = get_post_meta( $item, '_jpw_duration', true );
					$description    = $attr['paywall_description'] ? '<div class=\'package-description\'>' . $product->get_short_description() . '</div>' : '';
					$image          = '';
					$featured       = $product->is_featured_package() ? 'featured' : '';
					$outline_button = ! $product->is_featured_package() ? 'btn outline' : '';

					if ( $product->is_type( 'paywall_subscribe' ) ) {
						$text_below_price = $this->duration_text( $total, $duration );
					} elseif ( $product->is_type( 'paywall_unlock' ) ) {
						$text_below_price = $product->get_total_unlock() . ' ' . jnews_return_translation( 'posts', 'jnews-paywall', 'posts_text_below_price' );
					}

					if ( wp_get_attachment_url( $product->get_image_id() ) ) {
						$image = '<div class=\'package-image\'><img src=' . wp_get_attachment_image_url( $product->get_image_id(), 'jnews-350x250' ) . '></div>';
					}

					$output .=
						'<div class=\'package-item ' . $featured . '\'>
                            <div class=\'package-title\'>
                                <h3>' . $product->get_title() . '</h3>
                            </div>
                            	' . $image . '
                            <div class=\'package-price\'>
                                <span class=\'price\'>' . $product->get_price_html() . '</span>
                                <span class=\'duration\'>/ ' . $text_below_price . ' </span>
                            </div>
                                ' . $description . '
                            <div class=\'package-button jpw-button\'>
                                <a href=\'\' class=\'button ' . $outline_button . '\' data-product_id=\'' . esc_attr( $item ) . '\' data-recurring=\'no\'><span>' . $attr['paywall_button'] . '</span><i class=\'fa fa-spinner fa-pulse\' style=\'display: none;\'></i></a>
                            </div>
                        </div>';
				} else {
					$output .= '<div class=\'jeg_empty_module\'>' . jnews_return_translation( 'No Content Available', 'jnews-paywall', 'no_content_available' ) . '</div>';
				}
			}
		}

		return '<div class=\'jpw-wrapper ' . $wrapper_class . ' clearfix\'>' . $output . '</div>';
	}

	public function duration_text( $total, $duration ) {
		switch ( $duration ) {
			case 'day':
				$text = jnews_return_translation( 'days', 'jnews-paywall', 'paywall_day' );
				break;

			case 'week':
				$text = jnews_return_translation( 'weeks', 'jnews-paywall', 'paywall_week' );
				break;

			case 'month':
				$text = jnews_return_translation( 'months', 'jnews-paywall', 'paywall_month' );
				break;

			case 'year':
				$text = jnews_return_translation( 'years', 'jnews-paywall', 'paywall_year' );
				break;
		}

		return $total . ' ' . $text;
	}
}
