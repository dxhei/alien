<?php

/**
 * @author Jegtheme
 */

namespace JNews\Tiktok\Util;

/**
 * Class JNews\Tiktok\Util
 */
class JNews_Tiktok_Render {
	/**
	 * @var integer
	 */
	private $row;
	private $column;
	private $count;

	/**
	 * @var string
	 */
	private $cache_key = 'jnews_footer_tiktok_cache';
	private $type;
	private $feed;
	private $content;
	private $sort;
	private $hover;
	private $open;
	private $button;
	private $layout;
	private $cover;
	private $size;

	/**
	 * JNews\Tiktok\Util constructor
	 */
	public function __construct( $param, $row = 1 ) {
		$this->row    = isset( $param['row']['size'] ) ? $param['row']['size'] : $param['row'];
		$this->column = isset( $param['column']['size'] ) ? $param['column']['size'] : $param['column'];
		$this->type   = $param['type'];
		$this->feed   = 'username' === $this->type ? $param['username'] : $param['hastag'];
		$this->sort   = $param['sort'];
		$this->hover  = $param['hover'];
		$this->open   = $param['open'];
		$this->button = $param['button'];
		$this->layout = $param['layout'];
		$this->cover  = $param['cover'];
		$this->size   = 'rectangle' === $this->layout ? '1780' : '1000';
		$this->count  = $this->row * $this->column;
		$this->api    = new JNews_Tiktok_Api();
	}

	/**
	 * @return Tiktok_Render
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function render_content( $data ) {
		$content = $like = '';
		$data    = array_slice( $data, 0, $this->count );

		if ( ! empty( $data ) && is_array( $data ) ) {
			switch ( $this->sort ) {
				case 'most_recent':
					usort(
						$data,
						function ( $a, $b ) {
							return $b['time'] - $a['time'];
						}
					);
					break;
				case 'least_recent':
					usort(
						$data,
						function ( $a, $b ) {
							return $a['time'] - $b['time'];
						}
					);
					break;
				case 'most_like':
					usort(
						$data,
						function ( $a, $b ) {
							return $b['like'] - $a['like'];
						}
					);
					break;
				case 'least_like':
					usort(
						$data,
						function ( $a, $b ) {
							return $a['like'] - $b['like'];
						}
					);
					break;
				case 'most_comment':
					usort(
						$data,
						function ( $a, $b ) {
							return $b['comment'] - $a['comment'];
						}
					);
					break;
				case 'least_comment':
					usort(
						$data,
						function ( $a, $b ) {
							return $a['comment'] - $b['comment'];
						}
					);
					break;
			}

			$a = 1;
			foreach ( $data as $image ) {
				if ( $this->hover == 'like' ) {
					$like = "<i class='fa fa-heart'>" . jnews_number_format( $image['like'] ) . '</i>';
				} elseif ( $this->hover == 'comment' ) {
					$like = "<i class='fa fa-comments'>" . jnews_number_format( $image['comment'] ) . '</i>';
				}

				$image_tag = apply_filters( 'jnews_single_image', $image['images']['thumbnail'], $image['caption'], $this->size );

				$content .=
					"<li>
                        <a href='{$image[ 'link' ]}' {$this->open}>
                            {$like}
                            {$image_tag}
                        </a>
                    </li>";

				if ( $a >= ( $this->row * $this->column ) ) {
					break;
				}

				$a ++;
			}
		}

		$this->content = $content;
	}

	public function scrap_data() {
		$id   = $this->api->get_feed_id( $this->feed, $this->type );
		$feed = $this->api->get_feed( $id, $this->type );

		if ( isset( $feed['itemListData'] ) ) {
			$data_images = array();
			foreach ( $feed['itemListData'] as $key => $item ) {
				$data_images[] = array(
					'id'      => $item['itemInfos']['id'],
					'time'    => $item['itemInfos']['createTime'],
					'like'    => $item['itemInfos']['diggCount'],
					'comment' => $item['itemInfos']['commentCount'],
					'caption' => $item['itemInfos']['text'],
					'link'    => jnews_tiktok_get_url( 'video', $item['authorInfos']['uniqueId'], false, array( 'id' => $item['itemInfos']['id'] ) ),
					'images'  => array(
						'display'   => $item['itemInfos']['coversDynamic'][0],
						'thumbnail' => $item['itemInfos']['coversDynamic'][0],
					),
				);

				switch ( $this->cover ) {
					case 'origin':
						$data_images[ $key ]['images'] = array(
							'display'   => $item['itemInfos']['coversOrigin'][0],
							'thumbnail' => $item['itemInfos']['coversOrigin'][0],
						);
						break;
					case 'play':
						$data_images[ $key ]['images'] = array(
							'display'   => $item['itemInfos']['shareCover'][1],
							'thumbnail' => $item['itemInfos']['shareCover'][1],
						);
						break;
					case 'dynamic':
						$data_images[ $key ]['images'] = array(
							'display'   => $item['itemInfos']['coversDynamic'][0],
							'thumbnail' => $item['itemInfos']['coversDynamic'][0],
						);
						break;
					case 'cover':
					default:
						$data_images[ $key ]['images'] = array(
							'display'   => $item['itemInfos']['covers'][0],
							'thumbnail' => $item['itemInfos']['covers'][0],
						);
						break;
				}
			}

			return $data_images;
		}

		return null;
	}

	/**
	 * Check data cached
	 */
	protected function check_cache() {
		// delete_option( $this->get_cache_key() );
		$now    = current_time( 'timestamp' );
		$expire = 60 * 60 * 6;

		$temp_cached = array();
		$data_cached = get_option( $this->get_cache_key() );

		if ( empty( $data_cached ) ) {
			$data_cached = array();
		}

		$update_feed = false;
		$add_feed    = true;

		if ( ! empty( $data_cached ) && is_array( $data_cached ) ) {
			foreach ( $data_cached as $data ) {
				if ( 'username' === $this->type ) {
					if ( $data['username'] == $this->feed ) {
						if ( count( $data['feed'] ) >= $this->count ) {
							if ( $data['time'] > ( $now - $expire ) ) {
								// !expired
								$this->render_content( $data['feed'] );
							} else {
								// expired
								$update_feed = true;
								$data_scrap  = $this->scrap_data();

								if ( ! empty( $data_scrap ) ) {
									$data['feed'] = $data_scrap;
									$data['time'] = current_time( 'timestamp' );
								}

								$this->render_content( $data['feed'] );
							}
						} else {

							$update_feed = true;
							$data_scrap  = $this->scrap_data();

							if ( ! empty( $data_scrap ) ) {
								$data['feed'] = $data_scrap;
								$data['time'] = current_time( 'timestamp' );
							}

							$this->render_content( $data['feed'] );
						}

						$add_feed = false;
					}
				} else {
					if ( $data['hastag'] == $this->feed ) {
						if ( count( $data['feed'] ) >= $this->count ) {
							if ( $data['time'] > ( $now - $expire ) ) {
								// !expired
								$this->render_content( $data['feed'] );
							} else {
								// expired
								$update_feed = true;
								$data_scrap  = $this->scrap_data();

								if ( ! empty( $data_scrap ) ) {
									$data['feed'] = $data_scrap;
									$data['time'] = current_time( 'timestamp' );
								}

								$this->render_content( $data['feed'] );
							}
						} else {

							$update_feed = true;
							$data_scrap  = $this->scrap_data();

							if ( ! empty( $data_scrap ) ) {
								$data['feed'] = $data_scrap;
								$data['time'] = current_time( 'timestamp' );
							}

							$this->render_content( $data['feed'] );
						}

						$add_feed = false;
					}
				}

				$temp_cached[] = $data;
			}
		}

		if ( $add_feed ) {
			$data_scrap = $this->scrap_data();

			if ( ! empty( $data_scrap ) ) {
				$array[] = array(
					'username' => 'username' === $this->type ? $this->feed : '',
					'hastag'   => 'hastag' === $this->type ? $this->feed : '',
					'time'     => current_time( 'timestamp' ),
					'feed'     => $data_scrap,
				);

				$array = array_merge( $data_cached, $array );

				update_option( $this->get_cache_key(), $array );

				$this->render_content( $data_scrap );
			}
		}

		if ( $update_feed ) {
			update_option( $this->get_cache_key(), $temp_cached );
		}
	}

	/**
	 * Generate element for Tiktok feed
	 */
	public function generate_element( $echo = true ) {
		$view_button = '';
		$view_link   = jnews_tiktok_get_url( 'view', $this->feed, $this->type );

		if ( $view_button_option = $this->button ) {
			$view_button =
				"<h3 class='jeg_tiktok_heading'>
                    <a href='{$view_link}' {$this->open} class='jeg_btn-tiktok'>
                        <i class='fa fa-tiktok'></i>
                        <span>" . esc_html( $view_button_option ) . '</span>
                    </a>
                </h3>';
		}

		$this->check_cache();

		$output = "<div class='jeg_tiktok_feed clearfix'>
                        {$view_button}
                        <ul class='tiktok-pics tiktok-size-large col{$this->column} {$this->hover}'>{$this->content}</ul>
                    </div>";

		if ( $echo ) {
			echo jnews_sanitize_output( $output );
		}

		return $output;
	}

	private function get_cache_key() {
		return $this->cache_key . '_' . $this->type . '_' . $this->feed . '_' . $this->cover;
	}

}
