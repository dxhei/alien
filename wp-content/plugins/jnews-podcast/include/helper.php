<?php
/**
 * @author Jegtheme
 */

if ( ! function_exists( 'jnews_podcast_add_media_menu' ) ) {
	/**
	 * Add media menu
	 *
	 * @param $id
	 * @param string $type
	 * @param string $more
	 *
	 * @return string
	 */
	function jnews_podcast_add_media_menu( $id, $type = 'podcast', $more = 'ellipsis' ) {
		$output     = '';
		$attribute  = '';
		$can_render = false;
		$content    = '';
		$more_icon  = 'plus' === $more ? 'fa fa-plus' : 'fa fa-ellipsis-v';
		switch ( $type ) {
			case 'podcast':
			case 'podcast_subscribe':
				$can_render   = true;
				$slug         = JNEWS_PODCAST\Series\Series::get_slug();
				$is_subscribe = ( 'podcast_subscribe' === $type );

				$main_button_class = $is_subscribe ? 'jeg_media_button subscribe' : 'jeg_media_button play';
				$main_button_url   = $is_subscribe ? jnews_podcast_feed_link( $id, $slug ) : 'javascript:;';
				$main_button_icon  = $is_subscribe ? '' : '<span class="initial"><i class="fa fa-play" aria-hidden="true"></i></span><span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>';
				$main_button_text  = $is_subscribe ? jnews_return_translation( 'Subscribe', 'jnews-podcast', 'subscribe_podcast' ) : jnews_return_translation( 'Play', 'jnews-podcast', 'play' );

				$attribute        = "class=\"jeg_media_option {$type}\" data-id=\"{$id}\"";
				$wide_button      = '<a href="' . $main_button_url . '" class="' . $main_button_class . '">' . $main_button_icon . '<span class="wide-button-text">' . $main_button_text . '</span></a>';
				$subscribe_button = '';
				if ( ! $is_subscribe ) {
					$subscribe_button = '
					<li>
						<a href="' . jnews_podcast_feed_link( $id, $slug ) . '"><i class="fa fa-rss"></i><span>' . jnews_return_translation( 'Subscribe', 'jnews-podcast', 'subscribe_podcast' ) . '</span></a>
					</li>';
				}

				$content =
					$wide_button .
					'<a href="javascript:;" class="jeg_media_button more" >' .
					'<i class="' . $more_icon . '"></i>' .
					'</a>' .
					'<ul class="jeg_moreoption">
								' . $subscribe_button . '
								<li>
									<a class="add_to_queue" href="javascript:;">
										<span class="initial"><i class="fa fa-plus-square-o"></i></span>
										<span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>
										<span>' . jnews_return_translation( 'Add to Queue', 'jnews-podcast', 'add_to_queue_podcast' ) . '</span>
									</a>
								</li>
								<li>
								' . jnews_podcast_share( $id ) . '
								</li>
					</ul>';
				if ( ! jnews_podcast_option( 'podcast_global_player', false ) ) {
					$can_render = false;
				}
				break;
			case 'episode':
			case 'episode_overlay':
			case 'episode_overlay_more':
			case 'episode_block':
			case 'single_episode':
				$can_render = true;
				$play       =
					'<a href="javascript:;" class="jeg_media_button play">
						<span class="initial"><i class="fa fa-play" aria-hidden="true"></i></span><span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>
					</a>';

				if ( 'single_episode' !== $type ) {
					$more_button =
						'<a href="javascript:;" class="jeg_media_button more ' . $type . '" >' .
						'<i class="' . $more_icon . '"></i>' .
						'</a>' .
						'<ul class="jeg_moreoption">
									<li>
										<a class="add_to_queue" href="javascript:;">
											<span class="initial"><i class="fa fa-plus-square-o"></i></span>
											<span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>
											<span>' . jnews_return_translation( 'Add to Queue', 'jnews-podcast', 'add_to_queue_podcast' ) . '</span>
										</a>
									</li>
									<li>
									' . jnews_podcast_share( $id, 'episode' ) . '
									</li>
						</ul>';

				} else {
					$more_button = '<a class="jeg_media_button add_to_queue" href="javascript:;">
										<span class="initial"><i class="fa fa-plus"></i></span>
										<span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>
										<span class="success"><i class="fa fa-check" aria-hidden="true"></i></span>
									</a>';
				}

				if ( 'episode_block' === $type ) {
					$play = '<a href="javascript:;" class="jeg_media_button play">' .
							'<span class="initial"><i class="fa fa-play" aria-hidden="true"></i></span>' .
							'<span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>' .
							'<span>' . jnews_return_translation( 'Play', 'jnews-podcast', 'play' ) . '</span>' .
							'</a>';
				}

				if ( 'single_episode' === $type ) {
					$play = '<a href="javascript:;" class="jeg_media_button play">' .
							'<span class="initial"><i class="fa fa-play" aria-hidden="true"></i></span>' .
							'<span class="loader"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span>' .
							'<span>' . jnews_return_translation( 'Play', 'jnews-podcast', 'play' ) . '</span>' .
							jnews_podcast_get_duration( $id, true, false, 'span' ) .
							'</a>';
				}

				$content   = $play . $more_button;
				$attribute = "class=\"jeg_media_option {$type}\" data-id=\"{$id}\"";
				if ( 'episode_overlay' === $type || 'episode_overlay_more' === $type ) {
					$content         = 'episode_overlay' === $type ? $play : $more_button;
					$attribute_class = 'episode_overlay' === $type ? 'overlay' : '';
					$type            = 'episode';
					$attribute       = "class=\"jeg_media_option {$type} {$attribute_class}\" data-id=\"{$id}\"";
				}

				if ( ! jnews_podcast_option( 'podcast_global_player', false ) ) {
					$can_render = false;
				}
				break;
		}

		if ( $can_render ) {
			$output .= '<div ' . $attribute . '>
							' . $content . '
						</div>';
		}

		return $output;
	}
}

if ( ! function_exists( 'jnews_podcast_share' ) ) {
	/**
	 * @param $id
	 * @param $type
	 *
	 * @return string
	 */
	function jnews_podcast_share( $id, $type = 'podcast' ) {
		$share_output = '';
		$main_button  = array(
			array(
				'social_share' => 'facebook',
				'social_text'  => 'Share on Facebook',
			),
			array(
				'social_share' => 'twitter',
				'social_text'  => 'Share on Twitter',
			),
			array(
				'social_share' => 'pinterest',
				'social_text'  => '',
			),
		);
		$socials      = jnews_get_option( 'single_social_share_main', $main_button );

		if ( is_array( $socials ) ) {
			foreach ( $socials as $social ) {
				switch ( $social['social_share'] ) {
					case 'facebook':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-facebook"><i class="fa fa-facebook-official"></i> <span>' . esc_html__( 'Facebook', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'twitter':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-twitter"><i class="fa fa-twitter"></i> <span>' . esc_html__( 'Twitter', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'googleplus':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-google-plus "><i class="fa fa-google-plus"></i> <span>' . esc_html__( 'Google+', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'linkedin':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-linkedin "><i class="fa fa-linkedin"></i> <span>' . esc_html__( 'Linked In', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'pinterest':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-pinterest "><i class="fa fa-pinterest"></i> <span>' . esc_html__( 'Pinterest', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'tumblr':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-tumblr "><i class="fa fa-tumblr"></i> <span>' . esc_html__( 'Tumblr', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'stumbleupon':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-stumbleupon "><i class="fa fa-stumbleupon"></i> <span>' . esc_html__( 'StumbleUpon', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'whatsapp':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" data-action="share/whatsapp/share" target="_blank" class="jeg_btn-whatsapp "><i class="fa fa-whatsapp"></i> <span>' . esc_html__( 'WhatsApp', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'email':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-email "><i class="fa fa-envelope"></i> <span>' . esc_html__( 'E-mail', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'vk':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-vk "><i class="fa fa-vk"></i> <span>' . esc_html__( 'VK', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'reddit':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-reddit "><i class="fa fa-reddit"></i> <span>' . esc_html__( 'Reddit', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'wechat':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-wechat "><i class="fa fa-wechat"></i> <span>' . esc_html__( 'WeChat', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'buffer':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-buffer "><i class="fa fa-buffer"></i> <span>' . esc_html__( 'WeChat', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'telegram':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-telegram "><i class="fa fa-telegram"></i> <span>' . esc_html__( 'Telegram', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'line':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-line "><i class="fa fa-line"></i> <span>' . esc_html__( 'Line', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'hatena':
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-hatena "><i class="fa fa-hatena"></i> <span>' . esc_html__( 'Hatena', 'jnews-podcast' ) . '</span></a>';
						break;

					case 'qrcode':
						$icon_class    = 'fa fa-qrcode';
						$share_url     = jnews_podcast_get_social_share_url( $social['social_share'], $id, $type );
						$share_output .= '<a href="' . $share_url . '" target="_blank" class="jeg_btn-qrcode "><i class="fa fa-qrcode"></i> <span>' . esc_html__( 'QR Code', 'jnews-podcast' ) . '</span></a>';
						break;
				}
			}
		}

		$share_output =
			'<div class="jeg_meta_share">
				<a href="javascript:;" ><i class="fa fa-share"></i> <span>' . jnews_return_translation( 'Share', 'jnews-podcast', 'share_podcast' ) . '</span></a>
				<div class="jeg_sharelist_podcast">
					' . $share_output . '
				</div>
			</div>';

		return $share_output;
	}
}

if ( ! function_exists( 'jnews_podcast_get_social_share_url' ) ) {
	/**
	 * @param $social
	 * @param $id
	 * @param $type
	 *
	 * @return string
	 */
	function jnews_podcast_get_social_share_url( $social, $id, $type = 'podcast' ) {
		if ( 'episode' === $type ) {
			$image = get_post_thumbnail_id( $id );
		} else {
			$attribute = jnews_podcast_attribute( $id, array( 'fields' => array( 'image' ) ) );
			$image     = $attribute['image'];
		}
		$image     = ! empty( $image ) && $image ? wp_get_attachment_image_src( $image, 'full' ) : false;
		$image_url = $image ? $image[0] : '';
		$title     = jnews_podcast_get_share_title( $id, $type );
		$url       = apply_filters( 'jnews_get_permalink', jnews_podcast_encode_url( $id, $type ) );

		switch ( $social ) {
			case 'facebook':
				$button_url = 'http://www.facebook.com/sharer.php?u=' . $url;
				break;
			case 'twitter':
				$button_url = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;
				break;
			case 'googleplus':
				$button_url = 'https://plus.google.com/share?url=' . $url;
				break;
			case 'pinterest':
				$button_url = 'https://www.pinterest.com/pin/create/bookmarklet/?pinFave=1&url=' . $url . '&media=' . $image_url . '&description=' . $title;
				break;
			case 'stumbleupon':
				$button_url = 'http://www.stumbleupon.com/submit?url=' . $url . '&title=' . $title;
				break;
			case 'linkedin':
				$button_url = 'https://www.linkedin.com/shareArticle?url=' . $url . '&title=' . $title;
				break;
			case 'reddit':
				$button_url = 'https://reddit.com/submit?url=' . $url . '&title=' . $title;
				break;
			case 'tumblr':
				$button_url = 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $url . '&title=' . $title;
				break;
			case 'buffer':
				$button_url = 'https://buffer.com/add?text=' . $title . '&url=' . $url;
				break;
			case 'vk':
				$button_url = 'http://vk.com/share.php?url=' . $url;
				break;
			case 'whatsapp':
				$button_url = '//api.whatsapp.com/send?text=' . $title . '%0A' . $url;
				break;
			case 'telegram':
				$button_url = 'https://telegram.me/share/url?url=' . $url . '&text=' . $title;
				break;
			case 'wechat':
				// wechat only able to share post using qrcode .
				// $button_url = 'weixin://dl/posts/link?url=' . $url; .
				$button_url = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&choe=UTF-8&chl=' . $url;
				break;
			case 'line':
				$button_url = 'https://social-plugins.line.me/lineit/share?url=' . $url . '&text=' . $title;
				break;
			case 'hatena':
				$button_url = 'http://b.hatena.ne.jp/bookmarklet?url=' . $url . '&btitle=' . $title;
				break;
			case 'qrcode':
				$button_url = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&choe=UTF-8&chl=' . $url;
				break;
			case 'email':
				$button_url = 'mailto:?subject=' . $title . '&amp;body=' . $url;
				break;
			default:
				$button_url = $url;
				break;
		}

		return $button_url;
	}
}

if ( ! function_exists( 'jnews_podcast_get_share_title' ) ) {
	/**
	 * @param $id
	 * @param string $type
	 *
	 * @return string
	 */
	function jnews_podcast_get_share_title( $id, $type = 'podcast' ) {
		if ( 'episode' === $type ) {
			$title = get_the_title( $id );
		} else {
			$series = get_term( $id );
			$title  = ! is_wp_error( $series ) ? $series->name : '';
		}
		$title = html_entity_decode( $title, ENT_QUOTES, 'UTF-8' );
		$title = rawurlencode( $title );
		$title = str_replace( '#', '%23', $title );

		return esc_html( $title );
	}
}

if ( ! function_exists( 'jnews_podcast_encode_url' ) ) {
	/**
	 * @param $id
	 * @param string $type
	 *
	 * @return string
	 */
	function jnews_podcast_encode_url( $id, $type = 'podcast' ) {
		if ( 'episode' === $type ) {
			$url = get_permalink( $id );
		} else {
			$url = get_term_link( $id );
			$url = ! is_wp_error( $url ) ? $url : '';
		}

		return rawurlencode( $url );
	}
}

if ( ! function_exists( 'jnews_podcast_feed_link' ) ) {
	/**
	 * @param bool $term_id
	 * @param bool $taxonomy
	 *
	 * @return false|string|void
	 */
	function jnews_podcast_feed_link( $term_id = false, $taxonomy = false ) {

		$custom_feed_link = get_theme_mod( 'custom_feed_url' );
		if ( $custom_feed_link ) {
			return esc_attr( $custom_feed_link );
		}

		if ( false === $term_id || false === $taxonomy ) {
			if ( is_archive() ) {
				global $wp_query;
				$taxonomy = $wp_query->get_queried_object();

				return get_term_feed_link( $taxonomy->term_id, $taxonomy->taxonomy );
			}

			return '';
		}

		return get_term_feed_link( $term_id, $taxonomy );
	}
}

if ( ! function_exists( 'jnews_podcast_posts' ) ) {
	/**
	 * @param $podcast_id
	 * @param array      $args
	 *
	 * @return array|false|int[]|mixed|WP_Post[]
	 */
	function jnews_podcast_posts( $podcast_id, $args = array() ) {
		$slug       = JNEWS_PODCAST\Series\Series::get_slug();
		$defaults   = array(
			'orderby'   => 'rand',
			'tax_query' => array(
				array(
					'taxonomy'         => $slug,
					'field'            => 'term_id',
					'terms'            => $podcast_id, // Where term_id of selected $podcast_id.
					'include_children' => false,
				),
			),
		);
		$_args      = wp_parse_args( $args, $defaults );
		$query_hash = 'query_hash_' . md5( serialize( $_args ) );
		if ( ! $episodes = jnews_podcast_cache( $query_hash ) ) {
			$episodes = jnews_podcast_cache(
				$query_hash,
				call_user_func(
					static function () use ( $_args ) {
						return get_posts( $_args );
					}
				)
			);
		}
		if ( ! is_wp_error( $episodes ) ) {
			return $episodes;
		}

		return array();
	}
}

if ( ! function_exists( 'jnews_podcast_get_term_translate_id' ) ) {
	/**
	 * @param int $term_id Term ID .
	 *
	 * @return mixed
	 */
	function jnews_podacst_get_term_translate_id( $term_id ) {
		if ( function_exists( 'pll_get_term' ) ) {
			$result_id = pll_get_term( $term_id, pll_current_language() );

			if ( $result_id ) {
				$term_id = $result_id;
			}
		}

		return $term_id;
	}
}

if ( ! function_exists( 'jnews_podcast_attribute' ) ) {
	/**
	 * Get most user podcast series
	 *
	 * @param array|int $podcast_id
	 * @param array     $args
	 *
	 * @return array
	 */
	function jnews_podcast_attribute( $podcast_id, $args = array() ) {
		$result   = array();
		$defaults = array(
			'fields' => array( 'author' ),
		);
		$_args    = wp_parse_args( $args, $defaults );
		foreach ( $_args['fields'] as $field ) {
			switch ( $field ) {
				case 'author':
					$episodes = jnews_podcast_posts( $podcast_id );
					$is_empty = empty( $episodes ) ? true : false;
					$user     = false;
					if ( ! $is_empty ) {
						$authors = array();
						foreach ( $episodes as $episode_obj ) {
							$authors[] = $episode_obj->post_author;
						}
						if ( ! empty( $authors ) ) {
							$values = array_count_values( $authors );
							arsort( $values );
							$most_users = array_slice( array_keys( $values ), 0, 1, true );
							foreach ( $most_users as $index => $users ) {
								$user = $users;
							}
						}
					}
					$result[ $field ] = $user;
					break;
				case 'category':
					$query_hash = 'query_hash_' . md5( serialize( 'category_podcast_' . $podcast_id ) );
					if ( ! $category = jnews_podcast_cache( $query_hash ) ) {
						$episodes = jnews_podcast_posts( $podcast_id );
						$is_empty = empty( $episodes ) ? true : false;
						$category = false;
						if ( ! $is_empty ) {
							$categories      = array();
							$temp_categories = array();
							foreach ( $episodes as $episode_obj ) {
								$cat = get_the_category( $episode_obj->ID );
								if ( ! empty( $cat ) ) {
									$categories[]                        = $cat[0]->term_id;
									$temp_categories[ $cat[0]->term_id ] = $cat[0];
								}
							}
							if ( ! empty( $categories ) ) {
								$values = array_count_values( $categories );
								arsort( $values );
								$most_cat = array_slice( array_keys( $values ), 0, 1, true );
								foreach ( $most_cat as $index => $categories ) {
									$category = $temp_categories[ $categories ];
								}
							}
						}
						$category = jnews_podcast_cache( $query_hash, $category );
					}
					$result[ $field ] = $category;
					break;
				case 'image':
					$result[ $field ] = \JNEWS_PODCAST\Series\Object_Series::get_series_image_id( $podcast_id );
					break;
				case 'count_series':
					if ( is_array( $podcast_id ) ) {
						$result[ $field ] = empty( $podcast_id ) ? 0 : count( $podcast_id );
					} elseif ( is_int( $podcast_id ) ) {
						$episode_count    = jnews_get_series( array( 'term_taxonomy_id' => $podcast_id ) );
						$episode_count    = is_array( $episode_count ) && ! empty( $episode_count ) ? $episode_count[0]->count : 0;
						$result[ $field ] = $episode_count;
					}

					break;
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'jnews_podcast_get_duration' ) ) {
	/**
	 * Get podcast Length
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	function jnews_podcast_get_duration( $post_id, $human_readable = false, $icon = false, $wrapper = 'div' ) {
		$output   = '';
		$duration = vp_metabox( 'jnews_podcast_option.podcast_duration', null, $post_id );
		if ( $duration ) {
			$output .= "<$wrapper class='jeg_episode_length'>";
			if ( $human_readable ) {
				$duration_divided = explode( ':', $duration );
				$count_duration   = count( $duration_divided );
				$h                = '00';
				$m                = '00';
				$s                = '00';
				if ( $count_duration <= 3 && $count_duration > 0 ) {
					switch ( $count_duration ) {
						case 3:
							list( $h, $m, $s ) = $duration_divided;
							break;
						case 2:
							$h             = '00';
							list( $m, $s ) = $duration_divided;
							break;
						case 1:
							$h         = '00';
							$m         = '00';
							list( $s ) = $duration_divided;
							break;
					}
				} else {
					if ( $count_duration > 0 ) {
						$s = end( $duration_divided );
						$m = prev( $duration_divided );
						$h = prev( $duration_divided );
					}
				}
				$h      = is_int( (int) $h ) ? ltrim( $h, '0' ) : '0';
				$m      = is_int( (int) $m ) ? ltrim( $m, '0' ) : '0';
				$s      = is_int( (int) $s ) ? ltrim( $s, '0' ) : '0';
				$time_h = ( $h && $h !== '00' && ! empty( $h ) ? "$h " . esc_attr__( 'hr', 'jnews-podcast' ) . ' ' : '' );
				$time_m = ( $m && $m !== '00' && ! empty( $m ) ? "$m " : '0 ' ) . esc_attr__( 'min', 'jnews-podcast' );
				$time_s = '';
				if ( (int) $h < 1 && (int) $m < 2 ) {
					if ( (int) $m > 0 ) {
						$time_m .= ' ';
					} else {
						$time_m = '';
					}
					$time_s = ( $s && $s !== '00' && ! empty( $s ) ? "$s " : '0 ' ) . esc_attr__( 'sec', 'jnews-podcast' );
				}
				$time    = $time_h . $time_m . $time_s;
				$icon    = $icon ? "<i class='fa fa-clock-o' aria-hidden='true'></i> " : '';
				$output .= $icon . $time;
			} else {
				$output .= normalize_duration( $duration );
			}
			$output .= "</$wrapper>";
		}

		return $output;
	}
}
if ( ! function_exists( 'normalize_duration' ) ) {
	/**
	 * @param $duration
	 *
	 * @return false|string
	 */
	function normalize_duration( $duration ) {
		$string = '00:00:00';

		for ( $i = ( strlen( $string ) - 4 ); $i > 0; $i -- ) {
			$comparator = substr( $string, 0, $i );
			if ( 0 === strpos( $duration, $comparator ) ) {
				break;
			}
		}

		return substr( $duration, $i );
	}
}

if ( ! function_exists( 'jnews_podcast_option' ) ) {
	/**
	 * JNews get podcast option
	 *
	 * @param $setting
	 * @param array   $default
	 *
	 * @return array
	 */
	function jnews_podcast_option( $setting, $default = array() ) {
		$options = get_option( 'jnews_option', array() );
		$value   = $default;
		if ( isset( $options['jnews_podcast'] ) && isset( $options['jnews_podcast'][ $setting ] ) ) {
			$value = $options['jnews_podcast'][ $setting ];
		}

		return apply_filters( "jnews_option_podcast_{$setting}", $value );
	}
}

if ( ! function_exists( 'jnews_get_powerpress_option' ) ) {
	/**
	 * JNews Get Powerpress Option
	 *
	 * @param $setting
	 * @param null    $default
	 *
	 * @return array
	 */
	function jnews_get_powerpress_option( $setting, $default = null ) {
		$value = $default;
		if ( defined( 'POWERPRESS_VERSION' ) ) {
			$value = jnews_podcast_option( $setting, $default );
		}

		return $value;
	}
}

if ( ! function_exists( 'jnews_view_counter_podcast_query' ) ) {
	/**
	 * @param $instance
	 *
	 * @param string   $type
	 *
	 * @return array
	 */
	function jnews_view_counter_podcast_query( $instance, $type = 'custom' ) {
		if ( 'custom' === $type ) {
			return \JNEWS_PODCAST\Series\Series_View_Counter::get_instance()->query( $instance );
		}

		return \JNEWS_PODCAST\Series\Series_View_Counter::get_instance()->default_query( $instance );
	}
}

if ( ! function_exists( 'jnews_default_query_jnews_podcast' ) ) {
	/**
	 * Add more podcast default query
	 *
	 * @param $args
	 * @param $attr
	 *
	 * @return mixed
	 */
	function jnews_default_query_jnews_podcast( $args, $attr ) {
		if ( isset( $attr['include_podcast_episode'] ) && ! empty( $attr['include_podcast_episode'] ) ) {
			$slug              = JNEWS_PODCAST\Series\Series::get_slug();
			$args['tax_query'] = array(
				array(
					'taxonomy' => $slug,
					'field'    => 'term_id',
					'terms'    => $attr['include_podcast_episode'],
				),
			);
		}

		if ( isset( $attr['include_episode'] ) && ! empty( $attr['include_episode'] ) ) {
			$args['post__in'] = explode( ',', $attr['include_episode'] );
		}
		if ( isset( $attr['exclude_episode'] ) && ! empty( $attr['exclude_episode'] ) ) {
			$args['post__not_in'] = explode( ',', $attr['exclude_episode'] );
		}

		return $args;
	}

	add_filter( 'jnews_default_query_args', 'jnews_default_query_jnews_podcast', 10, 2 );
}

if ( ! function_exists( 'jnews_podcast_get_template_part' ) ) {
	/**
	 * @param $slug
	 * @param null $name
	 */
	function jnews_podcast_get_template_part( $slug, $name = null ) {
		if ( function_exists( 'jnews_get_template_part' ) ) {
			jnews_get_template_part( $slug, $name, JNEWS_PODCAST_DIR );
		}
	}
}

if ( ! function_exists( 'jnews_podcast_get_template_path' ) ) {
	/**
	 * @param $template_names
	 * @param bool           $load
	 * @param bool           $require_once
	 */
	function jnews_podcast_get_template_path( $template_names, $load = false, $require_once = true ) {
		if ( function_exists( 'jnews_get_template_path' ) ) {
			jnews_get_template_path( $template_names, $load, $require_once, JNEWS_PODCAST_DIR );
		}
	}
}

if ( ! function_exists( 'jnews_unset_unnecessary_jnews_podcast' ) ) {
	/**
	 * @param $accepted
	 * @param $args
	 *
	 * @return array
	 */
	function jnews_unset_unnecessary_jnews_podcast( $accepted, $args ) {
		if ( isset( $args['include_podcast_episode'] ) ) {
			$accepted = array_merge( $accepted, array( 'include_podcast_episode' ) );
		}
		if ( isset( $args['include_episode'] ) ) {
			$accepted = array_merge( $accepted, array( 'include_episode' ) );
		}
		if ( isset( $args['exclude_episode'] ) ) {
			$accepted = array_merge( $accepted, array( 'exclude_episode' ) );
		}

		return $accepted;
	}

	add_filter( 'jnews_unset_unnecessary_attr', 'jnews_unset_unnecessary_jnews_podcast', 10, 2 );
}

if ( ! function_exists( 'jnews_podcast_cache' ) ) {
	/**
	 * Podcast Cache
	 *
	 * @param $query_hash
	 * @param bool       $value
	 *
	 * @return bool|false|mixed
	 */
	function jnews_podcast_cache( $query_hash, $value = false ) {
		if ( ! $value ) {
			return wp_cache_get( $query_hash, 'jnews-podcast' );
		}
		wp_cache_set( $query_hash, $value, 'jnews-podcast' );

		return $value;
	}
}

if ( ! function_exists( 'jnews_get_series' ) ) {
	/**
	 * Retrieves all podcast jnews.
	 *
	 * @param string|array $args Tag arguments to use when retrieving tags.
	 *
	 * @return WP_Term[]|int $tags Array of 'jnews-series' term objects, or a count thereof.
	 * @since 7.5.0
	 * @see get_terms() For list of arguments to pass.
	 */
	function jnews_get_series( $args = '' ) {
		$slug       = JNEWS_PODCAST\Series\Series::get_slug();
		$defaults   = array( 'taxonomy' => $slug );
		$args       = wp_parse_args( $args, $defaults );
		$query_hash = 'query_hash_' . md5( serialize( $args ) );
		if ( ! $podcast = jnews_podcast_cache( $query_hash ) ) {
			$podcast = jnews_podcast_cache(
				$query_hash,
				call_user_func(
					static function () use ( $args ) {
						return get_terms( $args );
					}
				)
			);
		}

		if ( empty( $podcast ) ) {
			return array();
		}

		/**
		 * Filters the array of term objects returned for the 'post_tag' taxonomy.
		 *
		 * @param WP_Term[]|int $tags Array of 'post_tag' term objects, or a count thereof.
		 * @param array $args An array of arguments. @see get_terms()
		 *
		 * @since 7.5.0
		 */
		$podcast = apply_filters( 'jnews_get_series', $podcast, $args );

		return $podcast;
	}
}

if ( ! function_exists( 'jnews_podcast_get_category_link' ) ) {
	/**
	 * @param $term
	 *
	 * @return string|WP_Error
	 */
	function jnews_podcast_get_category_link( $term ) {
		add_filter( 'term_link', 'jnews_podcast_pre_category_link', 10, 3 );
		$category_link = get_term_link( $term, 'category' );
		remove_filter( 'term_link', 'jnews_podcast_pre_category_link', 10 );

		return $category_link;
	}
}

if ( ! function_exists( 'jnews_podcast_pre_category_link' ) ) {
	/**
	 * @param $termlink
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return string|string[]
	 */
	function jnews_podcast_pre_category_link( $termlink, $term, $taxonomy ) {
		if ( 'category' === $taxonomy ) {
			global $wp_rewrite;
			$category_base   = $wp_rewrite->get_extra_permastruct( $taxonomy );
			$is_blog_archive = $category_base ? false !== strpos( 'blog', $category_base ) : false;
			if ( $category_base ) {
				$find = array( '/%category%', '/' );
				if ( is_multisite() && $is_blog_archive ) {
					$find[] = 'blog';
				}
				$category_base = str_replace( $find, '', $category_base );
			} else {
				$category_base = 'category';
			}
			$find    = array( '?cat=', '/' . $category_base . '/' );
			$replace = array(
				'?series-category=',
				'/series-category/',
			);
			if ( is_multisite() && $is_blog_archive ) {
				$find    = array( '?cat=', '/blog/' . $category_base . '/' );
				$replace = array(
					'?series-category=',
					'/blog/series-category/',
				);
			}
			$termlink = str_replace(
				$find,
				$replace,
				$termlink
			);
		}

		return $termlink;
	}
}

if ( ! function_exists( 'jnews_podcast_module_custom_color' ) ) {
	/**
	 * @param $attr
	 * @param $unique_class
	 * @param string       $name
	 *
	 * @return string
	 */
	function jnews_podcast_module_custom_color( $attr, $unique_class, $name = '' ) {
		$style        = jnews_module_custom_color( $attr, $unique_class, $name );
		$unique_class = trim( $unique_class );

		$play_button_icon       = isset( $attr['play_button_icon'] ) && ! empty( $attr['play_button_icon'] ) ? 'color: ' . $attr['play_button_icon'] . ';' : '';
		$play_button_background = isset( $attr['play_button_background'] ) && ! empty( $attr['play_button_background'] ) ? 'background-color: ' . $attr['play_button_background'] . ';' : '';
		$play_button_border     = isset( $attr['play_button_border'] ) && ! empty( $attr['play_button_border'] ) ? 'border-color: ' . $attr['play_button_border'] . ';' : '';
		$more_button_icon       = isset( $attr['more_button_icon'] ) && ! empty( $attr['more_button_icon'] ) ? 'color: ' . $attr['more_button_icon'] . ';' : '';
		$more_button_background = isset( $attr['more_button_background'] ) && ! empty( $attr['more_button_background'] ) ? 'background-color: ' . $attr['more_button_background'] . ';' : '';
		$more_button_border     = isset( $attr['more_button_border'] ) && ! empty( $attr['more_button_border'] ) ? 'border-color: ' . $attr['more_button_border'] . ';' : '';

		if ( ! empty( $play_button_background ) || ! empty( $play_button_border ) ) {
			$style .= ".{$unique_class} .jeg_media_button.play, .{$unique_class} .jeg_media_button.subscribe { {$play_button_border} {$play_button_background} }";
		}
		if ( isset( $attr['play_button_text'] ) && ! empty( $attr['play_button_text'] ) ) {
			$style .= ".{$unique_class} .jeg_media_button.play span, .{$unique_class} .jeg_media_button.subscribe span { color: {$attr['play_button_text']}; }";
		}
		if ( isset( $attr['play_button_icon'] ) && ! empty( $attr['play_button_icon'] ) ) {
			$style .= ".{$unique_class} .jeg_media_button.play .fa, .{$unique_class} .jeg_media_button.subscribe .fa { {$play_button_icon} }";
		}

		if ( ! empty( $play_button_background ) || ! empty( $play_button_border ) ) {
			$style .= ".{$unique_class} .jeg_media_button.more { {$more_button_border} {$more_button_background} }";
		}
		if ( isset( $attr['play_button_icon'] ) && ! empty( $attr['play_button_icon'] ) ) {
			$style .= ".{$unique_class} .jeg_media_button.more .fa { {$more_button_icon} }";
		}

		return $style;
	}
}

if ( ! function_exists( 'jnews_get_podcast_selectize' ) ) {
	/**
	 * @return array
	 */
	function jnews_get_podcast_selectize() {
		$results = jnews_get_podcast_option();
		if ( ! empty( $results ) ) {
			foreach ( $results as $index => $result ) {
				if ( 'none' === $result['value'] ) {
					unset( $results[ $index ] );
				} else {
					$results[ $index ]['label'] = $result['text'];
				}
			}
		}

		return $results;
	}
}

if ( ! function_exists( 'jnews_get_podcast_option' ) ) {
	/**
	 * @param null $value
	 *
	 * @return array
	 */
	function jnews_get_podcast_option( $value = null ) {
		$slug   = JNEWS_PODCAST\Series\Series::get_slug();
		$result = array();
		$count  = wp_count_terms( $slug );

		$result[0] = array(
			'value' => 'none',
			'text'  => esc_attr__( 'None', 'jnews-podcast' ),
		);

		if ( (int) $count <= jnews_load_resource_limit() ) {
			$terms = jnews_get_series( array( 'hide_empty' => false ) );
			foreach ( $terms as $term ) {
				$result[] = array(
					'value' => (string) $term->term_id,
					'text'  => $term->name,
				);
			}
		} else {
			$selected = $value;

			if ( ! empty( $selected ) ) {
				$terms = jnews_get_series(
					array(
						'hide_empty'   => false,
						'hierarchical' => false,
						'include'      => $selected,
					)
				);

				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => (string) $term->term_id,
						'text'  => $term->name,
					);
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'jnews_podcast_get_episode_option' ) ) {
	function jnews_podcast_get_episode_option( $value = null ) {
		return jeg_get_post_option( $value );
	}
}

if ( ! function_exists( 'jnews_podcast_get_podcast_option' ) ) {
	function jnews_podcast_get_podcast_option( $value = null ) {
		$result = array();
		$count  = jnews_get_series( array( 'field' => 'count' ) );

		if ( (int) $count <= jnews_load_resource_limit() ) {
			$terms = jnews_get_series( array( 'hide_empty' => 0 ) );
			foreach ( $terms as $term ) {
				$result[] = array(
					'value' => $term->term_id,
					'text'  => $term->name,
				);
			}
		} else {
			$selected = $value;

			if ( ! empty( $selected ) ) {
				$terms = jnews_get_series(
					array(
						'hide_empty'   => false,
						'hierarchical' => true,
						'include'      => $selected,
					)
				);

				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name,
					);
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'jnews_get_podcast_option' ) ) {
	add_action( 'wp_ajax_jnews_get_podcast_option', 'jnews_get_ajax_podcast_option' );
	/**
	 * Get ajax option
	 */
	function jnews_get_ajax_podcast_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jnews_find_podcast' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jnews_get_podcast_option( $value ) );
		}
	}
}

if ( ! function_exists( 'jnews_podcast_find_episode' ) ) {
	function jnews_podcast_find_episode() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jnews_podcast_find_episode' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			add_filter(
				'posts_where',
				function ( $where ) use ( $query ) {
					global $wpdb;

					if ( isset( $_REQUEST['query'] ) && ! empty( $_REQUEST['query'] ) ) {
						$string = $_REQUEST['query'];

						$where .= $wpdb->prepare( "AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like( $string ) . '%' );
					}

					return $where;
				}
			);

			$query = new \WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => '15',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'meta_query'     => array(
						array(
							'key'     => 'jnews_podcast_option',
							'value'   => str_replace( 'a:1:{', '', str_replace( '}', '', serialize( array( 'enable_podcast' => '1' ) ) ) ),
							'compare' => 'LIKE',
						),
					),
				)
			);

			$result = array();

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$result[] = array(
						'value' => get_the_ID(),
						'text'  => get_the_title(),
					);
				}
			}

			wp_reset_postdata();
			wp_send_json_success( $result );
		}
	}
}
if ( ! function_exists( 'jnews_podcast_find_podcast' ) ) {
	function jnews_podcast_find_podcast() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['query'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jeg_find_category' ) ) {
			$query = sanitize_text_field( wp_unslash( $_REQUEST['query'] ) );

			$args = array(
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => 0,
				'fields'     => 'all',
				'name__like' => urldecode( $query ),
				'number'     => 50,
			);

			$terms = jnews_get_series( $args );

			$result = array();

			if ( count( $terms ) > 0 ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'value' => $term->term_id,
						'text'  => $term->name,
					);
				}
			}

			wp_send_json_success( $result );
		}
	}
}
add_action( 'wp_ajax_jnews_podcast_find_episode', 'jnews_podcast_find_episode' );
add_action( 'wp_ajax_jnews_podcast_find_podcast', 'jnews_podcast_find_podcast' );

if ( ! function_exists( 'jnews_podcast_get_ajax_episode_option' ) ) {
	function jnews_podcast_get_ajax_episode_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jnews_podcast_find_episode' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jnews_podcast_get_episode_option( $value ) );
		}
	}
}
if ( ! function_exists( 'jnews_podcast_get_ajax_podcast_option' ) ) {
	function jnews_podcast_get_ajax_podcast_option() {
		if ( isset( $_REQUEST['nonce'], $_REQUEST['value'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'jnews_podcast_find_podcast' ) ) {
			$value = sanitize_text_field( wp_unslash( $_REQUEST['value'] ) );
			wp_send_json_success( jnews_podcast_get_podcast_option( $value ) );
		}
	}
}
add_action( 'wp_ajax_jnews_podcast_get_episode_option', 'jnews_podcast_get_ajax_episode_option' );
add_action( 'wp_ajax_jnews_podcast_get_podcast_option', 'jnews_podcast_get_ajax_podcast_option' );

if ( ! function_exists( 'jnews_is_podcast_extension_active' ) ) {
	/**
	 * Check if extension active
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	function jnews_is_podcast_extension_active( $name ) {
		$flag = false;
		switch ( $name ) {
			case 'powerpress':
				if ( defined( 'POWERPRESS_VERSION' ) ) {
					$flag = true;
				}
				break;
		}

		return $flag;
	}
}

if ( ! function_exists( 'podcast_log' ) ) {
	/**
	 * @param null $object
	 *
	 * Logging Variable/Object in php_error_log file
	 * Note : Use this for variable/object that cannot be printed to a html page
	 */
	function podcast_log( $object = null ) {
		if ( function_exists( 'jnews_log' ) ) {
			jnews_log( $object );
		}
	}
}
