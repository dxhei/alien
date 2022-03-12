<?php
/**
 * @author : Jegtheme
 */

namespace JNEWS_PODCAST\Module\Element\Podcast;

use JNEWS_PODCAST\Module\Element\Podcast_Element_View_Abstract;

/**
 * Class Podcast_View_Abstract
 */
abstract class Podcast_View_Abstract extends Podcast_Element_View_Abstract {

	/**
	 * @param \WP_Term   $podcast
	 * @param $image_size
	 * @param bool       $show_category
	 *
	 * @return string
	 */
	public function render_block_type_1( $podcast, $image_size, $show_category = true ) {
		$count         = $podcast->count;
		$excerpt       = $this->get_excerpt( $podcast, 'podcast' );
		$attribute     = jnews_podcast_attribute( $podcast->term_id, array( 'fields' => array( 'category', 'image' ) ) );
		$thumbnail     = $this->get_thumbnail( $attribute['image'], $image_size );
		$category      = $attribute['category'] && $show_category ? '<div class="jeg_post_category"><a href="' . get_category_link( $attribute['category']->term_id ) . '" class="category-' . $attribute['category']->slug . '">' . $attribute['category']->name . '</a></div>' : '';
		$episode_count = 0 !== $count ? "<span class=\"jeg_episode_count\">{$count} " . jnews_return_translation( 'Episode', 'jnews-podcast', 'episode' ) . '</span>' : '';

		$post_meta  = "<div class=\"jeg_post_meta\">{$episode_count}</div>";
		$media_menu = jnews_podcast_add_media_menu( $podcast->term_id );
		$content    = '<div class="jeg_thumb">
		             	' . jnews_edit_post( $podcast->term_id, 'left', 'podcast' ) . '
                        <a href="' . get_term_link( $podcast->term_id ) . '">' . $thumbnail . '</a>
		             </div>
		             <div class="jeg_postblock_content">
			            ' . $category . '
		             	<h3 class="jeg_post_title">
		             		<a href="' . get_term_link( $podcast->term_id ) . '">' . $podcast->name . '</a>
		            	 </h3>
		             	' . $post_meta . '
		             	<div class="jeg_post_excerpt">
	                        <p>' . $excerpt . '</p>
	                    </div>
	                    ' . $media_menu . '
            		</div>';

		return '<article class="jeg_post series-' . $podcast->slug . '">
                        <div class="box_wrap">
                            ' . $content . ' 
                        </div>
                    </article>';
	}

	/**
	 * @param $attribute
	 *
	 * @return string
	 */
	public function get_author_meta( $attribute ) {
		$author_meta      = '';
		$most_user_upload = $attribute['author'];
		if ( $most_user_upload ) {
			$author_url  = get_author_posts_url( $most_user_upload );
			$author_name = get_the_author_meta( 'display_name', $most_user_upload );
			$author_meta = '<div class="jeg_meta_author"><span class="by">' . jnews_return_translation( 'by', 'jnews-podcast', 'by' ) . "</span> <a href=\"{$author_url}\">{$author_name}</a></div>";
		}

		return $author_meta;
	}
}
