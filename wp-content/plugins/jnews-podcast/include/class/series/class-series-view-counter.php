<?php
/**
 * @author Jegtheme
 */

namespace JNEWS_PODCAST\Series;

/**
 * Class Series_View_Counter
 *
 * @package JNEWS_PODCAST\Series
 */
class Series_View_Counter extends Series {
	/**
	 * @var Series_View_Counter
	 */
	private static $instance;

	/**
	 * Series_View_Counter constructor.
	 */
	private function __construct() {

	}

	/**
	 * @return Series_View_Counter
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Custom Query JNews. Add ability to receive Paging Parameter and Tag Parameter
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	public function query( $instance ) {
		global $wpdb;
		$default = array(
			'limit'            => 10,
			'offset'           => 0,
			'paged'            => 1,
			'range'            => 'all',
			'freshness'        => false,
			'order_by'         => 'views',
			'post_type'        => 'post',
			'include_post'     => '',
			'exclude_post'     => '',
			'include_category' => '',
			'exclude_category' => '',
			'include_tag'      => '',
			'exclude_tag'      => '',
			'author'           => '',
		);
		$slug    = self::$slug;

		// parse instance values
		$instance = $this->merge_array_r(
			$default,
			$instance
		);

		$prefix  = $wpdb->prefix . 'popularposts';
		$fields  = 'tr.term_taxonomy_id as id';
		$join    = " LEFT JOIN {$wpdb->term_relationships} tr ON tr.`object_id` = p.ID JOIN {$wpdb->term_taxonomy} AS tx ON tr.term_taxonomy_id = tx.term_taxonomy_id ";
		$where   = 'WHERE 1 = 1';
		$orderby = '';
		$groupby = '';

		$limit = 'LIMIT ' . (int) $instance['offset'] . ", {$instance['limit']}";

		$now = $this->now();

		// post filters
		if ( $instance['freshness'] ) {
			switch ( $instance['range'] ) {
				case 'daily':
					$where .= " AND p.post_date > DATE_SUB('{$now}', INTERVAL 1 DAY) ";
					break;

				case 'weekly':
					$where .= " AND p.post_date > DATE_SUB('{$now}', INTERVAL 1 WEEK) ";
					break;

				case 'monthly':
					$where .= " AND p.post_date > DATE_SUB('{$now}', INTERVAL 1 MONTH) ";
					break;

				default:
					$where .= '';
					break;
			}
		}

		// * post type
		$where .= " AND p.post_type = '{$instance['post_type']}'";

		// * post include & exclude
		if ( ! empty( $instance['include_post'] ) ) {
			$where .= " AND p.ID IN ({$instance['include_post']})";
		}

		if ( ! empty( $instance['exclude_post'] ) ) {
			$where .= " AND p.ID NOT IN({$instance['exclude_post']})";
		}

		// * categories
		if ( '' !== $instance['include_category'] || '' !== $instance['exclude_category'] ) {
			if ( '' !== $instance['include_category'] && '' == $instance['exclude_category'] ) {
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'category' AND x.term_id IN({$instance['include_category']})
                    )";
			} elseif ( '' === $instance['include_category'] && '' !== $instance['exclude_category'] ) {
				$where .= " AND p.ID NOT IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'category' AND x.term_id IN({$instance['exclude_category']})
                    )";
			} else { // mixed
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'category' AND x.term_id IN({$instance['include_category']}) AND x.term_id NOT IN({$instance['exclude_category']})
                    ) ";
			}
		}

		// * tag
		if ( '' !== $instance['include_tag'] || '' !== $instance['exclude_tag'] ) {
			if ( '' !== $instance['include_tag'] && '' == $instance['exclude_tag'] ) {
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'post_tag' AND x.term_id IN({$instance['include_tag']})
                    )";
			} elseif ( '' === $instance['include_tag'] && '' !== $instance['exclude_tag'] ) {
				$where .= " AND p.ID NOT IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'post_tag' AND x.term_id IN({$instance['exclude_tag']})
                    )";
			} else { // mixed
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'post_tag' AND x.term_id IN({$instance['include_tag']}) AND x.term_id NOT IN({$instance['exclude_tag']})
                    ) ";
			}
		}

		// * authors
		if ( ! empty( $instance['author'] ) ) {
			$where .= " AND p.post_author IN({$instance['author']})";
		}

		// * All-time range
		if ( 'all' == $instance['range'] ) {

			$fields .= ", p.comment_count AS 'comment_count'";

			// order by comments
			if ( 'comments' == $instance['order_by'] ) {

				$from    = "{$wpdb->posts} p";
				$where  .= ' AND p.comment_count > 0 ';
				$orderby = ' ORDER BY p.comment_count DESC';

			} // order by (avg) views
			else {

				$from = "{$prefix}data v LEFT JOIN {$wpdb->posts} p ON v.postid = p.ID";

				// order by views
				if ( 'views' == $instance['order_by'] ) {

					$fields .= ", v.pageviews AS 'pageviews'";
					$orderby = 'ORDER BY pageviews DESC';

				} // order by avg views
				elseif ( 'avg' == $instance['order_by'] ) {

					$fields .= ", ( v.pageviews/(IF ( DATEDIFF('{$now}', MIN(v.day)) > 0, DATEDIFF('{$now}', MIN(v.day)), 1) ) ) AS 'avg_views'";
					$groupby = 'GROUP BY v.postid';
					$orderby = 'ORDER BY avg_views DESC';

				}
			}
		} else { // CUSTOM RANGE

			switch ( $instance['range'] ) {
				case 'weekly':
					$interval = '1 WEEK';
					break;

				case 'monthly':
					$interval = '1 MONTH';
					break;

				default:
					$interval = '1 DAY';
					break;
			}

			// order by comments
			if ( 'comments' == $instance['order_by'] ) {

				$fields .= ", COUNT(c.comment_post_ID) AS 'comment_count'";
				$from    = "{$wpdb->comments} c LEFT JOIN {$wpdb->posts} p ON c.comment_post_ID = p.ID";
				$where  .= " AND c.comment_date_gmt > DATE_SUB('{$now}', INTERVAL {$interval}) AND c.comment_approved = 1 ";
				$groupby = 'GROUP BY c.comment_post_ID';
				$orderby = 'ORDER BY comment_count DESC';

			} // ordered by views / avg
			else {

				$from    = "{$prefix}summary v LEFT JOIN {$wpdb->posts} p ON v.postid = p.ID";
				$where  .= " AND v.view_datetime > DATE_SUB('{$now}', INTERVAL {$interval}) ";
				$groupby = 'GROUP BY v.postid';

				// ordered by views
				if ( 'views' == $instance['order_by'] ) {

					$fields .= ", SUM(v.pageviews) AS 'pageviews'";
					$orderby = 'ORDER BY pageviews DESC';

				} // ordered by avg views
				elseif ( 'avg' == $instance['order_by'] ) {

					$fields .= ", ( SUM(v.pageviews)/(IF ( DATEDIFF('{$now}', DATE_SUB('{$now}', INTERVAL {$interval})) > 0, DATEDIFF('{$now}', DATE_SUB('{$now}', INTERVAL {$interval})), 1) ) ) AS 'avg_views' ";
					$orderby = 'ORDER BY avg_views DESC';

				}
			}
		}

		// List only published, non password-protected posts
		$where .= " AND p.post_password = '' AND p.post_status = 'publish'";
		// List only jnews-series post
		$where .= " AND tx.taxonomy = '{$slug}'";
		// join added table
		$from   .= $join;
		$groupby = 'GROUP BY tr.term_taxonomy_id';
		// Build query
		$query = "SELECT {$fields} FROM {$from} {$where} {$groupby} {$orderby} {$limit};";
		$query = $wpdb->get_results( $query );

		if ( isset( $instance['no_found_rows'] ) && ! $instance['no_found_rows'] ) {
			$total_row = $wpdb->get_results( "SELECT COUNT(*) as total FROM {$from} {$where} {$groupby}" );
			$total_row = $groupby ? count( $total_row ) : $total_row[0]->total;
		} else {
			$total_row = 0;
		}

		$result_ids = array();

		foreach ( $query as $result ) {
			$result_ids[] = jnews_podacst_get_term_translate_id( $result->id );
		}

		$all_podcast = get_terms(
			array(
				'taxonomy' => $slug,
				'include'  => $result_ids,
				'number'   => empty( $result_ids ) ? $instance['limit'] : count( $result_ids ),
			)
		);

		$results = $this->podcast_arrange_index( $all_podcast, $result_ids );

		return array(
			'result' => $results,
			'total'  => $total_row,
		);
	}

	/**
	 * Merges two associative arrays recursively
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array
	 */
	private function merge_array_r( array &$array1, array &$array2 ) {

		$merged = $array1;

		foreach ( $array2 as $key => &$value ) {

			if ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = $this->merge_array_r( $merged[ $key ], $value );
			} else {
				$merged[ $key ] = $value;
			}
		}

		return $merged;

	}

	/**
	 * Returns mysql datetime
	 *
	 * @return  string
	 */
	private function now() {
		return current_time( 'mysql' );
	}

	/**
	 * @param $result
	 * @param $results_id
	 *
	 * @return array
	 */
	public function podcast_arrange_index( $result, $results_id ) {
		$new_result = array();

		foreach ( $results_id as $id ) {

			foreach ( $result as $podcast ) {
				if ( $id == $podcast->term_id ) {
					$new_result[] = $podcast;
					break;
				}
			}
		}

		return $new_result;
	}

	/**
	 * Custom Query JNews. Add ability to receive Paging Parameter and Tag Parameter
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	public function default_query( $instance ) {
		global $wpdb;
		$default = array(
			'limit'            => 10,
			'offset'           => 0,
			'paged'            => 1,
			'range'            => 'all',
			'freshness'        => false,
			'order_by'         => 'views',
			'post_type'        => 'post',
			'include_post'     => '',
			'exclude_post'     => '',
			'include_category' => '',
			'exclude_category' => '',
			'include_tag'      => '',
			'exclude_tag'      => '',
			'author'           => '',
		);
		$slug    = self::$slug;

		// parse instance values
		$instance = $this->merge_array_r(
			$default,
			$instance
		);

		$prefix  = $wpdb->prefix . 'popularposts';
		$fields  = "p.ID AS 'id', p.post_title AS 'title', p.post_date AS 'date', p.post_author AS 'uid'";
		$where   = 'WHERE 1 = 1';
		$orderby = '';
		$groupby = '';

		$limit = 'LIMIT ' . (int) $instance['offset'] . ", {$instance['limit']}";

		$now = $this->now();

		// post filters
		if ( $instance['freshness'] ) {
			switch ( $instance['range'] ) {
				case 'daily':
					$where .= " AND p.post_date > DATE_SUB('{$now}', INTERVAL 1 DAY) ";
					break;

				case 'weekly':
					$where .= " AND p.post_date > DATE_SUB('{$now}', INTERVAL 1 WEEK) ";
					break;

				case 'monthly':
					$where .= " AND p.post_date > DATE_SUB('{$now}', INTERVAL 1 MONTH) ";
					break;

				default:
					$where .= '';
					break;
			}
		}

		// * post type
		$where .= " AND p.post_type = '{$instance['post_type']}'";

		// * post include & exclude
		if ( ! empty( $instance['include_post'] ) ) {
			$where .= " AND p.ID IN ({$instance['include_post']})";
		}

		if ( ! empty( $instance['exclude_post'] ) ) {
			$where .= " AND p.ID NOT IN({$instance['exclude_post']})";
		}

		// * categories
		if ( '' !== $instance['include_category'] || '' !== $instance['exclude_category'] ) {
			if ( '' !== $instance['include_category'] && '' == $instance['exclude_category'] ) {
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'category' AND x.term_id IN({$instance['include_category']})
                    )";
			} elseif ( '' === $instance['include_category'] && '' !== $instance['exclude_category'] ) {
				$where .= " AND p.ID NOT IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'category' AND x.term_id IN({$instance['exclude_category']})
                    )";
			} else { // mixed
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'category' AND x.term_id IN({$instance['include_category']}) AND x.term_id NOT IN({$instance['exclude_category']})
                    ) ";
			}
		}

		// * tag
		if ( '' !== $instance['include_tag'] || '' !== $instance['exclude_tag'] ) {
			if ( '' !== $instance['include_tag'] && '' == $instance['exclude_tag'] ) {
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'post_tag' AND x.term_id IN({$instance['include_tag']})
                    )";
			} elseif ( '' === $instance['include_tag'] && '' !== $instance['exclude_tag'] ) {
				$where .= " AND p.ID NOT IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'post_tag' AND x.term_id IN({$instance['exclude_tag']})
                    )";
			} else { // mixed
				$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = 'post_tag' AND x.term_id IN({$instance['include_tag']}) AND x.term_id NOT IN({$instance['exclude_tag']})
                    ) ";
			}
		}

		// * podcast
		if ( isset( $instance['include_podcast_episode'] ) && ! in_array(
			$instance['include_podcast_episode'],
			array(
				'',
				'none',
			),
			true
		) ) {
			$where .= " AND p.ID IN (
                    SELECT object_id
                    FROM {$wpdb->term_relationships} AS r
                         JOIN {$wpdb->term_taxonomy} AS x ON x.term_taxonomy_id = r.term_taxonomy_id
                    WHERE x.taxonomy = '{$slug}' AND x.term_id IN({$instance['include_podcast_episode']})
                    )";
		}

		// * authors
		if ( ! empty( $instance['author'] ) ) {
			$where .= " AND p.post_author IN({$instance['author']})";
		}

		// * All-time range
		if ( 'all' == $instance['range'] ) {

			$fields .= ", p.comment_count AS 'comment_count'";

			// order by comments
			if ( 'comments' == $instance['order_by'] ) {

				$from    = "{$wpdb->posts} p";
				$where  .= ' AND p.comment_count > 0 ';
				$orderby = ' ORDER BY p.comment_count DESC';

			} // order by (avg) views
			else {

				$from = "{$prefix}data v LEFT JOIN {$wpdb->posts} p ON v.postid = p.ID";

				// order by views
				if ( 'views' == $instance['order_by'] ) {

					$fields .= ", v.pageviews AS 'pageviews'";
					$orderby = 'ORDER BY pageviews DESC';

				} // order by avg views
				elseif ( 'avg' == $instance['order_by'] ) {

					$fields .= ", ( v.pageviews/(IF ( DATEDIFF('{$now}', MIN(v.day)) > 0, DATEDIFF('{$now}', MIN(v.day)), 1) ) ) AS 'avg_views'";
					$groupby = 'GROUP BY v.postid';
					$orderby = 'ORDER BY avg_views DESC';

				}
			}
		} else { // CUSTOM RANGE

			switch ( $instance['range'] ) {
				case 'weekly':
					$interval = '1 WEEK';
					break;

				case 'monthly':
					$interval = '1 MONTH';
					break;

				default:
					$interval = '1 DAY';
					break;
			}

			// order by comments
			if ( 'comments' == $instance['order_by'] ) {

				$fields .= ", COUNT(c.comment_post_ID) AS 'comment_count'";
				$from    = "{$wpdb->comments} c LEFT JOIN {$wpdb->posts} p ON c.comment_post_ID = p.ID";
				$where  .= " AND c.comment_date_gmt > DATE_SUB('{$now}', INTERVAL {$interval}) AND c.comment_approved = 1 ";
				$groupby = 'GROUP BY c.comment_post_ID';
				$orderby = 'ORDER BY comment_count DESC';

			} // ordered by views / avg
			else {

				$from    = "{$prefix}summary v LEFT JOIN {$wpdb->posts} p ON v.postid = p.ID";
				$where  .= " AND v.view_datetime > DATE_SUB('{$now}', INTERVAL {$interval}) ";
				$groupby = 'GROUP BY v.postid';

				// ordered by views
				if ( 'views' == $instance['order_by'] ) {

					$fields .= ", SUM(v.pageviews) AS 'pageviews'";
					$orderby = 'ORDER BY pageviews DESC';

				} // ordered by avg views
				elseif ( 'avg' == $instance['order_by'] ) {

					$fields .= ", ( SUM(v.pageviews)/(IF ( DATEDIFF('{$now}', DATE_SUB('{$now}', INTERVAL {$interval})) > 0, DATEDIFF('{$now}', DATE_SUB('{$now}', INTERVAL {$interval})), 1) ) ) AS 'avg_views' ";
					$orderby = 'ORDER BY avg_views DESC';

				}
			}
		}

		// List only published, non password-protected posts
		$where .= " AND p.post_password = '' AND p.post_status = 'publish'";

		// Build query
		$query = "SELECT {$fields} FROM {$from} {$where} {$groupby} {$orderby} {$limit};";
		$query = $wpdb->get_results( $query );

		if ( isset( $instance['no_found_rows'] ) && ! $instance['no_found_rows'] ) {
			$total_row = $wpdb->get_results( "SELECT COUNT(*) as total FROM {$from} {$where} {$groupby}" );
			$total_row = $groupby ? count( $total_row ) : $total_row[0]->total;
		} else {
			$total_row = 0;
		}

		$result_ids = array();

		foreach ( $query as $result ) {
			$result_ids[] = $this->get_translate_id( $result->id );
		}

		$all_post = get_posts(
			array(
				'post__in'  => $result_ids,
				'post_type' => 'post',
				'showposts' => empty( $result_ids ) ? $instance['limit'] : count( $result_ids ),
			)
		);

		$results = $this->arrange_index( $all_post, $result_ids );

		return array(
			'result' => $results,
			'total'  => $total_row,
		);
	}

	/**
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function get_translate_id( $post_id ) {
		if ( function_exists( 'pll_get_post' ) ) {
			$result_id = pll_get_post( $post_id, pll_current_language() );

			if ( $result_id ) {
				$post_id = $result_id;
			}
		}

		return $post_id;
	}

	/**
	 * @param $result
	 * @param $results_id
	 *
	 * @return array
	 */
	public function arrange_index( $result, $results_id ) {
		$new_result = array();

		foreach ( $results_id as $id ) {
			foreach ( $result as $post ) {
				if ( $id == $post->ID ) {
					$new_result[] = $post;
					break;
				}
			}
		}

		return $new_result;
	}
}
