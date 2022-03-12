<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Module\Element\Episode;

use JNEWS_PODCAST\Module\Element\Podcast_Element_View_Abstract;
use WP_Post;

/**
 * Class Episode_View_Abstract
 */
abstract class Episode_View_Abstract extends Podcast_Element_View_Abstract {

	/**
	 * @param $result
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_column( $result, $column_class ) {
		switch ( $column_class ) {
			case 'jeg_col_2o3':
			case 'jeg_col_1o3':
			case 'jeg_col_3o3':
			default:
				$content = $this->build_column_1( $result );
				break;
		}

		return $content;
	}

	/**
	 * @param $results
	 *
	 * @return string
	 */
	public function build_column_1( $results ) {

		$size = count( $results );

		$first_block = '';
		for ( $i = 0; $i < $size; $i ++ ) {
			$first_block .= $this->render_block_type_1( $results[ $i ], 'jnews-350x350' );
		}

		return "<div class=\"jeg_posts_wrap\">
					<div class=\"jeg_posts jeg_load_more_flag\"> 
	                    	$first_block
					</div>
				</div>";
	}

	/**
	 * @param WP_Post    $post
	 * @param $image_size
	 *
	 * @return string
	 */
	public function render_block_type_1( $post, $image_size ) {
		$series = isset( $this->attribute['include_podcast_episode'] ) && 'none' !== $this->attribute['include_podcast_episode'] ? $this->attribute['include_podcast_episode'] : wp_get_post_terms( $post->ID, 'jnews-series' );
		$series = is_wp_error( $series ) ? '' : $series;
		$series = is_array( $series ) ? $series[0] : $series;
		if ( has_post_thumbnail( $post->ID ) ) {
			$image = $this->get_thumbnail( $post->ID, $image_size, 'post' );
		} else {
			$image_id = '';
			if ( ! empty( $series ) ) {
				$series    = is_object( $series ) ? $series->term_id : $series;
				$attribute = jnews_podcast_attribute( $series, array( 'fields' => array( 'image' ) ) );
				$image_id  = $attribute['image'];
			}

			$image = $this->get_thumbnail( $image_id, $image_size );
		}
		$thumbnail  = $image;
		$category   = '<div class="jeg_post_category">' . $this->get_primary_category( $post->ID ) . '</div>';
		$date       = "<div class='jeg_meta_date'><a href=" . jnews_get_respond_link( $post->ID ) . "><i class='fa fa-calendar' aria-hidden='true'></i> " . $this->format_date( $post->ID ) . '</a></div>';
		$duration   = $this->get_minute( $post->ID, true, true );
		$post_meta  = '<div class="jeg_post_meta">' . $date . $duration . '</div>';
		$media_menu = jnews_podcast_add_media_menu( $post->ID, 'episode_block' );
		$content    = '<div class="jeg_thumb">
		             	' . jnews_edit_post( $post->ID, 'left', 'podcast' ) . '
                        <a href="' . get_the_permalink( $post->ID ) . '">' . $thumbnail . '</a>
		             </div>
		             <div class="jeg_postblock_content">
			            ' . $category . '
		             	<h3 class="jeg_post_title">
		             		<a href="' . get_the_permalink( $post->ID ) . '">' . $post->post_title . '</a>
		            	 </h3>
		             	' . $post_meta . '
	                    ' . $media_menu . '
            		</div>';

		return '<article ' . jnews_post_class( 'jeg_post', $post->ID ) . '>
					<div class="box_wrap">
						' . $content . ' 
					</div>
				</article>';

	}

	/**
	 * get minute from podcast duration
	 *
	 * @param $post_id
	 *
	 * @return mixed|string
	 */
	public function get_minute( $post_id, $human_readable = false, $icon = false ) {
		$output = jnews_podcast_get_duration( $post_id, $human_readable, $icon );

		return $output;
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
	 * @param $results
	 *
	 * @return string
	 */
	public function build_column_1_alt( $results ) {

		$size = count( $results );

		$first_block = '';
		for ( $i = 0; $i < $size; $i ++ ) {
			$first_block .= $this->render_block_type_1( $results[ $i ], 'jnews-350x350' );
		}

		return $first_block;
	}
}
