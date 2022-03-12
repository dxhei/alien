<?php
/**
 * @author : Jegtheme
 */

use \JNEWS_PODCAST\Module\Element\Podcast\Podcast_View_Abstract;

/**
 * Class JNews_Podcast_Blockpodcast3_View
 */
class JNews_Podcast_Blockpodcast3_View extends Podcast_View_Abstract {

	/**
	 * @var string
	 */
	private $name = 'podcast_3';

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
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}
}
