<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Episode\Episode_View_Abstract;

/**
 * Class JNews_Podcast_Episodedetail_View
 */
class JNews_Podcast_Episodedetail_View extends Episode_View_Abstract {

	/**
	 * @var string
	 */
	private $name = 'episode_detail';

	/**
	 * @param $results
	 *
	 * @return string
	 */
	public function build_column_1( $results ) {
		$first_block = '';
		for ( $i = 0, $i_max = count( $results ); $i < $i_max; $i ++ ) {
			$first_block .= $this->render_block_type_1( $results[ $i ], 'jnews-75x75' );
		}

		$enable_post_excerpt = isset( $this->attribute['enable_post_excerpt'] ) && 'true' === $this->attribute['enable_post_excerpt'] ? 'jeg_enable_post_excerpt' : '';

		return "<div class=\"jeg_posts\">
					<div class=\"jeg_postsmall jeg_load_more_flag {$enable_post_excerpt}\">
						{$first_block}
					</div>
				</div>";
	}

	/**
	 * @param $post
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
		$post_excerpt = '<div class="jeg_post_excerpt">
							<p>' . $this->get_excerpt( $post ) . '</p>
						</div>';
		$podcast_meta = $this->get_minute( $post->ID, true );
		$date         = "<div class='jeg_meta_date'><a href=" . jnews_get_respond_link( $post->ID ) . '>' . $this->format_date( $post->ID ) . '</a></div>';
		$content      = '<div class="jeg_postblock_content">
						<div class="jeg_thumb">
							' . jnews_edit_post( $post->ID ) . '
							' . jnews_podcast_add_media_menu( $post->ID, 'episode_overlay' ) . '
							<a href="' . get_the_permalink( $post ) . '">' . $image . '</a>
						</div>
						<div class="jeg_post_info">
							<h3 class="jeg_post_title"><a href="' . get_the_permalink( $post ) . '">' . get_the_title( $post ) . '</a></h3>
		                    <div class="jeg_post_meta">
			                    ' . $date . '
                  	            ' . $podcast_meta . '
							</div>
							' . ( isset( $this->attribute['enable_post_excerpt'] ) && 'true' === $this->attribute['enable_post_excerpt'] ? $post_excerpt : '' ) . '
						</div>
						' . jnews_podcast_add_media_menu( $post->ID, 'episode_overlay_more' ) . '
	                </div>';

		return '<article ' . jnews_post_class( 'jeg_post', $post->ID ) . '>
					' . $content . '
				</article>';
	}


	/**
	 * @param $results
	 *
	 * @return string
	 */
	public function build_column_1_alt( $results ) {
		$first_block = '';
		for ( $i = 0, $i_max = count( $results ); $i < $i_max; $i ++ ) {
			$first_block .= $this->render_block_type_1( $results[ $i ], 'jnews-75x75' );
		}

		return $first_block;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
}
