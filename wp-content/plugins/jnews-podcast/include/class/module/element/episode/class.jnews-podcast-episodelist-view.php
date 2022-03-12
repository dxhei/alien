<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Module\Element\Podcast_Element_View_Abstract;
use JNEWS_PODCAST\Module\Module_Query;

/**
 * Class JNews_Podcast_Episodelist_View
 */
class JNews_Podcast_Episodelist_View extends Podcast_Element_View_Abstract {

	/**
	 * @var string
	 */
	private $name = 'episode_list';

	/**
	 * @param $result
	 * @param $column_class
	 * @param null         $attr
	 *
	 * @return string
	 */
	public function render_column( $result, $column_class, $attr = null ) {
		if ( null === $attr ) {
			$attr = $this->attribute;
		}
		switch ( $column_class ) {
			case 'jeg_col_1o3':
			case 'jeg_col_3o3':
			case 'jeg_col_2o3':
			default:
				$content = $this->build_column_1( $result, $attr );
				break;
		}

		return $content;
	}

	/**
	 * @param $results
	 * @param null    $attr
	 *
	 * @return string
	 */
	public function build_column_1( $results, $attr = null ) {
		$first_block = $this->render_block_type_1( $attr, 'jnews-350x350' );
		if ( isset( $attr['disable_podcast_detail'] ) && $attr['disable_podcast_detail'] ) {
			$first_block = '';
		}

		$second_block = '';
		for ( $i = 0, $i_max = count( $results ); $i < $i_max; $i ++ ) {
			$second_block .= $this->render_block_type_2( $results[ $i ] );
		}

		return "<div class=\"jeg_posts\">
					{$first_block}
					<div class=\"jeg_postsmall jeg_load_more_flag\">
						{$second_block}
					</div>
				</div>";
	}

	/**
	 * @param $attr
	 * @param $image_size
	 *
	 * @return string
	 */
	public function render_block_type_1( $attr, $image_size ) {
		$result = '';
		if ( isset( $attr['include_podcast_episode'] ) ) {
			$podcast       = jnews_get_series(
				array(
					'term_taxonomy_id' => $attr['include_podcast_episode'],
				)
			);
			$excerpt       = $this->get_excerpt( $podcast[0], 'podcast' );
			$attribute     = jnews_podcast_attribute( $podcast[0]->term_id, array( 'fields' => array( 'image' ) ) );
			$count         = $podcast[0]->count;
			$episode_count = 0 !== $count ? "<span class=\"jeg_episode_count\">{$count} " . jnews_return_translation( 'Episode', 'jnews-podcast', 'episode' ) . '</span>' : '';
			$thumbnail     = $this->get_thumbnail( $attribute['image'], $image_size );
			$post_meta     = "<div class=\"jeg_post_meta\">{$episode_count}</div>";
			$media_menu    = jnews_podcast_add_media_menu( $podcast[0]->term_id, 'podcast_subscribe' );
			$content       =
					'<div class="jeg_block_heading">
						<div class="jeg_thumb">
							' . jnews_edit_post( $podcast[0]->term_id, 'left', 'podcast' ) . '
							<a href="' . get_term_link( $podcast[0]->term_id ) . '">' . $thumbnail . '</a>
						</div>
						<div class="jeg_postblock_content">
							<h3 class="jeg_post_title">
								<a href="' . get_term_link( $podcast[0]->term_id ) . '">' . $podcast[0]->name . '</a>
							</h3>
							' . $post_meta . '
							' . $media_menu . '
						</div>
					 </div>
		             <div class="jeg_postblock_content">
		             	<div class="jeg_post_excerpt">
	                        <p>' . $excerpt . '</p>
	                    </div>
            		</div>';

			$result = '<article class="jeg_post series-' . $podcast[0]->slug . '">
                        <div class="box_wrap">
                            ' . $content . ' 
                        </div>
                    </article>';
		}

		return $result;
	}

	/**
	 * @param $post
	 *
	 * @return string
	 */
	public function render_block_type_2( $post ) {
		$media_menu   = jnews_podcast_add_media_menu( $post->ID, 'episode' );
		$podcast_meta = jnews_podcast_get_duration( $post->ID, true );
		$date         = "<div class='jeg_meta_date'><a href=" . jnews_get_respond_link( $post->ID ) . '>' . $this->format_date( $post->ID ) . '</a></div>';
		$content      = '<div class="jeg_postblock_content">
					' . $media_menu . '
                    <h3 class="jeg_post_title">
                        <a href="' . get_the_permalink( $post ) . '">' . get_the_title( $post ) . '</a>
                    </h3>
                   <div class="jeg_post_meta">
                   	' . $date . '
                  	' . $podcast_meta . '
					</div>
                </div>';

		return '<article ' . jnews_post_class( 'jeg_post jeg_pl_xs_4', $post->ID ) . '>
					' . $content . '
				</article>';
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
		}

		return $content;
	}

	/**
	 * @param $results
	 *
	 * @return string
	 */
	public function build_column_1_alt( $results ) {
		$first_block = '';
		for ( $i = 0, $i_max = count( $results ); $i < $i_max; $i ++ ) {
			$first_block .= $this->render_block_type_2( $results[ $i ] );
		}

		return $first_block;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param $attr
	 *
	 * @return array|int|string|WP_Error
	 */
	protected function build_query( $attr ) {
		$attr['post_type'] = 'post';
		if ( ! isset( $attr['include_podcast_episode'] ) || '' === $attr['include_podcast_episode'] || 'none' === $attr['include_podcast_episode'] ) {
			$attr['podcast_base_on']         = 'random';
			$query_result                    = Module_Query::get_podcast_base_on( $attr );
			$attr['include_podcast_episode'] = $query_result['result'][0]->term_id;
			unset( $attr['podcast_base_on'] );
		}
		$this->set_attribute( $attr );

		return parent::build_query( $attr );
	}
}
