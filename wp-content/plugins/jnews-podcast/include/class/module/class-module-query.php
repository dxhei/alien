<?php

namespace JNEWS_PODCAST\Module;

/**
 * Class Module_Query
 *
 * @package JNEWS_PODCAST\Module
 */
class Module_Query {
	/**
	 * @var array
	 */
	private static $cache_thumbnail = array();

	/**
	 * @param $attr
	 *
	 * @return array|bool|mixed
	 */
	public static function podcast_query( $attr ) {
		$attr       = self::unset_unnecessary( $attr );
		$query_hash = 'query_hash_' . md5( serialize( $attr ) );

		if ( ! $result = wp_cache_get( $query_hash, 'jnews-podcast' ) ) {
			$order = self::check_custom_order( 'sort_by', $attr );
			if ( 'custom' === $order ) {
				$result = self::custom_query( 'sort_by', $attr, 'default' );
			} elseif ( 'jetpack' === $order ) {
				$result = self::jetpack_query( 'sort_by', $attr );
			} else {
				$result = self::default_query( $attr );
			}

			wp_cache_set( $query_hash, $result, 'jnews-podcast' );

			// need to optimize query
			self::optimize_query( $result );
		}

		return $result;
	}

	/**
	 * @param $attr
	 *
	 * @return mixed
	 */
	private static function unset_unnecessary( $attr ) {
		$accepted = array(
			'post_type',
			'number_post',
			'post_offset',
			'include_post',
			'exclude_post',
			'include_category',
			'exclude_category',
			'include_author',
			'include_tag',
			'exclude_tag',
			'sort_by',
			'paged',
			'video_only',
			'content_type',
			'pagination_number_post',
			'pagination_mode',
			'date_query',
		);

		$accepted = apply_filters( 'jnews_unset_unnecessary_attr', $accepted, $attr );

		foreach ( $attr as $key => $value ) {
			if ( ! in_array( $key, $accepted, true ) ) {
				unset( $attr[ $key ] );
			}
		}

		if ( isset( $attr['pagination_number_post'] ) ) {
			$attr['pagination_number_post'] = (int) $attr['pagination_number_post'];
		}

		if ( isset( $attr['paged'] ) ) {
			$attr['paged'] = (int) $attr['paged'];
		}

		if ( isset( $attr['number_post']['size'] ) ) {
			$attr['number_post'] = $attr['number_post']['size'];
		}

		return $attr;
	}

	/**
	 * Check custom order
	 *
	 * @param $field
	 * @param $attr
	 *
	 * @return bool
	 */
	private static function check_custom_order( $field, $attr ) {
		$value = false;
		if ( isset( $attr[ $field ] ) ) {
			$custom_order  = array(
				'most_comment_day',
				'most_comment_week',
				'most_comment_month',
				'popular_post_day',
				'popular_post',
				'popular_post_week',
				'popular_post_month',
			);
			$jetpack_order = array(
				'popular_post_jetpack_day',
				'popular_post_jetpack_week',
				'popular_post_jetpack_month',
				'popular_post_jetpack_all',
			);
			if ( in_array( $attr[ $field ], $custom_order, true ) ) {
				$value = 'custom';
			} elseif ( in_array( $attr[ $field ], $jetpack_order, true ) ) {
				$value = 'jetpack';
			}
		}

		return $value;
	}

	/**
	 * @param $field
	 * @param $attr
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	private static function custom_query( $field, $attr, $type = 'custom' ) {
		if ( function_exists( 'jnews_view_counter_query' ) ) {
			$args             = array();
			$include_category = $args;
			$exclude_category = $include_category;

			// Argument
			$args['post_type']     = 'post';
			$args['paged']         = isset( $attr['paged'] ) ? $attr['paged'] : 1;
			$args['offset']        = self::calculate_offset( $args['paged'], $attr['post_offset'], $attr['number_post'], $attr['pagination_number_post'] );
			$args['limit']         = ( $args['paged'] > 1 ) ? $attr['pagination_number_post'] : $attr['number_post'];
			$args['no_found_rows'] = ! isset( $attr['pagination_mode'] ) || 'disable' === $attr['pagination_mode'];

			if ( ! empty( $attr['include_post'] ) ) {
				$args['include_post'] = $attr['include_post'];
			}

			if ( ! empty( $attr['exclude_post'] ) ) {
				$args['exclude_post'] = $attr['exclude_post'];
			}

			if ( ! empty( $attr['include_category'] ) ) {
				$categories = explode( ',', $attr['include_category'] );
				self::recursive_category( $categories, $include_category );
				$args['include_category'] = implode( ',', $include_category );
			}

			if ( ! empty( $attr['exclude_category'] ) ) {
				$categories = explode( ',', $attr['exclude_category'] );
				self::recursive_category( $categories, $exclude_category );
				$args['exclude_category'] = implode( ',', $exclude_category );
			}

			if ( ! empty( $attr['include_author'] ) ) {
				$args['author'] = $attr['include_author'];
			}

			if ( ! empty( $attr['include_tag'] ) ) {
				$args['include_tag'] = $attr['include_tag'];
			}

			if ( ! empty( $attr['exclude_tag'] ) ) {
				$args['exclude_tag'] = $attr['exclude_tag'];
			}

			if ( isset( $attr['include_podcast_episode'] ) && ! in_array(
				$attr['include_podcast_episode'],
				array(
					'',
					'none',
				),
				true
			) ) {
				$args['include_podcast_episode'] = $attr['include_podcast_episode'];
			}

			if ( 'most_comment_day' === $attr[ $field ] || 'most_comment_week' === $attr[ $field ] || 'most_comment_month' === $attr[ $field ] ) {
				$args['order_by'] = 'comments';
			} else {
				$args['order_by'] = 'views';
			}

			if ( 'most_comment_day' === $attr[ $field ] || 'popular_post_day' === $attr[ $field ] ) {
				$args['range'] = 'daily';
			}

			if ( 'most_comment_week' === $attr[ $field ] || 'popular_post_week' === $attr[ $field ] ) {
				$args['range'] = 'weekly';
			}

			if ( 'most_comment_month' === $attr[ $field ] || 'popular_post_month' === $attr[ $field ] ) {
				$args['range'] = 'monthly';
			}

			if ( 'popular_post' === $attr[ $field ] ) {
				$args[ $field ] = 'all';
			}

			return self::custom_jnews_query( $args, $attr, $type );
		}

		return array(
			'result'     => array(),
			'next'       => false,
			'prev'       => false,
			'total_page' => 1,
		);
	}

	/**
	 * Calculate Offset
	 *
	 * @param $paged
	 * @param $offset
	 * @param $number_post
	 * @param $number_post_ajax
	 *
	 * @return int
	 */
	private static function calculate_offset( $paged, $offset, $number_post, $number_post_ajax ) {
		$new_offset = 0;

		if ( isset( $offset['size'] ) ) {
			$offset = $offset['size'];
		}

		if ( 1 == $paged ) {
			$new_offset = (int) $offset;
		}
		if ( 2 == $paged ) {
			$new_offset = $number_post + (int) $offset;
		}
		if ( $paged >= 3 ) {
			$new_offset = $number_post + (int) $offset + ( $number_post_ajax * ( $paged - 2 ) );
		}

		return $new_offset;
	}

	/**
	 * @param $categories
	 * @param $result
	 */
	private static function recursive_category( $categories, &$result ) {
		foreach ( $categories as $category ) {
			if ( ! in_array( $category, $result, true ) ) {
				$result[] = $category;
				$children = get_categories( array( 'parent' => $category ) );

				if ( ! empty( $children ) ) {
					$child_id = array();
					foreach ( $children as $child ) {
						$child_id[] = $child->term_id;
					}
					self::recursive_category( $child_id, $result );
				}
			}
		}
	}

	/**
	 * Custom Query for JNews. Add ability to receive Paging Parameter and Tag Parameter
	 *
	 * @param $instance
	 * @param $attr
	 *
	 * @param string   $type
	 *
	 * @return array
	 */
	private static function custom_jnews_query( $instance, $attr, $type = 'custom' ) {
		if ( function_exists( 'jnews_view_counter_podcast_query' ) ) {
			$query_result = jnews_view_counter_podcast_query( $instance, $type );

			return array(
				'result'     => $query_result['result'],
				'next'       => self::has_next_page( $query_result['total'], $instance['paged'], $instance['offset'], $attr['number_post'], $attr['pagination_number_post'] ),
				'prev'       => self::has_prev_page( $instance['paged'] ),
				'total_page' => self::count_total_page( $query_result['total'], $instance['paged'], $instance['offset'], $attr['number_post'], $attr['pagination_number_post'] ),
			);

		}

		return array(
			'result'     => array(),
			'next'       => false,
			'prev'       => false,
			'total_page' => 0,
		);
	}

	/**
	 * Check if we have next page
	 *
	 * @param $total
	 * @param int          $curpage
	 * @param int          $offset
	 * @param $perpage
	 * @param $perpage_ajax
	 *
	 * @return bool
	 */
	private static function has_next_page( $total, $curpage = 1, $offset = 0, $perpage, $perpage_ajax ) {
		$flag = false;
		if ( 1 == $curpage ) {
			$flag = (int) $total > (int) $offset + (int) $perpage;
		} elseif ( $curpage > 1 ) {
			$flag = (int) $total > (int) $offset + (int) $perpage_ajax;
		}

		return $flag;
	}

	/**
	 * Check if we have previous page
	 *
	 * @param int $curpage
	 *
	 * @return bool
	 */
	private static function has_prev_page( $curpage = 1 ) {
		return ! ( $curpage <= 1 );
	}

	/**
	 * Get total count of total page
	 *
	 * @param $total
	 * @param int          $curpage
	 * @param int          $offset
	 * @param $perpage
	 * @param $perpage_ajax
	 *
	 * @return int
	 */
	private static function count_total_page( $total, $curpage = 1, $offset = 0, $perpage, $perpage_ajax ) {
		$remain = (int) $total - ( (int) $offset + (int) $perpage );

		if ( $remain > 0 ) {
			while ( $remain > 0 ) {
				$remain -= (int) $perpage_ajax;
				++ $curpage;
			}
		}

		return $curpage;
	}

	/**
	 * Jetpack Query
	 *
	 * @param $field
	 * @param $attr
	 *
	 * @return array
	 */
	private static function jetpack_query( $field, $attr ) {
		$result = array();

		if ( function_exists( 'stats_get_csv' ) ) {
			switch ( $attr[ $field ] ) {
				case 'popular_post_jetpack_week':
					$days = 7;
					break;
				case 'popular_post_jetpack_month':
					$days = 30;
					break;
				case 'popular_post_jetpack_day':
					$days = 2;
					break;
				case 'popular_post_jetpack_all':
				default:
					$days = - 1;
					break;
			}

			$top_posts = stats_get_csv(
				'postviews',
				array(
					'days'  => $days,
					'limit' => $attr['number_post'] + 5,
				)
			);

			if ( ! $top_posts ) {
				return array();
			}

			$counter = 0;
			foreach ( $top_posts as $post ) {
				$the_post = get_post( $post['post_id'] );

				if ( ! $the_post ) {
					continue;
				}
				if ( 'post' !== $the_post->post_type ) {
					continue;
				}

				$counter ++;
				$result[] = get_post( $post['post_id'] );

				if ( $counter == $attr['number_post'] ) {
					break;
				}
			}
		}

		return array(
			'result'     => $result,
			'next'       => false,
			'prev'       => false,
			'total_page' => 1,
		);

	}

	/**
	 * WordPress Default Query
	 *
	 * @param $attr
	 *
	 * @return array
	 */
	private static function default_query( $attr ) {
		$args             = array();
		$result           = $args;
		$exclude_category = $result;
		$include_category = $exclude_category;

		$attr['number_post']            = isset( $attr['number_post'] ) ? $attr['number_post'] : get_option( 'posts_per_page' );
		$attr['pagination_number_post'] = isset( $attr['pagination_number_post'] ) ? $attr['pagination_number_post'] : $attr['number_post'];

		// Argument
		$args['post_type']           = isset( $attr['post_type'] ) ? $attr['post_type'] : 'post';
		$args['paged']               = isset( $attr['paged'] ) ? $attr['paged'] : 1;
		$args['offset']              = self::calculate_offset( $args['paged'], $attr['post_offset'], $attr['number_post'], $attr['pagination_number_post'] );
		$args['posts_per_page']      = ( $args['paged'] > 1 ) ? $attr['pagination_number_post'] : $attr['number_post'];
		$args['no_found_rows']       = ! isset( $attr['pagination_mode'] ) || 'disable' === $attr['pagination_mode'];
		$args['ignore_sticky_posts'] = 1;

		if ( ! empty( $attr['include_post'] ) ) {
			$args['post__in'] = explode( ',', $attr['include_post'] );
		}

		if ( ! empty( $attr['exclude_post'] ) ) {
			$args['post__not_in'] = explode( ',', $attr['exclude_post'] );
		}

		if ( ! empty( $attr['include_category'] ) ) {
			$categories = explode( ',', $attr['include_category'] );
			self::recursive_category( $categories, $include_category );
			$args['category__in'] = $include_category;
		}

		if ( ! empty( $attr['exclude_category'] ) ) {
			$categories = explode( ',', $attr['exclude_category'] );
			self::recursive_category( $categories, $exclude_category );
			$args['category__not_in'] = $exclude_category;
		}

		if ( ! empty( $attr['include_author'] ) ) {
			$args['author__in'] = explode( ',', $attr['include_author'] );
		}

		if ( ! empty( $attr['include_tag'] ) ) {
			$args['tag__in'] = explode( ',', $attr['include_tag'] );
		}

		if ( ! empty( $attr['exclude_tag'] ) ) {
			$args['tag__not_in'] = explode( ',', $attr['exclude_tag'] );
		}

		if ( ! empty( $attr['include_podcast'] ) ) {
			$args['jnews-series__in'] = explode( ',', $attr['include_podcast'] );
		}

		if ( ! empty( $attr['exclude_podcast'] ) ) {
			$args['jnews-series__not_in'] = explode( ',', $attr['exclude_podcast'] );
		}

		// order
		if ( isset( $attr['sort_by'] ) ) {
			if ( 'latest' === $attr['sort_by'] ) {
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
			}

			if ( 'latest_modified' === $attr['sort_by'] ) {
				$args['orderby'] = 'modified';
				$args['order']   = 'DESC';
			}

			if ( 'oldest' === $attr['sort_by'] ) {
				$args['orderby'] = 'date';
				$args['order']   = 'ASC';
			}

			if ( 'oldest_modified' === $attr['sort_by'] ) {
				$args['orderby'] = 'modified';
				$args['order']   = 'ASC';
			}

			if ( 'alphabet_asc' === $attr['sort_by'] ) {
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
			}

			if ( 'alphabet_desc' === $attr['sort_by'] ) {
				$args['orderby'] = 'title';
				$args['order']   = 'DESC';
			}

			if ( 'random' === $attr['sort_by'] ) {
				$args['orderby'] = 'rand';
			}

			if ( 'random_week' === $attr['sort_by'] ) {
				$args['orderby']    = 'rand';
				$args['date_query'] = array(
					array(
						'after' => '1 week ago',
					),
				);
			}

			if ( 'random_month' === $attr['sort_by'] ) {
				$args['orderby']    = 'rand';
				$args['date_query'] = array(
					array(
						'after' => '1 month ago',
					),
				);
			}

			if ( 'most_comment' === $attr['sort_by'] ) {
				$args['orderby'] = 'comment_count';
				$args['order']   = 'DESC';
			}

			if ( 'rate' === $attr['sort_by'] ) {
				$args['orderby']    = 'meta_value_num';
				$args['meta_key']   = 'jnew_rating_mean';
				$args['order']      = 'DESC';
				$args['meta_query'] = array(
					'relation' => 'AND',
					array(
						'key'   => 'enable_review',
						'value' => '1',
					),
					array(
						'key'     => 'jnew_rating_mean',
						'value'   => '0',
						'compare' => '>',
					),
				);
			}

			if ( 'share' === $attr['sort_by'] ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'jnews_social_counter_total';
				$args['order']    = 'DESC';
			}

			if ( 'like' === $attr['sort_by'] ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'jnews_like_counter';
				$args['order']    = 'DESC';
			}

			if ( 'post__in' === $attr['sort_by'] ) {
				$args['orderby'] = 'post__in';
			}
		}

		// TODO : fix kalau hanya lihat post
		if ( isset( $attr['content_type'] ) ) {
			if ( 'all' === $attr['content_type'] ) {
				// do nothing
			}

			if ( 'post' === $attr['content_type'] ) {
				add_filter( 'posts_join', array( __CLASS__, 'join_only_post' ) );
				add_filter( 'posts_where', array( __CLASS__, 'where_only_post' ) );
			}

			if ( 'review' === $attr['content_type'] ) {
				$args['meta_query'] = array(
					array(
						'key'   => 'enable_review',
						'value' => '1',
					),
				);
			}
			if ( 'video' === $attr['content_type'] ) {
				$attr['video_only'] = true;
			}
		}

		if ( isset( $attr['video_only'] ) && true === $attr['video_only'] ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => array(
						'post-format-video',
					),
					'operator' => 'IN',
				),
			);
		}

		// date
		if ( isset( $attr['date_query'] ) ) {
			$args['date_query'] = $attr['date_query'];
		}

		if ( class_exists( 'Polylang' ) && isset( $attr['lang'] ) ) {
			$args['lang'] = $attr['lang'];
		}

		$args = apply_filters( 'jnews_default_query_args', $args, $attr );

		// Query
		$query = new \WP_Query( $args );

		foreach ( $query->posts as $post ) {
			$result[] = $post;
		}

		wp_reset_postdata();

		if ( isset( $attr['content_type'] ) && 'post' === $attr['content_type'] ) {
			jnews_remove_filters( 'posts_join', array( __CLASS__, 'join_only_post' ) );
			jnews_remove_filters( 'posts_where', array( __CLASS__, 'where_only_post' ) );
		}

		return array(
			'result'     => $result,
			'next'       => self::has_next_page( $query->found_posts, $args['paged'], $args['offset'], $attr['number_post'], $attr['pagination_number_post'] ),
			'prev'       => self::has_prev_page( $args['paged'] ),
			'total_page' => self::count_total_page( $query->found_posts, $args['paged'], $args['offset'], $attr['number_post'], $attr['pagination_number_post'] ),
		);
	}

	/**
	 * @param $result
	 */
	private static function optimize_query( $result ) {
		self::cache_thumbnail( $result );
	}

	/**
	 * @param $results
	 */
	public static function cache_thumbnail( $results ) {
		$thumbnails = array();

		foreach ( $results['result'] as $result ) {
			if ( ! in_array( $result->ID, self::$cache_thumbnail, true ) ) {
				$thumbnails[]            = get_post_thumbnail_id( $result->ID );
				self::$cache_thumbnail[] = $result->ID;
			}
		}

		if ( ! empty( $thumbnails ) ) {
			$query = array(
				'post__in'  => $thumbnails,
				'post_type' => 'attachment',
				'showposts' => count( $thumbnails ),
			);

			get_posts( $query );
		}
	}

	/**
	 * @param $id
	 *
	 * @return bool|false|mixed
	 */
	public static function get_podcast_by_category( $id ) {
		$query_hash = 'query_hash_podcast_by_category_' . md5( serialize( $id ) );
		if ( ! $result = jnews_podcast_cache( $query_hash ) ) {
			global $wpdb;
			$post_with_category   = "SELECT DISTINCT(p.ID) FROM {$wpdb->prefix}posts AS p
									LEFT JOIN {$wpdb->prefix}term_relationships AS tr ON(p.ID = tr.object_id)
									WHERE tr.term_taxonomy_id IN({$id})
									OR tr.term_taxonomy_id IN(
										SELECT DISTINCT(tt.term_taxonomy_id) FROM {$wpdb->prefix}term_taxonomy AS tt
										WHERE tt.parent IN({$id})
									)
									AND p.post_type = 'post'
									AND(p.post_status = 'publish')";
			$category_with_series = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'jnews-series'";
			$query                = "SELECT DISTINCT(term_taxonomy_id) FROM {$wpdb->prefix}term_relationships WHERE object_id IN({$post_with_category}) AND term_taxonomy_id IN({$category_with_series})";
			$query                = $wpdb->get_results( $query );

			$result_ids = array();

			if ( ! empty( $query ) ) {
				foreach ( $query as $result ) {
					$result_ids[] = jnews_podacst_get_term_translate_id( $result->term_taxonomy_id );
				}
			}

			$result = jnews_podcast_cache( $query_hash, $result_ids );
		}

		return $result;
	}

	/**
	 * @param $attr
	 *
	 * @return array|bool|mixed|null
	 */
	public static function get_podcast_base_on( $attr ) {
		$query_hash = 'query_hash_' . md5( serialize( $attr ) );
		$field      = isset( $attr['podcast_base_on'] ) ? 'podcast_base_on' : 'sort_by';
		$random     = 'random' === $attr[ $field ];

		if ( ( ! $result = wp_cache_get( $query_hash, 'jnews-podcast' ) ) || $random ) {
			$order = self::check_custom_order( $field, $attr );
			if ( 'custom' === $order ) {
				$result = self::custom_query( $field, $attr );
			} elseif ( 'jetpack' === $order ) {
				$result = self::jetpack_query( $field, $attr );
			} else {
				$result = self::get_random_podcast( $field, $attr );
			}
			wp_cache_set( $query_hash, $result, 'jnews-podcast' );
		}

		return $result;
	}

	/**
	 * Get random podcast of jnews-series
	 *
	 * @param $field
	 * @param $attr
	 *
	 * @return int|\WP_Term[]|null |null
	 */
	public static function get_random_podcast( $field, $attr ) {
		$default = array(
			'number'     => 5,
			'offset'     => 0,
			'paged'      => 1,
			'order'      => 'ASC',
			'orderby'    => 'name',
			'hide_empty' => false,
		);
		$args    = array();
		$order   = array( 'ASC', 'DESC' );

		$attr['number_post']            = isset( $attr['number_post'] ) ? $attr['number_post'] : get_option( 'posts_per_page' );
		$attr['pagination_number_post'] = isset( $attr['pagination_number_post'] ) ? $attr['pagination_number_post'] : $attr['number_post'];

		$args['paged']         = isset( $attr['paged'] ) ? $attr['paged'] : 1;
		$args['offset']        = self::calculate_offset( $args['paged'], $attr['post_offset'], $attr['number_post'], $attr['pagination_number_post'] );
		$args['number']        = ( $args['paged'] > 1 ) ? $attr['pagination_number_post'] : $attr['number_post'];
		$args['no_found_rows'] = ! isset( $attr['pagination_mode'] ) || 'disable' === $attr['pagination_mode'];

		if ( 'alphabet_asc' === $attr[ $field ] ) {
			$args['order'] = $order[0];
		}

		if ( 'alphabet_desc' === $attr[ $field ] ) {
			$args['order'] = $order[1];
		}

		if ( 'id_asc' === $attr[ $field ] ) {
			$args['orderby'] = 'term_id';
			$args['order']   = $order[0];
		}

		if ( 'id_desc' === $attr[ $field ] ) {
			$args['orderby'] = 'term_id';
			$args['order']   = $order[1];
		}

		if ( 'count_asc' === $attr[ $field ] ) {
			$args['orderby'] = 'count';
			$args['order']   = $order[0];
		}

		if ( 'count_desc' === $attr[ $field ] ) {
			$args['orderby'] = 'count';
			$args['order']   = $order[1];
		}

		if ( 'include_asc' === $attr[ $field ] ) {
			$args['orderby'] = 'include';
			$args['order']   = $order[0];
		}

		if ( 'include_desc' === $attr[ $field ] ) {
			$args['orderby'] = 'include';
			$args['order']   = $order[1];
		}

		if ( isset( $attr['include_podcast'] ) ) {
			$args['include'] = $attr['include_podcast'];
		}

		if ( isset( $attr['exclude_podcast'] ) ) {
			$args['exclude'] = $attr['exclude_podcast'];
		}

		$args = wp_parse_args( $args, $default );

		$total_podcast = jnews_get_series(
			array_merge(
				$args,
				array(
					'fields' => 'count',
					'offset' => 0,
				)
			)
		);

		$podcast = jnews_get_series( $args );
		if ( ( 'random' === $attr[ $field ] ) && ! empty( $podcast ) && ! is_wp_error( $podcast ) ) {
			shuffle( $podcast );
		}

		if ( ! is_wp_error( $podcast ) && ! empty( $podcast ) ) {
			$total = $total_podcast;

			return array(
				'result'     => $podcast,
				'next'       => self::has_next_page( $total, $args['paged'], $args['offset'], $attr['number_post'], $attr['pagination_number_post'] ),
				'prev'       => self::has_prev_page( $args['paged'] ),
				'total_page' => self::count_total_page( $total, $args['paged'], $args['offset'], $attr['number_post'], $attr['pagination_number_post'] ),
			);
		}

		return array(
			'result'     => array(),
			'next'       => false,
			'prev'       => false,
			'total_page' => 0,
		);
	}
}
