<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast\Podcast_View_Abstract;

/**
 * Class JNews_Podcast_Blockpodcast4_View
 */
class JNews_Podcast_Blockpodcast4_View extends Podcast_View_Abstract {

	private $name = 'podcast_4';

	/**
	 * @param $result
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_column( $result, $column_class ) {
		switch ( $column_class ) {
			case 'jeg_col_1o3':
			case 'jeg_col_3o3':
			case 'jeg_col_2o3':
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
		$first_block = '';
		for ( $i = 0, $i_max = count( $results ); $i < $i_max; $i ++ ) {
			$first_block .= $this->render_block_type_1( $results[ $i ], 'jnews-350x250', false );
		}

		return "<div class=\"jeg_posts_wrap\">
					<div class=\"jeg_posts jeg_load_more_flag\"> 
						{$first_block}
					</div>
				</div>";
	}

	/**
	 * @param WP_Term    $podcast
	 * @param $image_size
	 * @param bool       $show_category
	 *
	 * @return string
	 */
	public function render_block_type_1( $podcast, $image_size, $show_category = true ) {
		$excerpt       = $this->get_excerpt( $podcast, 'podcast' );
		$attribute     = jnews_podcast_attribute( $podcast->term_id, array( 'fields' => array( 'image', 'author' ) ) );
		$thumbnail     = $this->get_thumbnail( $attribute['image'], $image_size );
		$author_meta   = $this->get_author_meta( $attribute );
		$episode_count = jnews_get_series( array( 'term_taxonomy_id' => $podcast->term_id ) );
		$episode_count = is_array( $episode_count ) && ! empty( $episode_count ) ? $episode_count[0]->count : 0;
		$episode_count = "<div class=\"jeg_series_count\">{$episode_count} <span>" . jnews_return_translation( 'Episodes', 'jnews-podcast', 'episodes' ) . '</span></div>';
		$media_menu    = jnews_podcast_add_media_menu( $podcast->term_id );
		$post_meta     = "<div class=\"jeg_post_meta\">{$author_meta} {$episode_count}</div>";
		$content       = '<div class="jeg_thumb">
		             	' . jnews_edit_post( $podcast->term_id, 'left', 'podcast' ) . '
                        <a href="' . get_term_link( $podcast->term_id ) . '">' . $thumbnail . '</a>
		             </div>
		             <div class="jeg_postblock_content">
		             	<h3 class="jeg_post_title">
		             		<a href="' . get_term_link( $podcast->term_id ) . '">' . $podcast->name . '</a>
		            	 </h3>
		             	' . $post_meta . '
		             	<div class="jeg_post_excerpt">
	                        <p>' . $excerpt . '</p>
	                    </div>
	                    ' . $media_menu . '
            		</div>';

		return '<article class="jeg_post jeg_pl_md_1 series-' . $podcast->slug . '">
                        <div class="box_wrap">
                            ' . $content . ' 
                        </div>
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
			$first_block .= $this->render_block_type_1( $results[ $i ], 'jnews-350x250', false );
		}

		return $first_block;
	}

	/**
	 * @return mixed|string
	 */
	public function get_name() {
		return $this->name;
	}
}
