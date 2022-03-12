<?php
/**
 * @author Jegtheme
 */

namespace JNews\PAY_WRITER;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper
 */
class Helper {

	/**
	 * Localizes the Vanillajs datepicker.
	 *
	 * @global WP_Locale $wp_locale WordPress date and time locale object.
	 */
	public static function wp_localize_vanillajs_datepicker() {
		global $wp_locale;

		if ( ! wp_script_is( 'vanillajs-datepicker', 'enqueued' ) ) {
			return;
		}

		// Convert the PHP date format into jQuery UI's format.
		$datepicker_date_format = str_replace(
			array(
				'd',
				'j',
				'l',
				'z', // Day.
				'F',
				'M',
				'n',
				'm', // Month.
				'Y',
				'y', // Year.
			),
			array(
				'dd',
				'd',
				'DD',
				'o',
				'MM',
				'M',
				'm',
				'mm',
				'yy',
				'y',
			),
			get_option( 'date_format' )
		);

		$datepicker_defaults = wp_json_encode(
			array(
				'days'        => array_values( $wp_locale->weekday ),
				'daysShort'   => array_values( $wp_locale->weekday_abbrev ),
				'daysMin'     => array_values( $wp_locale->weekday_initial ),
				'months'      => array_values( $wp_locale->month ),
				'monthsShort' => array_values( $wp_locale->month_abbrev ),
				'today'       => __( 'Today' ),
				'clear'       => __( 'Clear' ),
				'weekStart'   => absint( get_option( 'start_of_week' ) ),
				'format'      => $datepicker_date_format,
				'titleFormat' => 'MM y',
			)
		);

		wp_add_inline_script( 'vanillajs-datepicker', "(function(){Datepicker.locales.en={$datepicker_defaults}})()" );
	}

	/**
	 * Check if the current page is elementor editor
	 */
	public static function is_elementor_editor() {
		return is_admin() && ( isset( $_GET['action'] ) && sanitize_text_field( $_GET['action'] ) === 'elementor' );
	}

	/**
	 * Encrypt Data
	 *
	 * @param string $data
	 * @return string $encrypted_data
	 */
	public static function encrypt_data( $data ) {
		$auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
		$auth_iv  = defined( 'NONCE_KEY' ) ? NONCE_KEY : '';

		// mcrypt strong encryption
		if ( function_exists( 'mcrypt_encrypt' ) && defined( 'MCRYPT_BLOWFISH' ) ) {
			// get max key size of the mcrypt mode
			$max_key_size = mcrypt_get_key_size( MCRYPT_BLOWFISH, MCRYPT_MODE_CBC );
			$max_iv_size  = mcrypt_get_iv_size( MCRYPT_BLOWFISH, MCRYPT_MODE_CBC );

			$encrypt_key = mb_strimwidth( $auth_key, 0, $max_key_size );
			$encrypt_iv  = mb_strimwidth( $auth_iv, 0, $max_iv_size );

			$encrypted_data = strtr( base64_encode( mcrypt_encrypt( MCRYPT_BLOWFISH, $encrypt_key, $data, MCRYPT_MODE_CBC, $encrypt_iv ) ), '+/=', '-_,' );
			// simple encryption
		} elseif ( function_exists( 'gzdeflate' ) ) {
			$encrypted_data = base64_encode( convert_uuencode( gzdeflate( $data, 9 ) ) );
		}
		// no encryption
		else {
			$encrypted_data = strtr( base64_encode( convert_uuencode( $data ) ), '+/=', '-_,' );
		}

		return $encrypted_data;
	}

	/**
	 * Decrypt user IP
	 *
	 * @param string $encrypted_data
	 * @return string $data
	 */
	public static function decrypt_data( $encrypted_data ) {
		$auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
		$auth_iv  = defined( 'NONCE_KEY' ) ? NONCE_KEY : '';

		// mcrypt strong encryption
		if ( function_exists( 'mcrypt_decrypt' ) && defined( 'MCRYPT_BLOWFISH' ) ) {
			// get max key size of the mcrypt mode
			$max_key_size = mcrypt_get_key_size( MCRYPT_BLOWFISH, MCRYPT_MODE_CBC );
			$max_iv_size  = mcrypt_get_iv_size( MCRYPT_BLOWFISH, MCRYPT_MODE_CBC );

			$encrypt_key = mb_strimwidth( $auth_key, 0, $max_key_size );
			$encrypt_iv  = mb_strimwidth( $auth_iv, 0, $max_iv_size );

			$data = mcrypt_decrypt( MCRYPT_BLOWFISH, $encrypt_key, base64_decode( strtr( $encrypted_data, '-_,', '+/=' ) ), MCRYPT_MODE_CBC, $encrypt_iv );
			// simple encryption
		} elseif ( function_exists( 'gzinflate' ) ) {
			$data = gzinflate( convert_uudecode( base64_decode( $encrypted_data ) ) );
			// no encryption
		} else {
			$data = convert_uudecode( base64_decode( strtr( $encrypted_data, '-_,', '+/=' ) ) );
		}

		return $data;
	}

	/**
	 * @param null $object
	 *
	 * Logging Variable/Object in php_error_log file
	 * Note : Use this for variable/object that cannot be printed to a html page
	 */
	public static function logging( $object = null ) {
		if ( function_exists( 'jnews_log' ) ) {
			jnews_log( $object );
		}
	}

	/**
	 * get_all_currencies
	 *
	 * @return array
	 */
	public static function get_all_currencies( $flip = false ) {
		static $currencies;

		if ( ! isset( $currencies ) ) {
			$currencies = array_unique(
				apply_filters(
					'jpwt_currencies',
					array(
						'AUD' => __( 'Australian dollar - AUD', 'jnews-pay-writer' ),
						'BRL' => __( 'Brazilian real - BRL', 'jnews-pay-writer' ),
						'CAD' => __( 'Canadian dollar - CAD', 'jnews-pay-writer' ),
						'CNY' => __( 'Chinese Renmenbi - CNY', 'jnews-pay-writer' ),
						'CZK' => __( 'Czech koruna - CZK', 'jnews-pay-writer' ),
						'DKK' => __( 'Danish krone - DKK', 'jnews-pay-writer' ),
						'EUR' => __( 'Euro - EUR', 'jnews-pay-writer' ),
						'HKD' => __( 'Hong Kong dollar - HKD', 'jnews-pay-writer' ),
						'HUF' => __( 'Hungarian forint - HUF', 'jnews-pay-writer' ),
						'ILS' => __( 'Israeli new shekel - ILS', 'jnews-pay-writer' ),
						'JPY' => __( 'Japanese yen - JPY', 'jnews-pay-writer' ),
						'MYR' => __( 'Malaysian ringgit - MYR', 'jnews-pay-writer' ),
						'MXN' => __( 'Mexican peso - MXN', 'jnews-pay-writer' ),
						'TWD' => __( 'New Taiwan dollar - TWD', 'jnews-pay-writer' ),
						'NZD' => __( 'New Zealand dollar - NZD', 'jnews-pay-writer' ),
						'NOK' => __( 'Norwegian krone - NOK', 'jnews-pay-writer' ),
						'PHP' => __( 'Philippine peso - PHP', 'jnews-pay-writer' ),
						'PLN' => __( 'Polish złoty - PLN', 'jnews-pay-writer' ),
						'GBP' => __( 'Pound sterling - GBP', 'jnews-pay-writer' ),
						'RUB' => __( 'Russian ruble - RUB', 'jnews-pay-writer' ),
						'SGD' => __( 'Singapore dollar - SGD', 'jnews-pay-writer' ),
						'SEK' => __( 'Swedish krona - SEK', 'jnews-pay-writer' ),
						'CHF' => __( 'Swiss franc - CHF', 'jnews-pay-writer' ),
						'THB' => __( 'Thai baht - THB', 'jnews-pay-writer' ),
						'USD' => __( 'United States dollar - USD', 'jnews-pay-writer' ),
					)
				)
			);
		}

		if ( $flip ) {
			$currencies = array_flip( $currencies );
		}

		return $currencies;
	}

	/**
	 * We need to detect currency
	 *
	 * @param string $currency
	 *
	 * @return array
	 */
	public static function get_currency_locale( $currency ) {
		$currency_locale = array();
		switch ( $currency ) {
			case 'AUD':
			case 'CAD':
			case 'CNY':
			case 'CZK':
			case 'EUR':
			case 'HKD':
			case 'ILS':
			case 'MYR':
			case 'MXN':
			case 'NZD':
			case 'NOK':
			case 'PHP':
			case 'GBP':
			case 'RUB':
			case 'SGD':
			case 'SEK':
			case 'THB':
			case 'USD':
				$currency_locale = array(
					'currency_pos' => 'left_space',
					'thousand_sep' => ',',
					'decimal_sep'  => '.',
					'num_decimals' => 2,
				);
				break;
			case 'BRL':
				$currency_locale = array(
					'currency_pos' => 'left_space',
					'thousand_sep' => '.',
					'decimal_sep'  => ',',
					'num_decimals' => 2,
				);
				break;
			case 'TWD':
			case 'JPY':
				$currency_locale = array(
					'currency_pos' => 'left_space',
					'thousand_sep' => ',',
					'decimal_sep'  => '.',
					'num_decimals' => 0,
				);
				break;
			case 'CHF':
				$currency_locale = array(
					'currency_pos' => 'left_space',
					'thousand_sep' => "'",
					'decimal_sep'  => '.',
					'num_decimals' => 2,
				);
				break;
			case 'PLN':
				$currency_locale = array(
					'currency_pos' => 'right_space',
					'thousand_sep' => ' ',
					'decimal_sep'  => ',',
					'num_decimals' => 2,
				);
				break;
			case 'HUF':
				$currency_locale = array(
					'currency_pos' => 'right_space',
					'thousand_sep' => ' ',
					'decimal_sep'  => ',',
					'num_decimals' => 0,
				);
				break;
			case 'DKK':
				$currency_locale = array(
					'currency_pos' => 'left_space',
					'thousand_sep' => '.',
					'decimal_sep'  => ',',
					'num_decimals' => 2,
				);
				break;
		}
		return $currency_locale;
	}

	/**
	 * Loads a template part into a template.
	 *
	 * @param  string $slug
	 * @param  string $name
	 */
	public static function get_template_part( $slug, $name = null ) {
		if ( function_exists( 'jnews_get_template_part' ) ) {
			jnews_get_template_part( $slug, $name, JNEWS_PAY_WRITER_DIR );
		}
	}

	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	public static function jpwt_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'JNews\PAY_WRITER\Helper::jpwt_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}


	/**
	 * Wrapper for nocache_headers which also disables page caching.
	 */
	public static function nocache_headers() {
		self::maybe_define_constant( 'DONOTCACHEPAGE', true );
		self::maybe_define_constant( 'DONOTCACHEOBJECT', true );
		self::maybe_define_constant( 'DONOTCACHEDB', true );
		nocache_headers();
	}

	/**
	 * Define a constant if it is not already defined.
	 *
	 * @param string $name  Constant name.
	 * @param mixed  $value Value.
	 */
	public static function maybe_define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * @param  string|array $template_names
	 * @param  boolean      $load
	 * @param  boolean      $require_once
	 * @return string
	 */
	public static function get_template_path( $template_names, $load = false, $require_once = true ) {
		if ( function_exists( 'jnews_get_template_path' ) ) {
			return jnews_get_template_path( $template_names, $load, $require_once, JNEWS_PAY_WRITER_DIR );
		}
	}

	/**
	 * Get view counter option
	 *
	 * @param  string $setting
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get_pay_writer_option( $setting, $default = false ) {
		if ( 'config' === $setting && get_current_user_id() ) {
			$user_id = get_current_user_id();
			$options = get_user_option( 'jnews_option', $user_id );
			$options = $options ? $options : array();
			$value   = $default;
			if ( isset( $options['pay_writer'] ) && isset( $options['pay_writer'][ $setting ] ) ) {
				$value = $options['pay_writer'][ $setting ];
			}

			return apply_filters( "jnews_option_pay_writer_{$setting}", $value );
		} else {
			$options = get_option( 'jnews_option', array() );
			$value   = $default;
			if ( isset( $options['pay_writer'] ) && isset( $options['pay_writer'][ $setting ] ) ) {
				$value = $options['pay_writer'][ $setting ];
			}

			return apply_filters( "jnews_option_pay_writer_{$setting}", $value );
		}
	}

	/**
	 * Get view counter payment option
	 *
	 * @param  string $setting
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get_payment_option( $setting, $default = false ) {
		$options = self::get_pay_writer_option( 'payment', JNews_Pay_Writer()->options['payment'] );
		$value   = $default;
		if ( isset( $options[ $setting ] ) ) {
			$value = $options[ $setting ];
		}

		return apply_filters( "jnews_option_pay_writer_payment_{$setting}", $value );
	}

	/**
	 * Get view counter paypal option
	 *
	 * @param  string $setting
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get_paypal_option( $setting, $default = false ) {
		$options = self::get_pay_writer_option( 'paypal', JNews_Pay_Writer()->options['paypal'] );
		$value   = $default;
		if ( isset( $options[ $setting ] ) ) {
			$value = $options[ $setting ];
		}

		return apply_filters( "jnews_option_pay_writer_paypal_{$setting}", $value );
	}

	/**
	 * Get view counter general option
	 *
	 * @param  string $setting
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get_general_option( $setting, $default = false ) {
		$options = self::get_pay_writer_option( 'general', JNews_Pay_Writer()->options['general'] );
		$value   = $default;
		if ( isset( $options[ $setting ] ) ) {
			$value = $options[ $setting ];
		}

		return apply_filters( "jnews_option_pay_writer_general_{$setting}", $value );
	}

	/**
	 * Update view counter payment option
	 *
	 * @param  string $setting
	 * @param  mixed  $value
	 * @return mixed
	 */
	public static function update_payment_option( $setting, $value ) {
		$jnews_options       = get_option( 'jnews_option', array() );
		$options             = self::get_pay_writer_option( 'payment', JNews_Pay_Writer()->options['payment'] );
		$options[ $setting ] = $value;

		if ( isset( $jnews_options['pay_writer'] ) ) {
			if ( isset( $jnews_options['pay_writer']['payment'] ) ) {
				$jnews_options['pay_writer']['payment'][ $setting ] = $value;
			} else {
				$jnews_options['pay_writer']['payment'] = $options;
			}
		} else {
			$jnews_options['pay_writer'] = array(
				'payment' => $options,
			);
		}
		update_option( 'jnews_option', $jnews_options );
	}

	/**
	 * Update view counter paypal option
	 *
	 * @param  string $setting
	 * @param  mixed  $value
	 */
	public static function update_display_option( $setting, $value ) {
		$jnews_options       = get_option( 'jnews_option', array() );
		$options             = self::get_pay_writer_option( 'display', JNews_Pay_Writer()->options['display'] );
		$options[ $setting ] = $value;

		if ( isset( $jnews_options['pay_writer'] ) ) {
			if ( isset( $jnews_options['pay_writer']['display'] ) ) {
				$jnews_options['pay_writer']['display'][ $setting ] = $value;
			} else {
				$jnews_options['pay_writer']['display'] = $options;
			}
		} else {
			$jnews_options['pay_writer'] = array(
				'display' => $options,
			);
		}
		update_option( 'jnews_option', $jnews_options );
	}

	/**
	 * Update view counter paypal option
	 *
	 * @param  string $setting
	 * @param  mixed  $value
	 */
	public static function update_paypal_option( $setting, $value ) {
		$jnews_options       = get_option( 'jnews_option', array() );
		$options             = self::get_pay_writer_option( 'paypal', JNews_Pay_Writer()->options['paypal'] );
		$options[ $setting ] = $value;

		if ( isset( $jnews_options['pay_writer'] ) ) {
			if ( isset( $jnews_options['pay_writer']['paypal'] ) ) {
				$jnews_options['pay_writer']['paypal'][ $setting ] = $value;
			} else {
				$jnews_options['pay_writer']['paypal'] = $options;
			}
		} else {
			$jnews_options['pay_writer'] = array(
				'paypal' => $options,
			);
		}
		update_option( 'jnews_option', $jnews_options );
	}

	/**
	 * Update view counter general option
	 *
	 * @param  string $setting
	 * @param  mixed  $value
	 * @return mixed
	 */
	public static function update_general_option( $setting, $value ) {
		$jnews_options       = get_option( 'jnews_option', array() );
		$options             = self::get_pay_writer_option( 'general', JNews_Pay_Writer()->options['general'] );
		$options[ $setting ] = $value;

		if ( isset( $jnews_options['pay_writer'] ) ) {
			if ( isset( $jnews_options['pay_writer']['general'] ) ) {
				$jnews_options['pay_writer']['general'][ $setting ] = $value;
			} else {
				$jnews_options['pay_writer']['general'] = $options;
			}
		} else {
			$jnews_options['pay_writer'] = array(
				'general' => $options,
			);
		}
		update_option( 'jnews_option', $jnews_options );
	}

	/**
	 * Update view counter paypal option
	 *
	 * @param  string $setting
	 * @param  mixed  $value
	 */
	public static function update_global_option( $setting, $value ) {
		if ( 'config' === $setting && get_current_user_id() ) {
			$user_id       = get_current_user_id();
			$jnews_options = get_user_option( 'jnews_option', $user_id );
			$jnews_options = $jnews_options ? $jnews_options : array();
			$options       = array_merge( JNews_Pay_Writer()->options[ $setting ], $value );

			if ( isset( $jnews_options['pay_writer'] ) ) {
				$jnews_options['pay_writer'][ $setting ] = $options;
			} else {
				$jnews_options['pay_writer'] = array(
					$setting => $options,
				);
			}
			update_user_option( $user_id, 'jnews_option', $jnews_options );
		} else {
			$jnews_options = get_option( 'jnews_option', array() );
			$options       = array_merge( JNews_Pay_Writer()->options[ $setting ], $value );

			if ( isset( $jnews_options['pay_writer'] ) ) {
				$jnews_options['pay_writer'][ $setting ] = $options;
			} else {
				$jnews_options['pay_writer'] = array(
					$setting => $options,
				);
			}
			update_option( 'jnews_option', $jnews_options );
		}
	}
}
