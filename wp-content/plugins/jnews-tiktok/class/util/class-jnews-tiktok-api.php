<?php

namespace JNews\Tiktok\Util;

/**
 * JNews Tiktok Element
 *
 * @package jnews-tiktok
 *
 * @author Jegtheme
 *
 * @since 1.0.0
 */
class JNews_Tiktok_Api {
	/**
	 * @param $username
	 * @param $method
	 * @return mixed
	 */
	public function get_feed_id( $username, $method ) {
		$url      = jnews_tiktok_get_url( 'id', $username, $method );
		$response = $this->remote_get( $url, $method );

		if ( ! empty( $response ) ) {
			if ( 'username' === $method ) {
				if ( preg_match( '/<script id="__NEXT_DATA__"([^>]+)>([^<]+)<\/script>/', $response, $matches ) ) {
					$result = json_decode( $matches[2], false );

					if ( isset( $result->props->pageProps->userInfo->user->id ) ) {
						return $result->props->pageProps->userInfo->user->id;
					}
				}
			} elseif ( 'hastag' === $method ) {
				return isset( $response['challengeInfo'] ) && isset( $response['challengeInfo']['challenge'] ) && isset( $response['challengeInfo']['challenge']['id'] ) ? $response['challengeInfo']['challenge']['id'] : false;
			}
		}

		return false;
	}

	/**
	 * @param $id
	 * @param $method
	 * @return mixed
	 */
	public function get_feed( $id, $method ) {
		$url      = jnews_tiktok_get_url(
			'feed',
			false,
			$method,
			array(
				'id'   => $id,
				'type' => 'username' === $method ? '1' : '3',
			)
		);
		$response = $this->remote_get( $url );

		if ( ! empty( $response ) ) {
			return $response;
		}

		return false;
	}

	/**
	 * @param $url
	 * @return mixed
	 */
	private function remote_get( $url, $method = false ) {
		$response = wp_remote_get(
			$url,
			array(
				'timeout'    => 30,
				'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36',
			)
		);

		if ( 'username' === $method ) {
			if ( isset( $response['body'] ) ) {
				return $response['body'];
			}
		} else {
			if ( ! is_string( $response ) && ! empty( $response ) && ! is_wp_error( $response ) ) {
				$body = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( isset( $body['body'] ) ) {
					$body = $body['body'];
				}

				return $body;
			}
		}

		return false;
	}
}
