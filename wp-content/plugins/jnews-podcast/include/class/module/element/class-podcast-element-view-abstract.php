<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Module\Element;

use JNews\Image\Image;
use JNews\Image\ImageNormalLoad;
use JNews\Module\Block\BlockViewAbstract;
use JNEWS_PODCAST\Module\Module_Query;

/**
 * Class Podcast_Element_View_Abstract
 *
 * @package JNEWS_PODCAST\Module\Element
 */
abstract class Podcast_Element_View_Abstract extends BlockViewAbstract {

	/**
	 * @param $attr
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_module( $attr, $column_class ) {
		$heading       = $this->render_header( $attr );
		$style_output  = jnews_header_styling( $attr, $this->unique_id . ' ' );
		$style_output .= jnews_podcast_module_custom_color( $attr, $this->unique_id . ' ', $this->get_name() );
		$content       = $this->render_output( $attr, $column_class );
		$style         = ! empty( $style_output ) ? "<style scoped>{$style_output}</style>" : '';
		$script        = $this->render_script( $attr, $column_class );
		$pagination    = isset( $attr['pagination_mode'] ) ? 'jeg_pagination_' . $attr['pagination_mode'] : '';

		return "<div {$this->element_id($attr)} class=\"jnews_podcast jeg_postblock_{$this->get_name()} jeg_postblock jeg_module_hook $pagination {$column_class} {$this->unique_id} {$this->get_vc_class_name()} {$this->color_scheme()} {$attr['el_class']}\" data-unique=\"{$this->unique_id}\">
					{$heading}
					{$content}
					{$style}
					{$script}
				</div>";
	}

	/**
	 * @param $attr
	 *
	 * @return string
	 */
	public function render_header( $attr ) {
		/** Don't Render Header Filter */
		$excludes = array(
			'header_filter_text',
			'header_filter_category',
			'header_filter_author',
			'header_filter_tag',
		);
		foreach ( $excludes as $key => $exclude ) {
			if ( isset( $attr[ $exclude ] ) ) {
				unset( $attr[ $exclude ] );
			}
		}

		return parent::render_header( $attr );
	}

	/**
	 * @return mixed
	 */
	abstract public function get_name();

	/**
	 * @param $attr
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_output( $attr, $column_class ) {
		if ( isset( $attr['results'] ) ) {
			$results = $attr['results'];
		} else {
			$results = $this->build_query( $attr );
		}

		$navigation = $this->render_navigation( $attr, $results['next'], $results['prev'], $results['total_page'] );

		if ( ! empty( $results ) && isset( $results['result'] ) && ! empty( $results['result'] ) ) {
			$content = $this->render_column( $results['result'], $column_class );
		} else {
			$content = $this->empty_content();
		}

		return "<div class=\"jeg_block_container\">
                {$this->get_content_before($attr)}
                {$content}
                {$this->get_content_after($attr)}
            </div>
            <div class=\"jeg_block_navigation\">
                {$this->get_navigation_before($attr)}
                {$navigation}
                {$this->get_navigation_after($attr)}
            </div>";
	}

	/**
	 * @param $attr
	 *
	 * @return array|int|string|\WP_Error
	 */
	protected function build_query( $attr ) {
		if ( in_array( $this->get_name(), array( 'episode_list', 'episode_detail', 'episode_1', 'episode_2' ), true ) ) {
			return $this->default_query( $attr );
		}

		// we need to create custom unique content for podcast
		if ( isset( $attr['unique_content_podcast'] ) && 'disable' !== $attr['unique_content_podcast'] ) {
			if ( ! empty( $attr['exclude_podcast'] ) ) {
				$exclude_podcast = explode( ',', $attr['exclude_podcast'] );
			} else {
				$exclude_podcast = array();
			}

			$exclude_podcast         = array_merge( $this->get_unique_podcast( $attr['unique_content_podcast'] ), $exclude_podcast );
			$attr['exclude_podcast'] = implode( ',', $exclude_podcast );
			// we need to alter attribute here...
			$this->set_attribute( $attr );
		}

		$result = Module_Query::get_podcast_base_on( $attr );
		if ( isset( $attr['unique_content_podcast'] ) && $attr['unique_content_podcast'] !== 'disable' ) {
			$this->add_unique_podcast( $attr['unique_content_podcast'], $this->collect_podcast_id( $result ) );
		}
		return $result;
	}

	protected function collect_podcast_id( $content ) {
		$podcast_ids = array();
		foreach ( $content['result'] as $result ) {
			$podcast_ids[] = $result->term_id;
		}

		return $podcast_ids;
	}

	/**
	 * push unique podcast to array
	 *
	 * @param $group
	 * @param $unique
	 */
	public function add_unique_podcast( $group, $unique ) {
		if ( ! isset( $this->manager->unique_podcast ) ) {
			$this->manager->unique_podcast = array();
		}

		if ( ! isset( $this->manager->unique_podcast[ $group ] ) ) {
			$this->manager->unique_podcast[ $group ] = array();
		}

		if ( is_array( $unique ) ) {
			$this->manager->unique_podcast[ $group ] = array_merge( $this->manager->unique_podcast[ $group ], $unique );
		} else {
			array_push( $this->manager->unique_podcast[ $group ], $unique );
		}
	}

	/**
	 * @param $group
	 * @return array
	 */
	public function get_unique_podcast( $group ) {
		if ( ! isset( $this->manager->unique_podcast ) ) {
			$this->manager->unique_podcast = array();
		}
		if ( isset( $this->manager->unique_podcast[ $group ] ) ) {
			return $this->manager->unique_podcast[ $group ];
		} else {
			return array();
		}
	}

	/**
	 * default query
	 *
	 * @param $attr
	 *
	 * @return array
	 */
	protected function default_query( $attr ) {
		if ( isset( $attr['unique_content'] ) && 'disable' !== $attr['unique_content'] ) {
			if ( ! empty( $attr['exclude_post'] ) ) {
				$exclude_post = explode( ',', $attr['exclude_post'] );
			} else {
				$exclude_post = array();
			}

			$exclude_post         = array_merge( $this->manager->get_unique_article( $attr['unique_content'] ), $exclude_post );
			$attr['exclude_post'] = implode( ',', $exclude_post );

			// we need to alter attribute here...
			$this->set_attribute( $attr );
		}

		$result = Module_Query::podcast_query( $attr );

		if ( isset( $attr['unique_content'] ) && 'disable' !== $attr['unique_content'] ) {
			$this->manager->add_unique_article( $attr['unique_content'], $this->collect_post_id( $result ) );
		}

		if ( isset( $result['result'] ) ) {
			foreach ( $result['result'] as $post ) {
				do_action( 'jnews_json_archive_push', $post->ID );
			}
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function empty_content() {
		if ( function_exists( 'JNews_View_Counter' ) ) {
			return parent::empty_content();
		}

		return '<div class="alert alert-error">' .
			   '<strong>' . esc_html__( 'Plugin Install', 'jnews-podcast' ) . '</strong>' . ' : ' . esc_html__( 'Popular Podcast need JNews - View Counter to be installed', 'jnews-podcast' ) .
			   '</div>';
	}

	/**
	 * @param $result
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_module_out_call( $result, $column_class ) {
		$name = str_replace( 'jnews_block_', '', $this->class_name );

		if ( ! empty( $result ) ) {
			$content = $this->render_column( $result, $column_class );
		} else {
			$content = $this->empty_content();
		}

		return "<div class=\"jeg_postblock_{$name} jeg_postblock {$column_class}\">
					<div class=\"jeg_block_container\">
						{$content}
					</div>
				</div>";

	}

	/**
	 * @param $result
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_column_alt( $result, $column_class ) {
		switch ( $column_class ) {
			case 'jeg_col_1o3':
			case 'jeg_col_3o3':
			case 'jeg_col_2o3':
			default:
				$content = $this->build_column_1_alt( $result );
				break;
		}

		return $content;
	}

	/**
	 * get thumbnail
	 *
	 * @param $id
	 * @param $size
	 * @param string $type
	 *
	 * @return mixed|string|void
	 */
	public function get_thumbnail( $id, $size, $type = 'term' ) {
		$value = '';
		switch ( $type ) {
			case 'term':
				$image_id = $id;
				if ( ! empty( $image_id ) ) {
					if ( isset( $this->attribute['force_normal_image_load'] ) && ( 'true' === $this->attribute['force_normal_image_load'] || 'yes' === $this->attribute['force_normal_image_load'] ) ) {
						$value = ImageNormalLoad::getInstance()->single_image_unwrap( $image_id, $size );
					} else {
						$value = apply_filters( 'jnews_single_image_unwrap', $image_id, $size );
					}
				} else {
					$value = $this->get_thumbnail( sprintf( '%s/wp-includes/images/media/%s.png', get_site_url(), 'audio' ), $size, 'custom_url' );
				}
				break;
			case 'post':
				$post_id = $id;
				if ( isset( $this->attribute['force_normal_image_load'] ) && ( 'true' === $this->attribute['force_normal_image_load'] || 'yes' === $this->attribute['force_normal_image_load'] ) ) {
					$value = ImageNormalLoad::getInstance()->image_thumbnail( $post_id, $size );
				} else {
					$value = apply_filters( 'jnews_image_thumbnail', $post_id, $size );
				}
				break;
			case 'custom_url':
				$img_src    = $id;
				$img_title  = '';
				$image_size = Image::getInstance()->get_image_size( $size );
				$dimension  = $image_size['dimension'];
				if ( isset( $this->attribute['force_normal_image_load'] ) && ( 'true' === $this->attribute['force_normal_image_load'] || 'yes' === $this->attribute['force_normal_image_load'] ) ) {
					$value = ImageNormalLoad::getInstance()->single_image( $img_src, $img_title, $dimension );
				} else {
					$value = apply_filters( 'jnews_single_image', $img_src, $img_title, $dimension );
				}
				break;
		}

		return $value;
	}

	/**
	 * @param $object
	 * @param string $type
	 *
	 * @return mixed|void
	 */
	protected function get_excerpt( $object, $type = 'post' ) {
		switch ( $type ) {
			case 'podcast':
				$id      = $object->term_id;
				$excerpt = $object->description;
				$excerpt = preg_replace( '/\[[^\]]+\]/', '', $excerpt );
				$excerpt = wp_trim_words( $excerpt, $this->excerpt_length(), $this->except_more() );
				break;
			default:
				$id      = $object->ID;
				$excerpt = parent::get_excerpt( $object );
				break;
		}

		return apply_filters( 'jnews_module_excerpt', $excerpt, $id, $this->excerpt_length(), $this->except_more() );
	}
}
