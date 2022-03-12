<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Food_Recipe' ) ) {
	class JNews_Food_Recipe {

		/**
		 * @var JNews_Food_Recipe
		 */
		private static $instance;

		/**
		 * @return JNews_Food_Recipe
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Food_Recipe constructor
		 */
		private function __construct() {
			add_action( 'wp_print_styles', array( $this, 'load_assets' ) );
			add_filter( 'the_content', array( $this, 'food_recipe_content' ) );
			add_action( 'amp_post_template_css', array( $this, 'food_recipe_content_style' ) );
			add_action( 'wp_footer', array( $this, 'food_recipe_json_ld' ) );
		}

		/**
		 * Load plugin assest
		 */
		public function load_assets() {

			if ( is_singular( 'post' ) && $this->food_recipe_enable() ) {
				wp_enqueue_style( 'jnews-food-recipe', JNEWS_FOOD_RECIPE_URL . '/assets/css/plugin.css', null, JNEWS_FOOD_RECIPE_VERSION );

				wp_enqueue_script( 'jnews-print', JNEWS_FOOD_RECIPE_URL . '/assets/js/printthis.js', null, JNEWS_FOOD_RECIPE_VERSION, true );
				wp_enqueue_script( 'jnews-food-recipe', JNEWS_FOOD_RECIPE_URL . '/assets/js/plugin.js', null, JNEWS_FOOD_RECIPE_VERSION, true );
			}
		}

		/**
		 * Return food recipe content
		 *
		 * @param  string $content
		 *
		 * @return string
		 */
		public function food_recipe_content( $content ) {
			if ( $this->food_recipe_enable() ) {
				// if split post, we need to leave the post to it should be
				$split_post = vp_metabox( 'jnews_post_split.enable_post_split', false );

				if ( $split_post ) {
					return $content;
				}

				return $this->render_food_recipe_content( $content );
			}

			return $content;
		}

		/**
		 * Integration with split content
		 *
		 * @param  string $content
		 * @param  int    $index
		 * @param  int    $max_page
		 *
		 * @return string
		 */
		public function split_food_recipe( $content, $index, $max_page ) {
			if ( $this->food_recipe_enable() ) {
				if ( $index == ( $max_page - 1 ) ) {
					return $this->render_food_recipe_content( $content );
				}
			}

			return $content;
		}

		/**
		 * Render food recipe content
		 *
		 * @param  string $content
		 *
		 * @return string
		 */
		public function render_food_recipe_content( $content ) {
			$data = vp_metabox( 'jnews_food_recipe.enable_food_recipe', false );

			$output = '<div id="jeg_food_recipe" class="jeg_food_recipe_wrap">
                            ' . $this->food_recipe_title() . '
                            ' . $this->food_recipe_ingredient() . '
                            ' . $this->food_recipe_instruction() . '
                        </div>';

			$content = $content . $output;

			return $content;
		}

		/**
		 * Food recipe title
		 *
		 * @return string
		 */
		public function food_recipe_title() {
			$title  = vp_metabox( 'jnews_food_recipe.food_recipe_title' );
			$output = '';

			if ( ! empty( $title ) ) {
				$output =
					'<div class="jeg_food_recipe_title">
                        <h3>' . esc_html( $title ) . '</h3>
                        ' . $this->render_food_recipe_meta() . '
                        ' . $this->render_food_recipe_print() . '
                    </div>';
			}

			return $output;
		}

		/**
		 * Food recipe ingredient
		 *
		 * @return string
		 */
		public function food_recipe_ingredient() {
			$ingredients = vp_metabox( 'jnews_food_recipe.ingredient' );
			$output      = '';

			if ( ! empty( $ingredients ) ) {
				foreach ( $ingredients as $ingredient ) {
					if ( ! empty( $ingredient['item'] ) ) {
						$output .= '<li><i class="jeg_checkmark"></i> ' . $ingredient['item'] . '</li>';
					}
				}
			}

			if ( ! empty( $output ) ) {
				$output =
					'<div class="jeg_food_recipe_ingredient">
                        <h4>' . jnews_return_translation( 'Ingredients', 'jnews-food-recipe', 'ingredients' ) . "</h4>
                        <ul>{$output}</ul>
                    </div>";
			}

			return $output;
		}

		/**
		 * Food recipe instruction
		 *
		 * @return string
		 */
		public function food_recipe_instruction() {
			$instruction = vp_metabox( 'jnews_food_recipe.instruction' );
			$output      = '';

			if ( ! empty( $instruction ) ) {
				$content = $this->format_text( $instruction );
				$output  =
					'<div class="jeg_food_recipe_instruction">
                        <h4>' . jnews_return_translation( 'Instructions', 'jnews-food-recipe', 'instructions' ) . '</h4>
                        ' . $content . '
                    </div>';
			}

			return $output;
		}

		/**
		 * Formating text
		 *
		 * @param  string $text
		 *
		 * @return string
		 */
		public function format_text( $text ) {
			$formats = array( 'wptexturize', 'convert_smilies', 'wpautop', 'shortcode_unautop', 'prepend_attachment', 'wp_filter_content_tags' );

			foreach ( $formats as $format ) {
				$text = $format( $text );
			}

			return $text;
		}

		/**
		 * Checking food recipe enable | disable
		 *
		 * @return boolean
		 */
		public function food_recipe_enable() {
			return vp_metabox( 'jnews_food_recipe.enable_food_recipe', false );
		}

		/**
		 * Render food recipe meta content
		 *
		 * @return string
		 */
		public function render_food_recipe_meta() {
			$output = '';

			// serving size
			$serving_size = vp_metabox( 'jnews_food_recipe.food_recipe_serve', false );

			if ( ! empty( $serving_size ) ) {
				$output .=
					'<div class="meta_serve">
                        <i class="fa fa-cutlery"></i>
                        <span class="meta_text">' . jnews_return_translation( 'Serves:', 'jnews-food-recipe', 'serves' ) . '</span>
                        <span>' . esc_html( $serving_size ) . '</span>
                    </div>';
			}

			// cooking time
			$cooking_time = vp_metabox( 'jnews_food_recipe.food_recipe_time', false );

			if ( ! empty( $cooking_time ) ) {
				$output .=
					'<div class="meta_time">
                        <i class="fa fa-clock-o"></i>
                        <span class="meta_text">' . sprintf( jnews_return_translation( 'Cooking time: %s minutes', 'jnews-food-recipe', 'cook_time' ), esc_html( $cooking_time ) ) . '</span>
                    </div>';
			}

			// cooking level
			$cooking_level = vp_metabox( 'jnews_food_recipe.food_recipe_level', false );

			if ( ! empty( $cooking_level ) ) {
				$output .=
					'<div class="meta_level">
                        <i class="fa fa-star"></i>
                        <span class="meta_text">' . jnews_return_translation( 'Level:', 'jnews-food-recipe', 'level' ) . '</span>
                        <span>' . esc_html( $cooking_level ) . '</span>
                    </div>';
			}

			if ( ! empty( $output ) ) {
				$output =
					"<div class=\"jeg_food_recipe_meta\">
                        {$output}
                    </div>";
			}

			return $output;
		}

		/**
		 * Render food recipe print button
		 *
		 * @return string
		 */
		public function render_food_recipe_print() {
			$print  = vp_metabox( 'jnews_food_recipe.enable_print_recipe' );
			$output = '';

			if ( ! empty( $print ) ) {
				$output =
					'<a href="#" class="jeg_food_recipe_print">
                        <i class="fa fa-print"></i>
                        <span>' . jnews_return_translation( 'Print Recipe', 'jnews-food-recipe', 'print_recipe' ) . '</span>
                    </a>';
			}

			return $output;
		}

		/**
		 * Render JSON LD for food recipe
		 */
		public function food_recipe_json_ld() {

			if ( ! jnews_get_option( 'enable_schema', 1 ) ) {
				return false;
			}

			$post_id = get_the_ID();

			if ( $this->food_recipe_enable() ) {
				$author_id       = get_post_field( 'post_author', $post_id );
				$recipe_cooktime = (int) vp_metabox( 'jnews_food_recipe.food_recipe_time', 0 );
				$recipe_preptime = (int) vp_metabox( 'jnews_food_recipe.food_recipe_prep', 0 );
				$ingredients     = vp_metabox( 'jnews_food_recipe.ingredient' );
				$recipe          = array(
					'@context'           => 'http://schema.org',
					'@type'              => 'Recipe',
					'dateCreated'        => get_the_date( 'Y-m-d H:i:s', $post_id ),
					'datePublished'      => get_the_date( 'Y-m-d H:i:s', $post_id ),
					'dateModified'       => get_post_modified_time( 'Y-m-d H:i:s', $post_id ),
					'name'               => vp_metabox( 'jnews_food_recipe.food_recipe_title', '' ),
					'description'        => vp_metabox( 'jnews_food_recipe.food_recipe_description', '' ),
					'recipeInstructions' => vp_metabox( 'jnews_food_recipe.instruction', '' ),
					'cookTime'           => 'PT' . $recipe_cooktime . 'M',
					'prepTime'           => 'PT' . $recipe_preptime . 'M',
					'totalTime'          => 'PT' . ( $recipe_cooktime + $recipe_preptime ) . 'M',
					'keywords'           => vp_metabox( 'jnews_food_recipe.food_recipe_keywords', '' ),
					'recipeCategory'     => vp_metabox( 'jnews_food_recipe.food_recipe_category', '' ),
					'recipeCuisine'      => vp_metabox( 'jnews_food_recipe.food_recipe_cuisine', '' ),
					'recipeYield'        => vp_metabox( 'jnews_food_recipe.food_recipe_yield', '' ),
					'author'             => array(
						'@type' => 'Person',
						'name'  => get_the_author_meta( 'display_name', $author_id ),
						'url'   => get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ),
					),
					'aggregateRating'    => array(
						'@type'       => 'AggregateRating',
						'ratingValue' => '5',
						'reviewCount' => rand( 1, 20 ),
					),
					'nutrition'          => array(
						'@type'       => 'NutritionInformation',
						'servingSize' => vp_metabox( 'jnews_food_recipe.food_recipe_serve', '' ),
						'calories'    => vp_metabox( 'jnews_food_recipe.food_recipe_calories', '0' ),
					),
				);

				if ( 'video' === vp_metabox( 'jnews_single_post.format', null, $post_id ) ) {
					$recipe['video'] = array(
						'@type'       => 'VideoObject',
						'name'        => vp_metabox( 'jnews_food_recipe.food_recipe_title', '' ),
						'description' => vp_metabox( 'jnews_food_recipe.food_recipe_description', '' ),
						'uploadDate'  => get_the_date( 'Y-m-d H:i:s', $post_id ),
						'contentURL'  => vp_metabox( 'jnews_single_post.video', null, $post_id ),
					);
				}

				if ( has_post_thumbnail() ) {
					$post_thumbnail_id = get_post_thumbnail_id( $post_id );
					$image_size        = wp_get_attachment_image_src( $post_thumbnail_id, $post_id );

					$recipe['image'] = $image_size[0];

					if ( isset( $recipe['video'] ) ) {
						$recipe['video']['thumbnailUrl'] = $image_size[0];
					}
				}

				if ( ! empty( $ingredients ) ) {
					foreach ( $ingredients as $item ) {
						$recipe['recipeIngredient'][] = ltrim( $item['item'] );
					}
				}

				if ( defined( 'JNEWS_REVIEW' ) ) {
					$rating_value                             = get_post_meta( $post_id, 'jnew_rating_mean', true );
					$recipe['aggregateRating']['ratingValue'] = round( (int) $rating_value / 2, 1 );
				}

				echo "<script type='application/ld+json'>" . wp_json_encode( $recipe ) . "</script>\n";
			}
		}

		/**
		 * AMP style
		 *
		 * @param  string $amp_template
		 */
		public function food_recipe_content_style( $amp_template ) {
			?>
			.jeg_food_recipe_wrap {
				border-top: 3px solid #eee;
				padding-top: 20px;
				margin: 40px 0;
			}
			.jeg_food_recipe_title {
				position: relative;
				margin-bottom: 30px;
			}
			.jeg_food_recipe_title::after {
				content: "";
				display: table;
				clear: both;
			}
			.jeg_food_recipe_title h3 {
				margin: 0 0 5px;
			}
			.jeg_food_recipe_meta {
				font-size: 13px;
				color: #a0a0a0;
				float: left;
			}
			.jeg_food_recipe_meta .fa {
				font-size: 14px;
				margin-right: 2px;
			}
			.jeg_food_recipe_meta > div {
				display: inline-block;
				margin-right: 15px;
			}
			.jeg_food_recipe_print {
				display: none;
			}
			.jeg_food_recipe_ingredient h4 {
				font-size: 18px;
				line-height: 1;
				font-weight: bolder;
				margin: 0 0 20px;
				text-transform: uppercase;
			}

			/* Ingredient */
			.jeg_food_recipe_ingredient {
				margin-bottom: 30px;
				padding: 20px;
				border: 1px solid #e0e0e0;
				-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			}
			.jeg_food_recipe_ingredient ul {
				list-style: none;
				margin: 0 -20px;
			}
			.jeg_food_recipe_ingredient li {
				padding: 10px 20px;
				margin: 0;
				font-size: 15px;
				line-height: 1.4;
				cursor: pointer;
				font-weight: bold;
			}
			.jeg_food_recipe_ingredient li:nth-child(odd) {
				background: #f5f5f5;
			}
			.jeg_food_recipe_ingredient li.active {
				font-weight: normal;
				text-decoration: line-through;
				font-style: italic;
				color: #a0a0a0;
			}
			.jeg_food_recipe_ingredient li .jeg_checkmark {
				display: inline-block;
				font: normal normal normal 14px/1 FontAwesome;
				margin-right: 7px;
				color: #a0a0a0;
			}
			.jeg_food_recipe_ingredient li .jeg_checkmark:before {
				content: "\f096";
			}
			.jeg_food_recipe_ingredient li.active .jeg_checkmark:before {
				content: "\f046";
			}
			.jeg_food_recipe_ingredient li.active .jeg_checkmark {
				color: #00a652;
			}

			/* Instruction */
			.jeg_food_recipe_instruction h4 {
				font-weight: bold;
				text-transform: uppercase;
			}
			.jeg_food_recipe_instruction li {
				margin-bottom: 15px;
			}

			/*** Responsive **/
			@media only screen and (max-width : 568px) {
				.jeg_food_recipe_meta {float: none;}
				.jeg_food_recipe_print {float: none; top: 0; margin-top: 20px; padding: 10px 16px;}
			}
			@media only screen and (max-width : 480px) {
				.jeg_food_recipe_title h3 {font-size: 20px;}
				.jeg_food_recipe_ingredient h4,.jeg_food_recipe_instruction h4 {font-size: 16px;}
			}
			<?php
		}

	}
}

