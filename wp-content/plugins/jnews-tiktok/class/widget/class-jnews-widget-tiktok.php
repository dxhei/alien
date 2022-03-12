<?php

/**
 * @author : Jegtheme
 */

namespace JNews\Widget\Normal\Element;

use JNews\Widget\Normal\NormalWidgetInterface;
use JNews\Tiktok\Util\JNews_Tiktok_Api;

class TiktokWidget implements NormalWidgetInterface {
	/**
	 * @var string
	 */
	private $cache_key = 'jnews_tiktok_widget_cache';

	/**
	 * @var  integer
	 */
	private $count;

	public function get_options() {
		return array(
			'title'    => array(
				'title' => esc_html__( 'Title', 'jnews-tiktok' ),
				'desc'  => esc_html__( 'Title on widget header.', 'jnews-tiktok' ),
				'type'  => 'text',
			),

			'type'     => array(
				'title'   => esc_html__( 'Type', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Select type of TikTok feed', 'jnews-tiktok' ),
				'type'    => 'select',
				'default' => 'username',
				'options' => array(
					'username' => esc_html__( 'Username', 'jnews-tiktok' ),
					'hastag'   => esc_html__( 'Hastag', 'jnews-tiktok' ),
				),
			),

			'username' => array(
				'title'      => esc_html__( 'Tiktok Username', 'jnews-tiktok' ),
				'desc'       => esc_html__( 'Insert your Tiktok username (without @). ', 'jnews-tiktok' ),
				'type'       => 'text',
				'dependency' => array(
					array(
						'field'    => 'type',
						'operator' => 'in',
						'value'    => array( 'username' ),
					),
				),
			),

			'hastag'   => array(
				'title'      => esc_html__( 'Tiktok Hastag', 'jnews-tiktok' ),
				'desc'       => esc_html__( 'Insert hastag that you want to add (without #). ', 'jnews-tiktok' ),
				'type'       => 'text',
				'dependency' => array(
					array(
						'field'    => 'type',
						'operator' => 'in',
						'value'    => array( 'hastag' ),
					),
				),
			),

			'column'   => array(
				'title'   => esc_html__( 'Set Column', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Choose number of column widget.', 'jnews-tiktok' ),
				'type'    => 'select',
				'default' => 3,
				'options' => array(
					2 => esc_html__( '2 Columns', 'jnews-tiktok' ),
					3 => esc_html__( '3 Columns', 'jnews-tiktok' ),
					4 => esc_html__( '4 Columns', 'jnews-tiktok' ),
				),
			),

			'row'      => array(
				'title'   => esc_html__( 'Set Row', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Choose number of row widget.', 'jnews-tiktok' ),
				'type'    => 'slider',
				'options' => array(
					'min'  => '1',
					'max'  => '10',
					'step' => '1',
				),
				'default' => 3,
			),

			'hover'    => array(
				'title'   => esc_html__( 'Hover Style', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Choose hover effect style.', 'jnews-tiktok' ),
				'type'    => 'select',
				'default' => 'normal',
				'options' => array(
					'normal'      => esc_html__( 'Normal', 'jnews-tiktok' ),
					'icon'        => esc_html__( 'Show Icon', 'jnews-tiktok' ),
					'like'        => esc_html__( 'Show Like Count', 'jnews-tiktok' ),
					'comment'     => esc_html__( 'Show Comment Count', 'jnews-tiktok' ),
					'zoom'        => esc_html__( 'Zoom', 'jnews-tiktok' ),
					'zoom-rotate' => esc_html__( 'Zoom Rotate', 'jnews-tiktok' ),
					''            => esc_html__( 'No Effect', 'jnews-tiktok' ),
				),
			),

			'sort'     => array(
				'title'   => esc_html__( 'Sort Type', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Choose sort type.', 'jnews-tiktok' ),
				'type'    => 'select',
				'default' => 'most_recent',
				'options' => array(
					'most_recent'   => esc_html__( 'Most Recent', 'jnews-tiktok' ),
					'least_recent'  => esc_html__( 'Least Recent', 'jnews-tiktok' ),
					'most_like'     => esc_html__( 'Most Liked', 'jnews-tiktok' ),
					'least_like'    => esc_html__( 'Least Liked', 'jnews-tiktok' ),
					'most_comment'  => esc_html__( 'Most Commented', 'jnews-tiktok' ),
					'least_comment' => esc_html__( 'Least Commented', 'jnews-tiktok' ),
				),
			),

			'button'   => array(
				'title' => esc_html__( 'View Button Text', 'jnews-tiktok' ),
				'desc'  => esc_html__( 'Leave it empty if you wont to show it.', 'jnews-tiktok' ),
				'type'  => 'text',
			),

			'layout'   => array(
				'title'   => esc_html__( 'Layout Type', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Choose layout type.', 'jnews-tiktok' ),
				'type'    => 'select',
				'default' => 'rectangle',
				'options' => array(
					'rectangle' => esc_html__( 'Rectangle', 'jnews-tiktok' ),
					'square'    => esc_html__( 'Square', 'jnews-tiktok' ),
				),
			),

			'cover'    => array(
				'title'   => esc_html__( 'Cover Type', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Choose cover type.', 'jnews-tiktok' ),
				'type'    => 'select',
				'default' => 'cover',
				'options' => array(
					'cover'   => esc_html__( 'Cover', 'jnews-tiktok' ),
					'origin'  => esc_html__( 'Origin', 'jnews-tiktok' ),
					'play'    => esc_html__( 'Play', 'jnews-tiktok' ),
					'dynamic' => esc_html__( 'Dynamic', 'jnews-tiktok' ),
				),
			),

			'open'     => array(
				'title'   => esc_html__( 'Open New Tab', 'jnews-tiktok' ),
				'desc'    => esc_html__( 'Open Tiktok profile on the new tab.', 'jnews-tiktok' ),
				'type'    => 'checkbox',
				'default' => false,
			),
		);
	}


	public function render_widget( $instance, $text_content = null ) {
		if ( ( ! empty( $instance['username'] ) || ! empty( $instance['hastag'] ) ) && ! empty( $instance['type'] ) ) {
			$this->type        = $instance['type'];
			$this->feed        = 'username' === $this->type ? $instance['username'] : $instance['hastag'];
			$this->row         = $instance['row'];
			$this->column      = $instance['column'];
			$this->count       = $this->row * $this->column;
			$this->hover       = $instance['hover'];
			$this->sort        = $instance['sort'];
			$this->newtab      = $instance['open'] ? 'target=\'_blank\'' : '';
			$this->button_link = jnews_tiktok_get_url( 'view', $this->feed, $this->type );
			$this->size        = 'rectangle' === $instance['layout'] ? '1780' : '1000';
			$this->cover       = $instance['cover'];
			$this->api         = new JNews_Tiktok_Api();

			if ( ! empty( $instance['button'] ) ) {
				$this->button =
					'<h3 class=\'jeg_tiktok_widget_heading\'>
                    <a href=\'' . $this->button_link . '\' ' . $this->newtab . ' class=\'jeg_btn-tiktok\'><i class="fa fa-tiktok"></i><span>' . esc_html( $instance['button'] ) . '</span></a>
                </h3>';
			} else {
				$this->button = '';
			}

			$this->check_cache();
		}
	}


	protected function render_content( $data ) {
		$content = '';

		if ( ! empty( $data ) && is_array( $data ) ) {
			$data    = array_slice( $data, 0, $this->count );
			$data    = $this->sort_data( $data );
			$content = $this->build_content( $data );
		}

		$output =
			"<div class='jeg_tiktok_widget jeg_grid_thumb_widget clearfix'>
                {$this->button}    
                <ul class='tiktok-pics col{$this->column} {$this->hover}'>
                    {$content}
                </ul>
            </div>";

		echo jnews_sanitize_output( $output );
	}


	protected function build_content( $data ) {
		$content = $like = '';
		$a       = 1;

		foreach ( $data as $image ) {
			if ( $a % $this->column == 0 ) {
				$class = 'last';
			} elseif ( $a % $this->column == 1 ) {
				$class = 'first';
			} else {
				$class = '';
			}

			if ( $this->hover == 'like' ) {
				$like = "<i class='fa fa-heart'>" . jnews_number_format( $image['like'] ) . '</i>';
			} elseif ( $this->hover == 'comment' ) {
				$like = "<i class='fa fa-comments'>" . jnews_number_format( $image['comment'] ) . '</i>';
			}

			$image_tag = apply_filters( 'jnews_single_image', $image['images']['thumbnail'], $image['caption'], $this->size );

			$content .=
				"<li class='{$class}'>
                    <a href='{$image[ 'link' ]}' {$this->newtab}>
                        {$like}
                        {$image_tag}
                    </a>
                </li>";

			if ( $a >= ( $this->row * $this->column ) ) {
				break;
			}

			$a ++;
		}

		return $content;
	}


	protected function sort_data( $data ) {
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

		return $data;
	}


	protected function check_cache() {
		// delete_option( $this->get_cache_key() );
		$data_cache  = get_option( $this->get_cache_key(), array() );
		$now         = current_time( 'timestamp' );
		$data_feed   = null;
		$add_feed    = true;
		$update_feed = false;
		$expire      = 60 * 60 * 6; // set expire in 6 hour

		if ( ! empty( $data_cache ) && is_array( $data_cache ) ) {
			foreach ( $data_cache as &$data ) {
				if ( 'username' === $this->type ) {
					if ( $data['username'] == $this->feed ) {
						$add_feed = false;

						if ( count( $data['feed'] ) >= $this->count ) {
							if ( $data['time'] < ( $now - $expire ) ) {
								$data_feed = $this->fetch_data();

								if ( ! empty( $data_feed ) ) {
									$data['feed'] = $data_feed;
									$data['time'] = current_time( 'timestamp' );
									$update_feed  = true;
								}
							}
						} else {
							$data_feed = $this->fetch_data();

							if ( ! empty( $data_feed ) ) {
								$data['feed'] = $data_feed;
								$data['time'] = current_time( 'timestamp' );
								$update_feed  = true;
							}
						}

						$data_feed = $data['feed'];
					}
				} else {
					if ( $data['hastag'] == $this->feed ) {
						$add_feed = false;

						if ( count( $data['feed'] ) >= $this->count ) {
							if ( $data['time'] < ( $now - $expire ) ) {
								$data_feed = $this->fetch_data();

								if ( ! empty( $data_feed ) ) {
									$data['feed'] = $data_feed;
									$data['time'] = current_time( 'timestamp' );
									$update_feed  = true;
								}
							}
						} else {
							$data_feed = $this->fetch_data();

							if ( ! empty( $data_feed ) ) {
								$data['feed'] = $data_feed;
								$data['time'] = current_time( 'timestamp' );
								$update_feed  = true;
							}
						}

						$data_feed = $data['feed'];
					}
				}
			}
		}

		if ( $add_feed ) {
			$data_feed = $this->fetch_data();

			if ( ! empty( $data_feed ) ) {
				$data_cache[] = array(
					'username' => 'username' === $this->type ? $this->feed : '',
					'hastag'   => 'hastag' === $this->type ? $this->feed : '',
					'time'     => current_time( 'timestamp' ),
					'feed'     => $data_feed,
				);

			} else {
				$add_feed = false;
			}
		}

		if ( $add_feed || $update_feed ) {
			update_option( $this->get_cache_key(), $data_cache );
		}

		if ( ! empty( $data_feed ) ) {
			$this->render_content( $data_feed );
		}
	}


	protected function fetch_data() {
		$id   = $this->api->get_feed_id( $this->feed, $this->type );
		$feed = $this->api->get_feed( $id, $this->type, $this->count );

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

	protected function get_cache_key() {
		return $this->cache_key . '_' . $this->type . '_' . $this->feed . '_' . $this->cover;
	}

	protected function get_widget_template() {
	}
}
