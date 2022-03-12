<?php
/**
 * @see \JNews\Util\ValidateLicense::is_license_validated
 * @since 8.0.0
 */
if ( function_exists( 'jnews_is_active' ) && jnews_is_active()->is_license_validated() ) {
	return array(
		'id'       => 'jnews_food_recipe',
		'types'    => array( 'post' ),
		'title'    => 'JNews : Food Recipe Setting',
		'priority' => 'high',
		'mode'     => WPALCHEMY_MODE_EXTRACT,
		'template' => array(

			array(
				'type'   => 'tab',
				'name'   => 'food_recipe_setting',
				'title'  => esc_html__( 'Food Recipe Setting', 'jnews-food-recipe' ),
				'fields' => array(

					array(
						'type'        => 'toggle',
						'name'        => 'enable_food_recipe',
						'label'       => esc_html__( 'Enable Food Recipe', 'jnews-food-recipe' ),
						'description' => esc_html__( 'Check this option to enable food recipe on this post.', 'jnews-food-recipe' ),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_title',
						'label'           => esc_html__( 'Food Recipe Title', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert title of this food recipe.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textarea',
						'name'            => 'food_recipe_description',
						'label'           => esc_html__( 'Food Recipe Description', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert description of this food recipe. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_serve',
						'label'           => esc_html__( 'Serving Size', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Serving size of this food recipe. Ex : 5 People, 3 Bowls', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_time',
						'label'           => esc_html__( 'Cooking Time', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Cooking time of this food recipe in minute.', 'jnews-food-recipe' ),
						'validation'      => 'numeric',
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_prep',
						'label'           => esc_html__( 'Prepare Time', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Preparing time of this food recipe in minute. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'validation'      => 'numeric',
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_level',
						'label'           => esc_html__( 'Cooking Level', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Cooking level of this food recipe.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_keywords',
						'label'           => esc_html__( 'Food Recipe Keywords', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert some keywords for this food recipe. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_category',
						'label'           => esc_html__( 'Food Recipe Category', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert category for this food recipe. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_cuisine',
						'label'           => esc_html__( 'Food Recipe Cuisine', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert recipe cuisine for this food recipe. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_yield',
						'label'           => esc_html__( 'Food Recipe Yield', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert recipe cuisine for this food recipe. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'textbox',
						'name'            => 'food_recipe_calories',
						'label'           => esc_html__( 'Food Recipe Calories', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Insert recipe cuisine for this food recipe. Please note, this value will be used for Google Schema and not show up on frontpage.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					array(
						'type'            => 'toggle',
						'name'            => 'enable_print_recipe',
						'label'           => esc_html__( 'Enable Print Recipe', 'jnews-food-recipe' ),
						'description'     => esc_html__( 'Check this option to enable print food recipe.', 'jnews-food-recipe' ),
						'active_callback' => array(
							array(
								'field'    => 'enable_food_recipe',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
				),
			),

			array(
				'type'   => 'tab',
				'name'   => 'food_recipe_ingredient',
				'title'  => esc_html__( 'Food Recipe Ingredients', 'jnews-food-recipe' ),
				'fields' => array(

					array(
						'type'      => 'group',
						'repeating' => true,
						'sortable'  => true,
						'name'      => 'ingredient',
						'title'     => esc_html__( 'Ingredient of Food Recipe', 'jnews-food-recipe' ),
						'fields'    => array(
							array(
								'type'        => 'textbox',
								'name'        => 'item',
								'label'       => esc_html__( 'Ingredient Item', 'jnews-food_recipe' ),
								'description' => esc_html__( 'Insert ingredient item.', 'jnews-food_recipe' ),
							),
						),
					),

				),
			),

			array(
				'type'   => 'tab',
				'name'   => 'food_recipe_instruction',
				'title'  => esc_html__( 'Food Recipe Instructions', 'jnews-food-recipe' ),
				'fields' => array(

					array(
						'type'        => 'wpeditor',
						'name'        => 'instruction',
						'label'       => esc_html__( 'Food Recipe Instruction', 'jnews-food-recipe' ),
						'description' => esc_html__( 'Insert the instruction of food recipe.', 'jnews-food-recipe' ),
					),

				),
			),

		),
	);
} else {
	return array(
		'id'       => 'jnews_food_recipe',
		'types'    => array( 'post' ),
		'title'    => 'JNews : Food Recipe Setting',
		'priority' => 'high',
		'mode'     => WPALCHEMY_MODE_EXTRACT,
		'template' => array(
			array(
				'type'   => 'tab',
				'name'   => 'food_recipe_setting',
				'title'  => esc_html__( 'Food Recipe Setting', 'jnews-food-recipe' ),
				'fields' => array(),
			),
			array(
				'type'   => 'tab',
				'name'   => 'food_recipe_ingredient',
				'title'  => esc_html__( 'Food Recipe Ingredients', 'jnews-food-recipe' ),
				'fields' => array(),
			),
			array(
				'type'   => 'tab',
				'name'   => 'food_recipe_instruction',
				'title'  => esc_html__( 'Food Recipe Instructions', 'jnews-food-recipe' ),
				'fields' => array(),
			),
		),
	);
}
