<?php

namespace JNEWS_PODCAST\Series;

/**
 * Class Object_Series
 *
 * @package JNEWS_PODCAST\Series
 */
abstract class Object_Series {
	/**
	 * Slug podcast series
	 *
	 * @var string
	 */
	protected static $slug = 'jnews-series';
	/**
	 * ID podcast series
	 *
	 * @var int
	 */
	protected $series_id;

	/**
	 * Get Series slug
	 */
	public static function get_slug() {
		return self::$slug;
	}

	/**
	 * Set series id
	 *
	 * @param $series_id
	 */
	public function set_series_id( $series_id ) {
		$this->series_id = $series_id;
	}

	/**
	 * Get Series ID
	 *
	 * @return int
	 */
	public function get_series_id() {
		return $this->series_id;
	}

	/**
	 * Set series image
	 *
	 * @param $attachment_id
	 * @param $term_id
	 * @param bool          $replace
	 */
	protected function set_series_image( $attachment_id, $term_id, $replace = true ) {
		$continue    = true;
		$term_images = get_option( 'jnews_' . self::$slug . '_term_image', array() );
		if ( ! $replace ) {
			$continue = ( ! isset( $term_images[ $term_id ] ) );
		}
		if ( $continue ) {
			if ( is_array( $term_images ) ) {
				$term_images[ $term_id ] = $attachment_id;
			} else {
				$term_images = array(
					$term_id => $attachment_id,
				);
			}
			update_option( 'jnews_' . self::$slug . '_term_image', $term_images );
		}
	}

	/**
	 * Get default podcast image id
	 * Use term image if default podcast image not exist
	 *
	 * @param int $term_id
	 *
	 * @return bool|mixed|void
	 */
	protected function get_series_default_image_id( $term_id ) {
		$default_image = false;
		$podcast_image = $this->get_podcast_default_image_id( $term_id );
		if ( $podcast_image ) {
			$default_image = $podcast_image;
		} else {
			$term_image = self::get_series_image_id( $term_id );
			if ( $term_image ) {
				$default_image = $term_image;
			}
		}

		return $default_image;
	}

	/**
	 * Get default podcast image id
	 *
	 * @param int $term_id
	 *
	 * @return bool|mixed
	 */
	protected function get_podcast_default_image_id( $term_id ) {
		$podcast_image = false;
		$term_id       = (int) $term_id;
		if ( ! $term_id ) {
			return $podcast_image;
		}

		$podcast_images = get_option( 'jnews_' . self::$slug . '_podcast_image', array() );
		if ( is_array( $podcast_images ) && isset( $podcast_images[ $term_id ] ) ) {
			$podcast_image = $podcast_images[ $term_id ];
		}

		return $podcast_image;
	}

	/**
	 * Get Series image id
	 *
	 * @param int $term_id
	 *
	 * @return bool|mixed
	 */
	public static function get_series_image_id( $term_id ) {
		$term_image = false;
		$term_id    = (int) $term_id;
		if ( ! $term_id ) {
			return $term_image;
		}

		$term_images = get_option( 'jnews_' . self::$slug . '_term_image', array() );
		if ( is_array( $term_images ) && isset( $term_images[ $term_id ] ) && ! empty( $term_images[ $term_id ] ) ) {
			$term_image = $term_images[ $term_id ];
		}

		return $term_image;
	}

	/**
	 * Determines whether a term has an image attached.
	 *
	 * @param $term_id
	 *
	 * @return bool
	 */
	protected function has_term_thumbnail( $term_id ) {
		$thumbnail_id = self::get_series_image_id( $term_id );

		return (bool) $thumbnail_id;
	}
}
