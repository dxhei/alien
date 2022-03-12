<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast_Element_View_Abstract;
use JNEWS_PODCAST\Module\Module_Query;

/**
 * Class JNews_Podcast_Category_View
 */
class JNews_Podcast_Category_View extends Podcast_Element_View_Abstract {

	private $name = 'podcast_category';

	/**
	 * @param $attr
	 * @param $column_class
	 *
	 * @return string
	 */
	public function render_output( $attr, $column_class ) {
		if ( ! isset( $attr['pagination_mode'] ) ) {
			$attr['pagination_mode'] = 'disable';
			$this->set_attribute( $attr );
		}

		return parent::render_output( $attr, $column_class );
	}

	/**
	 * @param $result
	 * @param $column_class
	 * @param null         $attr
	 *
	 * @return string
	 */
	public function render_column( $result, $column_class, $attr = null ) {
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
		$first_block = '';
		foreach ( $results as $result ) {
			$first_block .= $this->render_block_type_1( $result, 'jnews-350x350', $attr );
		}

		return "<div class=\"jeg_posts_wrap\">
					<div class=\"jeg_posts jeg_load_more_flag\"> 
						{$first_block}
					</div>
				</div>";
	}

	/**
	 * @param WP_Term    $category
	 * @param $image_size
	 * @param null       $attr
	 *
	 * @return string
	 */
	public function render_block_type_1( $category, $image_size, $attr = null ) {
		$series_count    = '';
		$category_image  = '';
		$category_images = get_option( 'jnews_category_term_image', array() );
		if ( isset( $category_images[ $category->term_id ] ) ) {
			$category_image = $category_images[ $category->term_id ];
		}
		$thumbnail = $this->get_thumbnail( $category_image, $image_size );

		$podcast_list = Module_Query::get_podcast_by_category( $category->term_id );

		$podcast_attr = jnews_podcast_attribute( $podcast_list, array( 'fields' => array( 'count_series' ) ) );
		if ( isset( $podcast_attr['count_series'] ) ) {
			$series_count = "<div class=\"jeg_series_count\">{$podcast_attr['count_series']} <span>" . esc_attr__( 'Podcasts', 'jnews-podcast' ) . '</span></div>';
		}
		$post_meta = "<div class=\"jeg_post_meta\">{$series_count}</div>";
		$content   = '<div class="jeg_thumb">
		             	' . jnews_edit_post( $category->term_id, 'left', 'category' ) . '
                        <a href="' . jnews_podcast_get_category_link( $category->term_id ) . '">' . $thumbnail . '</a>
		             </div>
		             <div class="jeg_postblock_content">
		             	<h3 class="jeg_post_title">
		             		<a href="' . jnews_podcast_get_category_link( $category->term_id ) . '">' . $category->name . '</a>
		            	 </h3>
		             	' . $post_meta . '
            		</div>';

		return '<article class="jeg_post jeg_pl_md_box category-' . $category->slug . '">
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
		foreach ( $results as $result ) {
			$first_block .= $this->render_block_type_1( $result, 'jnews-350x350', $attr );
		}

		return $first_block;
	}

	/**
	 * @return mixed|string
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
		$args = array( 'hide_empty' => false );

		if ( ! empty( $attr['include_category'] ) ) {
			$args['include'] = $attr['include_category'];
		}
		if ( ! empty( $attr['number_category'] ) ) {

			$args['number'] = is_array( $attr['number_category'] ) ? $attr['number_category']['size'] : $attr['number_category'];
		}
		if ( ! empty( $attr['category_offset'] ) ) {
			$args['offset'] = $attr['category_offset'];
		}

		return array(
			'result'     => get_categories( $args ),
			'next'       => false,
			'prev'       => false,
			'total_page' => 0,
		);
	}
}
