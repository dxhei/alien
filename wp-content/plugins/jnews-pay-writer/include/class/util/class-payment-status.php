<?php
/**
 * @author : Jegtheme
 */

namespace JNews\PAY_WRITER\Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * JNews Pay Writer Paypal
 *
 * @author Jegtheme
 * @since 10.0.0
 * @package jnews-pay-writer
 */
class Payment_Status {

	private $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * @param \WP_Post $single
	 *
	 * @return array
	 */
	public function post_counting_status( $single ) {
		$counting = array();

		if ( in_array( 'view_payment', $this->settings['type'] ) && function_exists( 'jnews_view_counter_query' ) ) {
			$view = $this->get_total_views( $single );
			if ( $view > 0 ) {
				$counting['view'] = $view;
			}
		}

		if ( in_array( 'standard_payment', $this->settings['type'] ) ) {
			$counting['standard'] = $this->standard_payment();
		}

		if ( in_array( 'word_payment', $this->settings['type'] ) ) {
			$word = $this->get_total_words( $single );
			if ( $word > 0 ) {
				$counting['word'] = $word;
			}
		}

		return $counting;
	}

	/**
	 * @param \WP_Post|array $single
	 * @param int            $int
	 * @param int            $word_count
	 *
	 * @return array
	 */
	public function post_payment_status( $single, $view = null, $word_count = null ) {
		$payment = array(
			'total' => 0,
		);

		$view       = is_null( $view ) ? $this->get_total_views( $single ) : (int) $view;
		$word_count = is_null( $word_count ) ? $this->get_total_words( $single ) : $this->get_total_words( (object) array(), $word_count );

		if ( in_array( 'standard_payment', $this->settings['type'] ) ) {
			$payment['standard'] = $this->standard_payment( $payment['total'] );
		}
		if ( in_array( 'view_payment', $this->settings['type'] ) && function_exists( 'jnews_view_counter_query' ) ) {
			$payment['view']   = $this->view_payment( $view );
			$payment['total'] += $payment['view'];
		}
		if ( in_array( 'standard_payment', $this->settings['type'] ) ) {
			if ( $this->standard_payment( $payment['total'] ) ) {
				$payment['total'] = $payment['standard'];
			}
		}
		if ( in_array( 'word_payment', $this->settings['type'] ) ) {
			$payment['word']   = $this->word_payment( $word_count, $payment['total'] ) - $payment['total'];
			$payment['word']   = $payment['word'] < floatval( 0 ) ? floatval( 0 ) : $payment['word'];
			$payment['total'] += $payment['word'];
		}
		if ( $this->settings['max_amount'] > 0 ) {
			$payment['total'] = $payment['total'] > $this->settings['max_amount'] ? $this->settings['max_amount'] : $payment['total'];
		}

		return $payment;
	}

	/**
	 * @param \WP_Post $single
	 *
	 * @return int
	 */
	public function get_total_views( $single ) {
		$view = function_exists( 'jnews_view_counter_query' ) ? jnews_get_views( $single->ID, 'all', false ) : 0;
		return (int) $view;
	}

	/**
	 * @param \WP_Post $single
	 * @param string   $post_content
	 *
	 * @return int
	 */
	public function get_total_words( $single, $post_content = null ) {
		$content    = is_null( $post_content ) ? $single->post_content : $post_content;
		$content    = wp_strip_all_tags( $content );
		$content    = strip_shortcodes( $content );
		$content    = preg_replace( '/\.|,|:|;|\(|\)|"|\'/', '', $content );
		$word_count = str_word_count( $content );
		return $word_count;
	}

	public function standard_payment( $total = 0 ) {
		if ( $this->settings['standard_amount'] > 0 && $total <= $this->settings['standard_amount'] ) {
			return floatval( $this->settings['standard_amount'] );
		}
		return false;
	}

	public function view_payment( $view ) {
		return floatval( $view >= $this->settings['min_view'] ? $view * $this->settings['view_rate'] : 0 );
	}

	public function word_payment( $word_count, $total = 0 ) {
		return floatval( $word_count >= $this->settings['min_word'] ? ( $word_count * $this->settings['word_rate'] ) + $total : 0 );
	}

}
