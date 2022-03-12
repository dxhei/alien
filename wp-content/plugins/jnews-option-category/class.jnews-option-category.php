<?php
/**
 * @author Jegtheme
 */

use JNews\Archive\Builder\OptionAbstract;

class OptionCategory extends OptionAbstract {

	protected $prefix = 'jnews_category_';

	protected $version;

	protected $patch = array(
		//post template
		'blog_style_header',
		'blog_template',
		'blog_custom',
		'blog_layout',
		'blog_enable_parallax',
		'blog_enable_fullscreen',
		'sidebar',
		'second_sidebar',
		'sticky_sidebar',
		'blog_element_header',
		'show_featured',
		'show_featured_image',
		'show_featured_gallery',
		'show_featured_video',
		'show_post_meta',
		'show_post_author',
		'show_post_author_image',
		'show_post_date',
		'post_date_format',
		'post_date_format_custom',
		'show_category',
		'comment',
		'reading_time',
		'reading_time_wpm',
		'zoom_button',
		'zoom_button_out_step',
		'zoom_button_in_step',
		'share_float_style',
		'share_position',
		'show_share_counter',
		'show_view_counter',
		'show_tag',
		'show_prev_next_post',
		'show_popup_post',
		'number_popup_post',
		'show_author_box',
		'show_reading_progress_bar',
		'show_reading_progress_bar_position',
		'show_reading_progress_bar_color',
		'blog_post_thumbnail_header',
		'post_thumbnail_size',
		'post_gallery_size',
		//category
		'page_layout',
		'sidebar',
		'second_sidebar',
		'header_style',
		'header_scheme',
		'title_bg_color',
		'title_bg_image',
		'show_hero',
		'hero_layout',
		'hero_style',
		'hero_margin',
		'hero_date',
		'hero_date_custom',
		'content_layout',
		'content_boxed',
		'content_boxed_shadow',
		'content_box_shadow',
		'content_excerpt',
		'content_date',
		'content_date_custom',
		'content_pagination',
		'content_pagination_limit',
		'content_pagination_align',
		'content_pagination_text',
		'content_pagination_page',
		//category builder
		'category_template',
		'number_post',
		'category_override_post',
		'category_override',
		'category_override_color',
		'category_text_color',
		'category_bg_color',
	);

	protected function setup_hook() {
		$taxonomy = 'category';
		if ( is_admin() ) {
			add_action( "{$taxonomy}_edit_form", array( $this, 'render_options' ) );
			add_action( "edit_{$taxonomy}", array( $this, 'save_category' ) );
		} else {
			add_action( 'pre_get_posts', array( $this, 'category_custom_get_posts' ) );
			add_action( 'jeg_after_inline_dynamic_css', array( $this, 'override_color' ) );
			add_filter( 'category_template', array( $this, 'get_category_template' ) );
			$this->override_global_category();
			$this->override_category_builder();
		    $this->override_global_post();
		}
	}

	public function category_custom_get_posts( $query ) {
		if ( is_category() && $query->is_main_query() ) {
			$term = get_queried_object_id();

			if ( $term && $this->is_overwritten( $term ) ) {
				if ( $this->get_value( 'page_layout', $term, false ) === 'custom-template' ) {
					$query->query_vars['posts_per_page'] = (int) $this->get_value( 'number_post', $term, false );
				}
			}
		}
	}

	public function get_category_template( $template ) {
		if ( is_category() ) {
			$term = get_queried_object_id();

			if ( $term && $this->is_overwritten( $term ) ) {
				$layout      = $this->get_value( 'page_layout', $term, false );
				$template_id = $this->get_value( 'category_template', $term, false );

				if ( $layout === 'custom-template' && $template_id ) {
					$template = JNEWS_THEME_DIR . '/fragment/archive/category.php';
				}
			}
		}

		return $template;
	}

	protected function override_category_builder() {
		$self = $this;
		$keys = array(
			'page_layout'       => 'page_layout',
			'category_template' => 'custom_template_id',
			'number_post'       => 'custom_template_number_post',
		);

		foreach ( $keys as $key => $label ) {
			add_filter(
				'theme_mod_' . $this->prefix . $label,
				function ( $value ) use ( $self, $key ) {

					if ( is_category() ) {
						$term = get_queried_object_id();

						if ( $term && $self->is_overwritten( $term ) ) {
							$new_option = get_option( $self->prefix . $key );

							if ( isset( $new_option[ $term ] ) ) {
								$value = $new_option[ $term ];
							}
						}
					}

					return $value;
				}
			);
		}
	}

	protected function override_global_post() {
        $self  = $this;
	    $items = array(
            'blog_style_header',
            'blog_template',
            'blog_custom',
            'blog_layout',
            'blog_enable_parallax',
            'blog_enable_fullscreen',
            'sidebar',
            'second_sidebar',
            'sticky_sidebar',
            'blog_element_header',
            'show_featured',
            'show_featured_image',
            'show_featured_gallery',
            'show_featured_video',
            'show_post_meta',
            'show_post_author',
            'show_post_author_image',
            'show_post_date',
            'post_date_format',
            'post_date_format_custom',
            'show_category',
            'comment',
            'reading_time',
            'reading_time_wpm',
            'zoom_button',
            'zoom_button_out_step',
            'zoom_button_in_step',
            'share_float_style',
            'share_position',
            'show_share_counter',
            'show_view_counter',
            'show_tag',
            'show_prev_next_post',
            'show_popup_post',
            'number_popup_post',
            'show_author_box',
            'show_reading_progress_bar',
            'show_reading_progress_bar_position',
            'show_reading_progress_bar_color',
            'blog_post_thumbnail_header',
            'post_thumbnail_size',
            'post_gallery_size',
        );

        foreach ( $items as $key ) {
            add_filter( 'theme_mod_' . 'jnews_single_' . $key, function ( $value ) use ( $self, $key ) {

                if ( is_single() ) {
                    $post_id = get_queried_object_id();
                    if ( $term = vp_metabox( 'jnews_primary_category.id', null, $post_id ) ) {
                        if ( $term && $self->is_post_override( $term ) ) {
                            $new_option = get_option( $this->prefix . 'jnews_single_' . $key );

                            if ( isset( $new_option[ $term ] ) ) {
                                $value = $new_option[ $term ];
                            }
                        }
                    }
                }

                return $value;
            } );
        }
    }

	protected function override_global_category() {
		$self  = $this;
		$items = array(
			'page_layout'              => 'page_layout',
			'sidebar'                  => 'sidebar',
			'second_sidebar'           => 'second_sidebar',
			'header_style'             => 'header',
			'header_scheme'            => 'header_style',
			'title_bg_color'           => 'header_bg_color',
			'title_bg_image'           => 'header_bg_image',
			'show_hero'                => 'hero_show',
			'hero_layout'              => 'hero',
			'hero_style'               => 'hero_style',
			'hero_margin'              => 'hero_margin',
			'hero_date'                => 'hero_date',
			'hero_date_custom'         => 'hero_date_custom',
			'content_layout'           => 'content',
			'content_boxed'            => 'boxed',
			'content_boxed_shadow'     => 'boxed_shadow',
			'content_box_shadow'       => 'box_shadow',
			'content_excerpt'          => 'content_excerpt',
			'content_date'             => 'content_date',
			'content_date_custom'      => 'content_date_custom',
			'content_pagination'       => 'content_pagination',
			'content_pagination_limit' => 'content_pagination_limit',
			'content_pagination_align' => 'content_pagination_align',
			'content_pagination_text'  => 'content_pagination_show_navtext',
			'content_pagination_page'  => 'content_pagination_show_pageinfo',
		);

		foreach ( $items as $key => $label ) {
			add_filter(
				$this->prefix . $label,
				function ( $value ) use ( $self, $key ) {
					if ( is_category() ) {
						$term = get_queried_object_id();

						if ( $term && $self->is_overwritten( $term ) ) {
							$new_option = get_option( $self->prefix . $key );

							if ( isset( $new_option[ $term ] ) ) {
								$value = $new_option[ $term ];
							}
						}
					}

					return $value;
				}
			);
		}
	}

	public function is_post_override( $term_id ) {
		$option = get_option( $this->prefix . 'category_override_post', array() );

		if ( isset( $option[$term_id] ) ) {
			return $option[$term_id];
		}

		return false;
	}

	public function is_overwritten( $term_id ) {
		$option = get_option( $this->prefix . 'category_override', array() );

		if ( isset( $option[ $term_id ] ) ) {
			return $option[ $term_id ];
		}

		return false;
	}

	public function override_color() {
		$style   = '';
		$options = get_option( $this->prefix . 'category_override_color', array() );

		foreach ( $options as $key => $option ) {
			if ( $option ) {
				$category = get_category( $key );

				if ( isset( $category->slug ) && $category->slug ) {
					$bg_color   = $this->get_value( 'category_bg_color', $key, false );
					$text_color = $this->get_value( 'category_text_color', $key, false );

					if ( $bg_color ) {
						$style .= ".jeg_heroblock .jeg_post_category a.category-{$category->slug},.jeg_thumb .jeg_post_category a.category-{$category->slug},.jeg_pl_lg_box .jeg_post_category a.category-{$category->slug},.jeg_pl_md_box .jeg_post_category a.category-{$category->slug},.jeg_postblock_carousel_2 .jeg_post_category a.category-{$category->slug},.jeg_slide_caption .jeg_post_category a.category-{$category->slug} { background-color:{$bg_color}; }";

						$style .= ".jeg_heroblock .jeg_post_category a.category-{$category->slug},.jeg_thumb .jeg_post_category a.category-{$category->slug},.jeg_pl_lg_box .jeg_post_category a.category-{$category->slug},.jeg_pl_md_box .jeg_post_category a.category-{$category->slug},.jeg_postblock_carousel_2 .jeg_post_category a.category-{$category->slug},.jeg_slide_caption .jeg_post_category a.category-{$category->slug} { border-color:{$bg_color}; }";
					}

					if ( $text_color ) {
						$style .= ".jeg_heroblock .jeg_post_category a.category-{$category->slug},.jeg_thumb .jeg_post_category a.category-{$category->slug},.jeg_pl_lg_box .jeg_post_category a.category-{$category->slug},.jeg_pl_md_box .jeg_post_category a.category-{$category->slug},.jeg_postblock_carousel_2 .jeg_post_category a.category-{$category->slug},.jeg_slide_caption .jeg_post_category a.category-{$category->slug} { color:{$text_color}; }";
					}
				}
			}
		}

		if ( $style ) {
			if ( is_customize_preview() ) {
				wp_add_inline_style( 'jeg-dynamic-style', $style );
			} else {
				?>
				<style id="jeg_extended_category_css" type="text/css" data-type="jeg_custom-css"><?php echo $style; ?></style>
				<?php
			}
		}
	}

	protected function get_id( $tag ) {
		if ( ! empty( $tag->term_id ) ) {
			return $tag->term_id;
		} else {
			return null;
		}
	}

	public function prepare_segments() {
		$segments = array();

		$segments[] = array(
			'id'   => 'override-category-setting',
			'name' => esc_html__( 'Category Setting', 'jnews-option-category' ),
		);

		return $segments;
	}

	public function save_category() {
		if ( isset( $_POST['taxonomy'] ) && sanitize_key( $_POST['taxonomy'] ) === 'category' ) {
			$options = $this->get_options();
			$this->do_save( $options, (int) sanitize_text_field( $_POST['tag_ID'] ) );
		}
	}

	protected function get_options() {
		$options           = array();
		$all_sidebar       = apply_filters( 'jnews_get_sidebar_widget', null );
		$content_layout    = apply_filters(
			'jnews_get_content_layout_option',
			array(
				'3'  => JNEWS_THEME_URL . '/assets/img/admin/content-3.png',
				'4'  => JNEWS_THEME_URL . '/assets/img/admin/content-4.png',
				'5'  => JNEWS_THEME_URL . '/assets/img/admin/content-5.png',
				'6'  => JNEWS_THEME_URL . '/assets/img/admin/content-6.png',
				'7'  => JNEWS_THEME_URL . '/assets/img/admin/content-7.png',
				'9'  => JNEWS_THEME_URL . '/assets/img/admin/content-9.png',
				'10' => JNEWS_THEME_URL . '/assets/img/admin/content-10.png',
				'11' => JNEWS_THEME_URL . '/assets/img/admin/content-11.png',
				'12' => JNEWS_THEME_URL . '/assets/img/admin/content-12.png',
				'14' => JNEWS_THEME_URL . '/assets/img/admin/content-14.png',
				'15' => JNEWS_THEME_URL . '/assets/img/admin/content-15.png',
				'18' => JNEWS_THEME_URL . '/assets/img/admin/content-18.png',
				'22' => JNEWS_THEME_URL . '/assets/img/admin/content-22.png',
				'23' => JNEWS_THEME_URL . '/assets/img/admin/content-23.png',
				'25' => JNEWS_THEME_URL . '/assets/img/admin/content-25.png',
				'26' => JNEWS_THEME_URL . '/assets/img/admin/content-26.png',
				'27' => JNEWS_THEME_URL . '/assets/img/admin/content-27.png',
				'32' => JNEWS_THEME_URL . '/assets/img/admin/content-32.png',
				'33' => JNEWS_THEME_URL . '/assets/img/admin/content-33.png',
				'34' => JNEWS_THEME_URL . '/assets/img/admin/content-34.png',
				'35' => JNEWS_THEME_URL . '/assets/img/admin/content-35.png',
				'36' => JNEWS_THEME_URL . '/assets/img/admin/content-36.png',
				'37' => JNEWS_THEME_URL . '/assets/img/admin/content-37.png',
				'38' => JNEWS_THEME_URL . '/assets/img/admin/content-38.png',
				'39' => JNEWS_THEME_URL . '/assets/img/admin/content-39.png',
			)
		);
		$category_override = array(
			'field'    => 'category_override',
			'operator' => '==',
			'value'    => true,
		);

		$custom_template = array(
			'field'    => 'page_layout',
			'operator' => '!=',
			'value'    => 'custom-template',
		);

		$category_override_color = array(
			'field'    => 'category_override_color',
			'operator' => '==',
			'value'    => true,
		);

		/**
		 * Override category color
		 */
		$options['category_override_color'] = array(
			'segment' => 'override-category-setting',
			'title'   => esc_html__( 'Override Category Color', 'jnews-option-category' ),
			'desc'    => esc_html__( 'Override category general color setting.', 'jnews-option-category' ),
			'type'    => 'checkbox',
			'default' => false,
		);

		$options['category_bg_color'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Background Color', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Main color for this category.', 'jnews-option-category' ),
			'default'    => '',
			'type'       => 'color',
			'dependency' => array(
				$category_override_color,
			),
		);

		$options['category_text_color'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Text Color', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose text color for this category.', 'jnews-option-category' ),
			'default'    => '',
			'type'       => 'color',
			'dependency' => array(
				$category_override_color,
			),
		);

		/**
		 * Override category setting
		 */
		$options['category_override'] = array(
			'segment' => 'override-category-setting',
			'title'   => esc_html__( 'Override Category Setting', 'jnews-option-category' ),
			'desc'    => esc_html__( 'Override category general setting.', 'jnews-option-category' ),
			'type'    => 'checkbox',
			'default' => false,
		);

		$options['page_layout'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Page Layout', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your page layout.', 'jnews-option-category' ),
			'default'    => 'right-sidebar',
			'type'       => 'radioimage',
			'options'    => array(
				'right-sidebar'        => JNEWS_THEME_URL . '/assets/img/admin/single-post-right-sidebar.png',
				'left-sidebar'         => JNEWS_THEME_URL . '/assets/img/admin/single-post-left-sidebar.png',
				'right-sidebar-narrow' => JNEWS_THEME_URL . '/assets/img/admin/single-post-wide-right-sidebar.png',
				'left-sidebar-narrow'  => JNEWS_THEME_URL . '/assets/img/admin/single-post-wide-left-sidebar.png',
				'double-sidebar'       => JNEWS_THEME_URL . '/assets/img/admin/single-post-double-sidebar.png',
				'double-right-sidebar' => JNEWS_THEME_URL . '/assets/img/admin/single-post-double-right.png',
				'no-sidebar'           => JNEWS_THEME_URL . '/assets/img/admin/single-post-no-sidebar.png',
				'custom-template'      => JNEWS_THEME_URL . '/assets/img/admin/single-post-custom.png',
			),
			'dependency' => array(
				$category_override,
			),
		);

		$options['category_template'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Template', 'jnews' ),
			'desc'       => esc_html__( 'Choose archive template that you want to use for this category.', 'jnews' ),
			'type'       => 'select',
			'options'    => jnews_get_all_custom_archive_template(),
			'dependency' => array(
				$category_override,
				array(
					'field'    => 'page_layout',
					'operator' => '==',
					'value'    => 'custom-template',
				),
			),
		);

		$options['number_post'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Number of Post', 'jnews' ),
			'desc'       => esc_html__( 'Set the number of post per page on category page.', 'jnews' ),
			'type'       => 'text',
			'default'    => '10',
			'dependency' => array(
				$category_override,
				array(
					'field'    => 'page_layout',
					'operator' => '==',
					'value'    => 'custom-template',
				),
			),
		);

		$options['sidebar'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Sidebar', 'jnews-option-category' ),
			'desc'       => wp_kses( __( 'Choose your category sidebar. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.', 'jnews-option-category' ), wp_kses_allowed_html() ),
			'type'       => 'select',
			'default'    => 'default-sidebar',
			'options'    => $all_sidebar,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'page_layout',
					'operator' => '!=',
					'value'    => 'no-sidebar',
				),
			),
		);

		$options['second_sidebar'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Second Category Sidebar', 'jnews-option-category' ),
			'desc'       => wp_kses( __( 'Choose your second sidebar for category page. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.', 'jnews-option-category' ), wp_kses_allowed_html() ),
			'type'       => 'select',
			'default'    => 'default-sidebar',
			'options'    => $all_sidebar,
			'dependency' => array(
				$category_override,
				array(
					'field'    => 'page_layout',
					'operator' => 'in',
					'value'    => array( 'double-sidebar', 'double-right-sidebar' ),
				),
			),
		);

		$options['second_sidebar'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Sticky Sidebar', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Enable sticky sidebar on this category page.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => true,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'page_layout',
					'operator' => '!=',
					'value'    => 'no-sidebar',
				),
			),
		);

		$options['header_style'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Header Style', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Category header: title and description type.', 'jnews-option-category' ),
			'default'    => '1',
			'type'       => 'radioimage',
			'options'    => array(
				'1' => JNEWS_THEME_URL . '/assets/img/admin/header-style-1.png',
				'2' => JNEWS_THEME_URL . '/assets/img/admin/header-style-2.png',
				'3' => JNEWS_THEME_URL . '/assets/img/admin/header-style-3.png',
				'4' => JNEWS_THEME_URL . '/assets/img/admin/header-style-4.png',
			),
			'dependency' => array(
				$category_override,
				$custom_template,
			),
		);

		$options['header_scheme'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Color Scheme', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose color for your category title background color.', 'jnews-option-category' ),
			'default'    => 'dark',
			'type'       => 'select',
			'options'    => array(
				'dark'   => esc_html__( 'Dark Style', 'jnews-option-category' ),
				'normal' => esc_html__( 'Normal Style (Light)', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'header_style',
					'operator' => 'in',
					'value'    => array( '3', '4' ),
				),
			),
		);

		$options['title_bg_color'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Title Background Color', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose color for your category title background color.', 'jnews-option-category' ),
			'default'    => '#f5f5f5',
			'type'       => 'color',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'header_style',
					'operator' => 'in',
					'value'    => array( '3', '4' ),
				),
			),
		);

		$options['title_bg_image'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Title Background Image', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose or upload image for your category background.', 'jnews-option-category' ),
			'default'    => '',
			'type'       => 'image',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'header_style',
					'operator' => 'in',
					'value'    => array( '3', '4' ),
				),
			),
		);

		/**
		 * Override category hero
		 */
		$options['show_hero']   = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Show Category Hero Block', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Disable this option to hide category hero block.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
			),
		);
		$options['hero_layout'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Hero Header', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your category header (hero).', 'jnews-option-category' ),
			'default'    => '1',
			'type'       => 'radioimage',
			'options'    => array(
				'1'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-1.png',
				'2'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-2.png',
				'3'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-3.png',
				'4'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-4.png',
				'5'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-5.png',
				'6'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-6.png',
				'7'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-7.png',
				'8'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-8.png',
				'9'    => JNEWS_THEME_URL . '/assets/img/admin/hero-type-9.png',
				'10'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-10.png',
				'11'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-11.png',
				'12'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-12.png',
				'13'   => JNEWS_THEME_URL . '/assets/img/admin/hero-type-13.png',
				'skew' => JNEWS_THEME_URL . '/assets/img/admin/hero-type-skew.png',
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true,
				),
			),
		);
		$options['hero_style']  = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Header Style', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your category header (hero) style.', 'jnews-option-category' ),
			'default'    => 'jeg_hero_style_1',
			'type'       => 'radioimage',
			'options'    => array(
				'jeg_hero_style_1' => JNEWS_THEME_URL . '/assets/img/admin/hero-1.png',
				'jeg_hero_style_2' => JNEWS_THEME_URL . '/assets/img/admin/hero-2.png',
				'jeg_hero_style_3' => JNEWS_THEME_URL . '/assets/img/admin/hero-3.png',
				'jeg_hero_style_4' => JNEWS_THEME_URL . '/assets/img/admin/hero-4.png',
				'jeg_hero_style_5' => JNEWS_THEME_URL . '/assets/img/admin/hero-5.png',
				'jeg_hero_style_6' => JNEWS_THEME_URL . '/assets/img/admin/hero-6.png',
				'jeg_hero_style_7' => JNEWS_THEME_URL . '/assets/img/admin/hero-7.png',
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true,
				),
			),
		);
		$options['hero_margin'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Hero Margin', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Margin of each hero element.', 'jnews-option-category' ),
			'type'       => 'number',
			'options'    => array(
				'min'  => '0',
				'max'  => '30',
				'step' => '1',
			),
			'default'    => 10,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true,
				),
			),
		);
		$options['hero_date']   = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Choose Date Format', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose which date format you want to use for category.', 'jnews-option-category' ),
			'default'    => 'default',
			'type'       => 'select',
			'options'    => array(
				'ago'     => esc_html__( 'Relative Date/Time Format (ago)', 'jnews-option-category' ),
				'default' => esc_html__( 'WordPress Default Format', 'jnews-option-category' ),
				'custom'  => esc_html__( 'Custom Format', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options['hero_date_custom'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Custom Date Format', 'jnews-option-category' ),
			'desc'       => wp_kses(
				sprintf(
					__(
						"Please set custom date format for hero element. For more detail about this format, please refer to
                        <a href='%s' target='_blank'>Developer Codex</a>.",
						'jnews-option-category'
					),
					'https://developer.wordpress.org/reference/functions/current_time/'
				),
				wp_kses_allowed_html()
			),
			'default'    => 'Y/m/d',
			'type'       => 'text',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'show_hero',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'field'    => 'hero_date',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		);

		/**
		 * Override category content
		 */
		$options['content_layout'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Category Content Layout', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose your category content layout.', 'jnews-option-category' ),
			'default'    => '3',
			'type'       => 'radioimage',
			'options'    => $content_layout,
			'dependency' => array(
				$category_override,
				$custom_template,
			),
		);

		$options['content_boxed'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Enable Boxed', 'jnews-option-category' ),
			'desc'       => esc_html__( 'This option will turn the module into boxed.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_layout',
					'operator' => 'in',
					'value'    => array(
						'3',
						'4',
						'5',
						'6',
						'7',
						'9',
						'10',
						'14',
						'18',
						'22',
						'23',
						'25',
						'26',
						'27',
						'39',
					),
				),
			),
		);

		$options['content_boxed_shadow'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Enable Shadow', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Enable shadow on the module template.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_boxed',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'field'    => 'content_layout',
					'operator' => 'in',
					'value'    => array(
						'3',
						'4',
						'5',
						'6',
						'7',
						'9',
						'10',
						'14',
						'18',
						'22',
						'23',
						'25',
						'26',
						'27',
						'39',
					),
				),
			),
		);

		$options['content_box_shadow'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Enable Shadow', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Enable shadow on the module template.', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_layout',
					'operator' => 'in',
					'value'    => array( '37', '35', '33', '36', '32', '38' ),
				),
			),
		);

		$options['content_excerpt'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Excerpt Length', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Set the word length of excerpt on post.', 'jnews-option-category' ),
			'type'       => 'number',
			'options'    => array(
				'min'  => '0',
				'max'  => '200',
				'step' => '1',
			),
			'default'    => 20,
			'dependency' => array(
				$category_override,
				$custom_template,
			),
		);

		$options['content_date'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Choose Date Format', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose which date format you want to use for category content element.', 'jnews-option-category' ),
			'default'    => 'default',
			'type'       => 'select',
			'options'    => array(
				'ago'     => esc_html__( 'Relative Date/Time Format (ago)', 'jnews-option-category' ),
				'default' => esc_html__( 'WordPress Default Format', 'jnews-option-category' ),
				'custom'  => esc_html__( 'Custom Format', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
			),
		);

		$options['content_date_custom'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Custom Date Format', 'jnews-option-category' ),
			'desc'       => wp_kses(
				sprintf(
					__(
						"Please set custom date format for category content element. For more detail about this format, please refer to
                                        <a href='%s' target='_blank'>Developer Codex</a>.",
						'jnews-option-category'
					),
					'https://developer.wordpress.org/reference/functions/current_time/'
				),
				wp_kses_allowed_html()
			),
			'default'    => 'Y/m/d',
			'type'       => 'text',
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_date',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		);

		$options['content_pagination'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Choose Pagination Mode', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose which pagination mode that fit with your block.', 'jnews-option-category' ),
			'default'    => 'nav_1',
			'type'       => 'select',
			'options'    => array(
				'nav_1'      => esc_html__( 'Normal - Navigation 1', 'jnews-option-category' ),
				'nav_2'      => esc_html__( 'Normal - Navigation 2', 'jnews-option-category' ),
				'nav_3'      => esc_html__( 'Normal - Navigation 3', 'jnews-option-category' ),
				'nextprev'   => esc_html__( 'Ajax - Next Prev', 'jnews-option-category' ),
				'loadmore'   => esc_html__( 'Ajax - Load More', 'jnews-option-category' ),
				'scrollload' => esc_html__( 'Ajax - Auto Scroll Load', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
			),
		);

		$options['content_pagination_limit'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Auto Load Limit', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Limit of auto load when scrolling, set to zero to always load until end of content.', 'jnews-option-category' ),
			'type'       => 'number',
			'options'    => array(
				'min'  => '0',
				'max'  => '9999',
				'step' => '1',
			),
			'default'    => 0,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => '==',
					'value'    => 'scrollload',
				),
			),
		);

		$options['content_pagination_align'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Pagination Align', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Choose pagination alignment.', 'jnews-option-category' ),
			'default'    => 'center',
			'type'       => 'select',
			'options'    => array(
				'left'   => esc_html__( 'Left', 'jnews-option-category' ),
				'center' => esc_html__( 'Center', 'jnews-option-category' ),
			),
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => 'in',
					'value'    => array( 'nav_1', 'nav_2', 'nav_3' ),
				),
			),
		);

		$options['content_pagination_text'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Show Navigation Text', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Show navigation text (next, prev).', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => 'in',
					'value'    => array( 'nav_1', 'nav_2', 'nav_3' ),
				),
			),
		);

		$options['content_pagination_page'] = array(
			'segment'    => 'override-category-setting',
			'title'      => esc_html__( 'Show Page Info', 'jnews-option-category' ),
			'desc'       => esc_html__( 'Show page info text (Page x of y).', 'jnews-option-category' ),
			'type'       => 'checkbox',
			'default'    => false,
			'dependency' => array(
				$category_override,
				$custom_template,
				array(
					'field'    => 'content_pagination',
					'operator' => 'in',
					'value'    => array( 'nav_1', 'nav_2', 'nav_3' ),
				),
			),
		);

		/*
		 * NEW BARU
		 *
		 * FOR ` Custom Post template for category `
		 *
		 * Descriptions :
		 * id				=== [NOT FOUND]
		 * transport		=== [NOT FOUND]
		 * label			===	title
		 * description		=== desc
		 * postMessage		=== [NOT FOUND]
		 * type				=== type
		 * default			===	default
		 * multiple			=== multiple
		 * choices			===	options
		 * postmeta_refresh	=== [UNDEFINED] Untuk refresh frontend
		 * postvar			=== [UNDEFINED] Untuk redirect halaman
		 * wrapper_class	=== [UNDEFINED] Untuk menambahkan class
		 * active_callback	===	dependency
		 *
		 */

		$category_override_post = array(
			'field'		=> 'category_override_post',
			'operator'	=> '==',
			'value'		=> true
		);

		$postmeta_refresh = array(
			'selector'			=> '.jeg_meta_container',
			'render_callback'	=> function () {
				$single = \JNews\Single\SinglePost::getInstance();
				$single->render_post_meta();
			},
		);

		$top_share = array(
			'selector'			=> '.jeg_share_top_container',
			'render_callback'	=> function () {
				do_action( 'jnews_share_top_bar', get_the_ID() );
			},
		);

		$float_share = array(
			'selector'			=> '.jeg_share_float_container',
			'render_callback'	=> function () {
				do_action( 'jnews_share_float_bar', get_the_ID() );
			},
		);

		$bottom_share = array(
			'selector'			=> '.jeg_share_bottom_container',
			'render_callback'	=> function () {
				do_action( 'jnews_share_bottom_bar', get_the_ID() );
			},
		);

		$single_post_tag = array(
			'redirect'	=> 'single_post_tag',
			'refresh'	=> false
		);

		$single_post_callback = array(
			'field'		=> 'jnews_single_blog_template',
			'operator'	=> '!=',
			'value'		=> 'custom',
		);

		$postfeatured_callback = array(
			'field'		=> 'jnews_single_show_featured',
			'operator'	=> '==',
			'value'		=> true,
		);

		$options['category_override_post'] = array(
			'segment'	=> 'override-category-setting',
			'title'		=> esc_html__( 'Override Single Post Option', 'jnews' ),
			'type'		=> 'checkbox',
			'default'	=> false
		);

		$options['jnews_single_blog_style_header'] = array(
			'id'	=> 'jnews_single_blog_style_header',
			'type'	=> 'jnews-header',
			'label'	=> esc_html__( 'Single Blog Post Template', 'jnews' ),
		);

		$options['jnews_single_blog_template'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_blog_template',
			'transport'	=> 'postMessage',
			'default'	=> '1',
			'type'		=> 'radioimage',
			'title'		=> esc_html__( 'Single Blog Post Template', 'jnews' ),
			'desc'		=> esc_html__( 'Choose your single blog post template.', 'jnews' ),
			'options'	=> array(
				'1'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-1.png',
				'2'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-2.png',
				'3'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-3.png',
				'4'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-4.png',
				'5'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-5.png',
				'6'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-6.png',
				'7'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-7.png',
				'8'      	=> JNEWS_THEME_URL . '/assets/img/admin/single-post-8.png',
				'9' 		=> JNEWS_THEME_URL . '/assets/img/admin/single-post-9.png',
				'10'		=> JNEWS_THEME_URL . '/assets/img/admin/single-post-10.png',
				'custom'	=> JNEWS_THEME_URL . '/assets/img/admin/single-post-custom.png'
			),
			'dependency'	=> array(
				$category_override_post
			)
		);

		$options['jnews_single_blog_custom'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_blog_custom',
			'transport'	=> 'refresh',
			'default'	=> '',
			'type'		=> 'select',
			'title'		=> esc_html__( 'Custom Single Post Template', 'jnews' ),
			'desc'		=> wp_kses( sprintf( __( 'Create custom single post template from <a href="%s" target="_blank">here</a>', 'jnews' ), get_admin_url() . 'edit.php?post_type=custom-post-template' ), wp_kses_allowed_html() ),
			'multiple'	=> 1,
			'options'	=> call_user_func( function () {
				$post = get_posts( array(
					'posts_per_page'	=> - 1,
					'post_type'			=> 'custom-post-template',
				) );

				$footer   = array();
				$footer[] = esc_html__( 'Choose Post Template', 'jnews' );

				if ( $post ) {
					foreach ( $post as $value ) {
						$footer[ $value->ID ] = $value->post_title;
					}
				}

				return $footer;
			} ),
			'postvar' => array(
				array(
					'redirect'	=> 'single_post_tag',
					'refresh'	=> true
				)
			),
			'dependency' => array(
				$category_override_post,
				array(
					'field'		=> 'jnews_single_blog_template',
					'operator'	=> '==',
					'value'		=> 'custom'
				)
			)
		);

		$options['jnews_single_blog_layout'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_blog_layout',
			'transport'	=> 'postMessage',
			'default'	=> 'right-sidebar',
			'type'		=> 'radioimage',
			'title'		=> esc_html__( 'Single Blog Post Layout', 'jnews' ),
			'desc'		=> esc_html__( 'Choose your single blog post layout.', 'jnews' ),
			'options'	=> array(
				'right-sidebar'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-right-sidebar.png',
				'left-sidebar'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-left-sidebar.png',
				'right-sidebar-narrow'	=> JNEWS_THEME_URL . '/assets/img/admin/single-post-wide-right-sidebar.png',
				'left-sidebar-narrow'	=> JNEWS_THEME_URL . '/assets/img/admin/single-post-wide-left-sidebar.png',
				'double-sidebar'		=> JNEWS_THEME_URL . '/assets/img/admin/single-post-double-sidebar.png',
				'double-right-sidebar'	=> JNEWS_THEME_URL . '/assets/img/admin/single-post-double-right.png',
				'no-sidebar'			=> JNEWS_THEME_URL . '/assets/img/admin/single-post-no-sidebar.png',
				'no-sidebar-narrow'		=> JNEWS_THEME_URL . '/assets/img/admin/single-post-no-sidebar-narrow.png'
			),
			'postvar'	=> array(
				array(
					'redirect'	=> 'single_post_tag',
					'refresh'	=> true
				)
			),
			'dependency' => array(
				$category_override_post,
				$single_post_callback

			)
		);


		$options['jnews_single_blog_enable_parallax'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_blog_enable_parallax',
			'transport'	=> 'postMessage',
			'default'	=> true,
			'type'		=> 'checkbox',
			'title'		=> esc_html__( 'Parallax Effect', 'jnews' ),
			'desc'		=> esc_html__( 'Turn this option on if you want your featured image to have parallax effect.', 'jnews' ),
			'postvar'	=> array(
				array(
					'redirect'	=> 'single_post_tag',
					'refresh'	=> true
				)
			),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				array(
					'field'		=> 'jnews_single_blog_template',
					'operator'	=> 'contains',
					'value'	=> array( '4', '5' )
				)
			)
		);

		$options['jnews_single_blog_enable_fullscreen'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_blog_enable_fullscreen',
			'transport'	=> 'postMessage',
			'default'	=> true,
			'type'		=> 'checkbox',
			'title'		=> esc_html__( 'Fullscreen Featured Image', 'jnews' ),
			'desc'		=> esc_html__( 'Turn this option on if you want your post header to have fullscreen image featured.', 'jnews' ),
			'postvar'	=> array(
				array(
					'redirect'	=> 'single_post_tag',
					'refresh'	=> true
				)
			),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				array(
					'field'		=> 'jnews_single_blog_template',
					'operator'	=> 'contains',
					'value'		=> array( '4', '5' )
				)
			)
		);


		$all_sidebar = apply_filters( 'jnews_get_sidebar_widget', null );

		$options['jnews_single_sidebar'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_sidebar',
			'transport'			=> 'postMessage',
			'default'			=> 'sidebar',
			'type'				=> 'select',
			'title'				=> esc_html__( 'Single Post Sidebar', 'jnews' ),
			'desc'				=> wp_kses( __( "Choose your single post sidebar. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.", 'jnews' ), wp_kses_allowed_html() ),
			'multiple'			=> 1,
			'options'			=> $all_sidebar,
			'active_callback'	=> array(
				array(
					'setting'	=> 'jnews_single_blog_layout',
					'operator'	=> 'contains',
					'value'		=> array(
						'left-sidebar',
						'right-sidebar',
						'left-sidebar-narrow',
						'right-sidebar-narrow',
						'double-sidebar',
						'double-right-sidebar'
					),
				),
				$single_post_callback
			),
			'dependency'	=> array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_blog_layout',
					'operator' => 'contains',
					'value'    => array(
						'left-sidebar',
						'right-sidebar',
						'left-sidebar-narrow',
						'right-sidebar-narrow',
						'double-sidebar',
						'double-right-sidebar'
					),
				),
				$single_post_callback
			)
		);

		$options['jnews_single_second_sidebar'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_second_sidebar',
			'transport'	=> 'postMessage',
			'default'	=> 'default-sidebar',
			'type'		=> 'select',
			'title'		=> esc_html__( 'Second Single Post Sidebar', 'jnews' ),
			'desc'		=> wp_kses( __( "Choose your single post sidebar for the second sidebar. If you need another sidebar, you can create from <strong>WordPress Admin</strong> &raquo; <strong>Appearance</strong> &raquo; <strong>Widget</strong>.", 'jnews' ), wp_kses_allowed_html() ),
			'multiple'	=> 1,
			'options'	=> $all_sidebar,
			'postvar'	=> array(
				array(
					'redirect'	=> 'single_post_tag',
					'refresh'	=> true
				)
			),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				array(
					'field'		=> 'jnews_single_blog_layout',
					'operator'	=> 'contains',
					'value'		=> array( 'double-sidebar', 'double-right-sidebar' ),
				),
				$single_post_callback
			)
		);

		$options['jnews_single_sticky_sidebar'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_sticky_sidebar',
			'transport'	=> 'postMessage',
			'default'	=> true,
			'type'		=> 'checkbox',
			'title'		=> esc_html__( 'Single Post Sticky Sidebar', 'jnews' ),
			'desc'		=> esc_html__( 'Enable sticky sidebar on single post page.', 'jnews' ),
			'postvar'	=> array(
				array(
					'redirect'	=> 'single_post_tag',
					'refresh'	=> true
				)
			),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				array(
					'field'		=> 'jnews_single_blog_layout',
					'operator'	=> 'contains',
					'value'		=> array(
						'left-sidebar',
						'right-sidebar',
						'left-sidebar-narrow',
						'right-sidebar-narrow',
						'double-sidebar',
						'double-right-sidebar'
					)
				),
				$single_post_callback
			)
		);

		$options['jnews_single_blog_element_header'] = array(
			'segment'		=> 'override-category-setting',
			'id'			=> 'jnews_single_blog_element_header',
			'type'			=> 'jnews-header',
			'title'			=> esc_html__( 'Single Post Element', 'jnews' ),
			'dependency'	=> array(
				$category_override_post
			)
		);

		$options['jnews_single_show_featured'] = array(
			'segment'		=> 'override-category-setting',
			'id'			=> 'jnews_single_show_featured',
			'transport'		=> 'postMessage',
			'default'		=> true,
			'type'			=> 'checkbox',
			'title'			=> esc_html__( 'Show Featured Image,Gallery or Video', 'jnews' ),
			'desc'			=> esc_html__( 'Show featured image, gallery or video on single post.', 'jnews' ),
			'postvar'		=> array( $single_post_tag ),
			'dependency'	=> array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_show_featured_image'] = array(
			'segment'		=> 'override-category-setting',
			'id'			=> 'jnews_single_show_featured_image',
			'transport'		=> 'postMessage',
			'default'		=> true,
			'type'			=> 'checkbox',
			'title'			=> esc_html__( 'Show Featured Image', 'jnews' ),
			'desc'			=> esc_html__( 'Show featured image on single post.', 'jnews' ),
			'postvar'		=> array( $single_post_tag ),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				$single_post_callback,
				$postfeatured_callback
			)
		);

		$options['jnews_single_show_featured_gallery'] = array(
			'segment'		=> 'override-category-setting',
			'id'			=> 'jnews_single_show_featured_gallery',
			'transport'		=> 'postMessage',
			'default'		=> true,
			'type'			=> 'checkbox',
			'title'			=> esc_html__( 'Show Featured Gallery', 'jnews' ),
			'desc'			=> esc_html__( 'Show featured gallery on single post.', 'jnews' ),
			'postvar'		=> array( $single_post_tag ),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				$single_post_callback,
				$postfeatured_callback
			)
		);

		$options['jnews_single_show_featured_video'] = array(
			'segment'		=> 'override-category-setting',
			'id'			=> 'jnews_single_show_featured_video',
			'transport'		=> 'postMessage',
			'default'		=> true,
			'type'			=> 'checkbox',
			'title'			=> esc_html__( 'Show Featured Video', 'jnews' ),
			'desc'			=> esc_html__( 'Show featured video on single post.', 'jnews' ),
			'postvar'		=> array( $single_post_tag ),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				$single_post_callback,
				$postfeatured_callback
			)
		);

		$postmeta_callback = array(
			'field'		=> 'jnews_single_show_post_meta',
			'operator'	=> '==',
			'value'		=> true
		);

		$options['jnews_single_show_post_meta'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_show_post_meta',
			'transport'			=> 'postMessage',
			'default'			=> true,
			'type'				=> 'checkbox',
			'title'				=> esc_html__( 'Show Post Meta', 'jnews' ),
			'desc'				=> esc_html__( 'Show post meta on post header.', 'jnews' ),
			'partial_refresh'	=> array(
				'jnews_single_show_post_meta' => $postmeta_refresh
			),
			'postvar'			=> array( $single_post_tag ),
			'dependency'		=> array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_show_post_author'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_show_post_author',
			'transport'			=> 'postMessage',
			'default'			=> true,
			'type'				=> 'checkbox',
			'title'				=> esc_html__( 'Show Post Author', 'jnews' ),
			'desc'				=> esc_html__( 'Show post author on post meta container.', 'jnews' ),
			'partial_refresh'	=> array(
				'jnews_single_show_post_author' => $postmeta_refresh
			),
			'postvar'			=> array( $single_post_tag ),
			'wrapper_class'		=> array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_show_post_author_image'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_show_post_author_image',
			'transport'			=> 'postMessage',
			'default'			=> true,
			'type'				=> 'checkbox',
			'title'				=> esc_html__( 'Show Post Author Image', 'jnews' ),
			'desc'				=> esc_html__( 'Show post author image on post meta container.', 'jnews' ),
			'partial_refresh'	=> array(
				'jnews_single_show_post_author_image_1' => $postmeta_refresh,
			),
			'postvar'			=> array( $single_post_tag ),
			'wrapper_class'		=> array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				array(
					'field'		=> 'jnews_single_show_post_author',
					'operator'	=> '==',
					'value'		=> true,
				),
				$single_post_callback
			)
		);

		$options['jnews_single_show_post_date'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_show_post_date',
			'transport'			=> 'postMessage',
			'default'			=> true,
			'type'				=> 'checkbox',
			'title'				=> esc_html__( 'Show Post Date', 'jnews' ),
			'desc'				=> esc_html__( 'Show post date on post meta container.', 'jnews' ),
			'partial_refresh'	=> array(
				'jnews_single_show_post_date' => $postmeta_refresh
			),
			'postvar'			=> array( $single_post_tag ),
			'wrapper_class'		=> array( 'first_child' ),
			'dependency'		=> array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_post_date_format'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_post_date_format',
			'transport'			=> 'postMessage',
			'default'			=> 'default',
			'type'				=> 'select',
			'title'				=> esc_html__( 'Post Date Format', 'jnews' ),
			'desc'				=> esc_html__( 'Choose which date format you want to use for single post meta.', 'jnews' ),
			'options'			=> array(
				'ago'		=> esc_attr__( 'Relative Date/Time Format (ago)', 'jnews' ),
				'default'	=> esc_attr__( 'WordPress Default Format', 'jnews' ),
				'custom'	=> esc_attr__( 'Custom Format', 'jnews' ),
			),
			'partial_refresh'	=> array(
				'jnews_single_post_date_format' => $postmeta_refresh
			),
			'postvar'			=> array( $single_post_tag ),
			'wrapper_class'		=> array( 'first_child' ),
			'dependency'		=> array(
				$category_override_post,
				$postmeta_callback,
				array(
					'field'		=> 'jnews_single_show_post_date',
					'operator'	=> '==',
					'value'		=> true,
				),
				$single_post_callback
			)
		);

		$options['jnews_single_post_date_format_custom'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_post_date_format_custom',
			'transport'       => 'postMessage',
			'default'         => 'Y/m/d',
			'type'            => 'text',
			'title'           => esc_html__( 'Custom Date Format', 'jnews' ),
			'desc'     => wp_kses( sprintf( __( "Please set custom date format for single post meta. For more detail about this format, please refer to
										<a href='%s' target='_blank'>Developer Codex</a>.", "jnews" ), "https://developer.wordpress.org/reference/functions/current_time/" ),
				wp_kses_allowed_html() ),
			'partial_refresh' => array(
				'jnews_single_post_date_format_custom' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				array(
					'field'  => 'jnews_single_show_post_date',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'field'  => 'jnews_single_post_date_format',
					'operator' => '==',
					'value'    => 'custom',
				),
				$single_post_callback
			)
		);

		$options['jnews_single_show_category'] = array(
			'segment'		=> 'override-category-setting',
			'id'              => 'jnews_single_show_category',
			'transport'       => 'postMessage',
			'default'         => true,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Category', 'jnews' ),
			'desc'     => esc_html__( 'Show post category on post meta container.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_show_category' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_comment'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_comment',
			'transport'       => 'postMessage',
			'default'         => true,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Comment Button', 'jnews' ),
			'desc'     => esc_html__( 'Show comment button on post meta container.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_comment' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_reading_time'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_reading_time',
			'transport'       => 'postMessage',
			'default'         => false,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Reading Time', 'jnews' ),
			'desc'     => esc_html__( 'Show estimate reading time on post meta container.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_reading_time' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_reading_time_wpm'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_reading_time_wpm',
			'transport'       => 'postMessage',
			'default'         => '300',
			'type'            => 'text',
			'title'           => esc_html__( 'Words Per Minute', 'jnews' ),
			'desc'     => esc_html__( 'Set the average reading speed for the user.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_reading_time_wpm' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback,
				array(
					'field'  => 'jnews_single_reading_time',
					'operator' => '==',
					'value'    => true
				)
			)
		);

		$options['jnews_single_zoom_button'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_zoom_button',
			'transport'       => 'postMessage',
			'default'         => false,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Zoom Button', 'jnews' ),
			'desc'     => esc_html__( 'Show zoom button on the post meta container.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_post_zoom' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_zoom_button_out_step'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_zoom_button_out_step',
			'transport'       => 'postMessage',
			'default'         => 2,
			'type'            => 'slider',
			'title'           => esc_html__( 'Number of Zoom Out Step', 'jnews' ),
			'desc'     => esc_html__( 'Set the number of zoom out step to limit when zoom out button clicked.', 'jnews' ),
			'options'         => array(
				'min'  => '1',
				'max'  => '5',
				'step' => '1',
			),
			'partial_refresh' => array(
				'jnews_single_post_zoom' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_zoom_button',
					'operator' => '==',
					'value'    => true,
				),
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_zoom_button_in_step'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_zoom_button_in_step',
			'transport'       => 'postMessage',
			'default'         => 3,
			'type'            => 'slider',
			'title'           => esc_html__( 'Number of Zoom In Step', 'jnews' ),
			'desc'     => esc_html__( 'Set the number of zoom in step to limit when zoom in button clicked.', 'jnews' ),
			'options'         => array(
				'min'  => '1',
				'max'  => '5',
				'step' => '1',
			),
			'partial_refresh' => array(
				'jnews_single_post_zoom' => $postmeta_refresh
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_zoom_button',
					'operator' => '==',
					'value'    => true,
				),
				$postmeta_callback,
				$single_post_callback
			)
		);

		$options['jnews_single_share_position'] = array(
			'segment'	=> 'override-category-setting',
			'id'		=> 'jnews_single_share_position',
			'transport'	=> 'postMessage',
			'default'	=> 'top',
			'type'		=> 'select',
			'title'		=> esc_html__( 'Share Position', 'jnews' ),
			'desc'		=> esc_html__( 'Choose your share position.', 'jnews' ),
			'multiple'	=> 1,
			'options'	=> array(
				'top'			=> esc_attr__( 'Only Top', 'jnews' ),
				'float'			=> esc_attr__( 'Only Float', 'jnews' ),
				'bottom'		=> esc_attr__( 'Only Bottom', 'jnews' ),
				'topbottom'		=> esc_attr__( 'Top + Bottom', 'jnews' ),
				'floatbottom'	=> esc_attr__( 'Float + Bottom', 'jnews' ),
				'hide'			=> esc_attr__( 'Hide All', 'jnews' ),
			),
			'partial_refresh' => array(
				'jnews_single_share_position_top'		=> $top_share,
				'jnews_single_share_position_float'		=> $float_share,
				'jnews_single_share_position_bottom'	=> $bottom_share,
			),
			'output'          => array(
				array(
					'method'   => 'class-masking',
					'element'  => '.entry-content',
					'property' => array(
						'top'         => 'no-share',
						'float'       => 'with-share',
						'bottom'      => 'no-share',
						'topbottom'   => 'no-share',
						'floatbottom' => 'with-share',
						'hide'        => 'no-share',
					),
				),
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_share_float_style'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_share_float_style',
			'transport'       => 'postMessage',
			'default'         => 'share-monocrhome',
			'type'            => 'select',
			'title'           => esc_html__( 'Float Share Style', 'jnews' ),
			'desc'     => esc_html__( 'Choose your float share style.', 'jnews' ),
			'multiple'        => 1,
			'options'         => array(
				'share-normal'     => esc_attr__( 'Color', 'jnews' ),
				'share-monocrhome' => esc_attr__( 'Monochrome', 'jnews' ),
			),
			'output'          => array(
				array(
					'method'   => 'class-masking',
					'element'  => '.jeg_share_button',
					'property' => array(
						'share-normal'     => 'share-normal',
						'share-monocrhome' => 'share-monocrhome',
					),
				),
			),
			'postvar'		=> array( $single_post_tag ),
			'wrapper_class'	=> array( 'first_child' ),
			'dependency'	=> array(
				$category_override_post,
				array(
					'field'		=> 'jnews_single_share_position',
					'operator'	=> 'in',
					'value'		=> array( 'float', 'floatbottom' ),
				),
				$single_post_callback
			)
		);

		$options['jnews_single_show_share_counter'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_share_counter',
			'transport'       => 'postMessage',
			'default'         => true,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Share Counter', 'jnews' ),
			'desc'     => wp_kses( __( 'Show or hide share counter, share counter may be hidden depending on your setup on <strong>Share Position</strong> option above.', 'jnews' ), wp_kses_allowed_html() ),
			'partial_refresh' => array(
				'jnews_single_show_share_counter' => $top_share
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_share_position',
					'operator' => 'in',
					'value'    => array( 'top', 'topbottom' ),
				),
				$single_post_callback
			)
		);

		$options['jnews_single_show_view_counter'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_view_counter',
			'transport'       => 'postMessage',
			'default'         => true,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show View Counter', 'jnews' ),
			'desc'     => wp_kses( __( 'Show or hide view counter, view counter may be hidden depending on your setup on <strong>Share Position</strong> option above.', 'jnews' ), wp_kses_allowed_html() ),
			'partial_refresh' => array(
				'jnews_single_show_view_counter' => $top_share
			),
			'postvar'         => array( $single_post_tag ),
			'wrapper_class'   => array( 'first_child' ),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_share_position',
					'operator' => 'in',
					'value'    => array( 'top', 'topbottom' ),
				),
				$single_post_callback
			)
		);

		$options['jnews_single_show_tag'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_tag',
			'transport'       => 'postMessage',
			'default'         => true,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Post Tag', 'jnews' ),
			'desc'     => esc_html__( 'Show single post tag (below article).', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_show_tag' => array(
					'selector'        => '.jeg_post_tags',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						$single->post_tag_render();
					},
				)
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_show_prev_next_post'] = array(
			'segment'			=> 'override-category-setting',
			'id'				=> 'jnews_single_show_prev_next_post',
			'transport'			=> 'postMessage',
			'default'			=> true,
			'type'				=> 'checkbox',
			'title'				=> esc_html__( 'Show Prev / Next Post', 'jnews' ),
			'desc'				=> esc_html__( 'Show previous or next post navigation (below article).', 'jnews' ),
			'partial_refresh'	=> array(
				'jnews_single_show_prev_next_post'	=> array(
					'selector'        => '.jnews_prev_next_container',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						$single->prev_next_post();
					},
				)
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_show_popup_post'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_popup_post',
			'transport'       => 'postMessage',
			'default'         => true,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Popup Post', 'jnews' ),
			'desc'     => esc_html__( 'Show bottom right popup post widget.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_show_popup_post' => array(
					'selector'        => '.jnews_popup_post_container',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						$single->popup_post();
					}
				)
			),
			'postvar'		=> array( $single_post_tag ),
			'dependency'	=> array(
				$category_override_post
			)
		);

		$options['jnews_single_number_popup_post'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_number_popup_post',
			'transport'       => 'postMessage',
			'default'         => 1,
			'type'            => 'slider',
			'title'           => esc_html__( 'Number of Popup Post', 'jnews' ),
			'desc'     => esc_html__( 'Set the number of post to show when popup post appear.', 'jnews' ),
			'options'         => array(
				'min'  => '1',
				'max'  => '5',
				'step' => '1',
			),
			'partial_refresh' => array(
				'jnews_single_number_popup_post' => array(
					'selector'        => '.jnews_popup_post_container',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						$single->popup_post();
					},
				)
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_show_popup_post',
					'operator' => '==',
					'value'    => true
				)
			)
		);

		$options['jnews_single_show_author_box'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_author_box',
			'transport'       => 'postMessage',
			'default'         => false,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Author Box', 'jnews' ),
			'desc'     => esc_html__( 'Show author box (below article).', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_show_author_box' => array(
					'selector'        => '.jnews_author_box_container',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						$single->author_box();
					},
				)
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_show_reading_progress_bar'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_reading_progress_bar',
			'transport'       => 'postMessage',
			'default'         => false,
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Show Reading Progress Bar', 'jnews' ),
			'desc'     => esc_html__( 'Show reading progress bar on single post.', 'jnews' ),
			'partial_refresh' => array(
				'jnews_single_show_reading_progress_bar' => array(
					'selector'        => '.jeg_read_progress_wrapper',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						echo jnews_sanitize_by_pass( $single->build_reading_progress_bar() );
					},
				)
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_show_reading_progress_bar_position'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_show_reading_progress_bar_position',
			'transport'       => 'postMessage',
			'default'         => 'bottom',
			'type'            => 'checkbox',
			'title'           => esc_html__( 'Progress Bar Position', 'jnews' ),
			'desc'     => esc_html__( 'Choose the position of reading progress bar on single post.', 'jnews' ),
			'options'         => array(
				'top'    => esc_attr__( 'Top', 'jnews' ),
				'bottom' => esc_attr__( 'Bottom', 'jnews' ),
			),
			'partial_refresh' => array(
				'jnews_single_show_reading_progress_bar_position' => array(
					'selector'        => '.jeg_read_progress_wrapper',
					'render_callback' => function () {
						$single = \JNews\Single\SinglePost::getInstance();
						echo jnews_sanitize_by_pass( $single->build_reading_progress_bar() );
					},
				)
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_show_reading_progress_bar',
					'operator' => '==',
					'value'    => true,
				),
				$single_post_callback
			)
		);

		$options['jnews_single_show_reading_progress_bar_color'] = array(
			'segment'	=> 'override-category-setting',
			'id'            => 'jnews_single_show_reading_progress_bar_color',
			'transport'     => 'postMessage',
			'default'       => '#f70d28',
			'type'          => 'color',
			'disable_color' => true,
			'title'         => esc_html__('Progress Bar Color', 'jnews'),
			'desc'   => esc_html__('Set color for the progress bar.', 'jnews'),
			'output'     => array(
				array(
					'method'        => 'inject-style',
					'element'       => '.jeg_read_progress_wrapper .jeg_progress_container .progress-bar',
					'property'      => 'background-color',
				)
			),
			'dependency' => array(
				$category_override_post,
				array(
					'field'  => 'jnews_single_show_reading_progress_bar',
					'operator' => '==',
					'value'    => true,
				),
				$single_post_callback
			)
		);

		$options['jnews_single_blog_post_thumbnail_header'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_blog_post_thumbnail_header',
			'type'            => 'jnews-header',
			'title'           => esc_html__( 'Single Thumbnail Setting', 'jnews' ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_post_thumbnail_size'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_post_thumbnail_size',
			'transport'       => 'refresh',
			'default'         => 'crop-500',
			'type'            => 'select',
			'title'           => esc_html__( 'Post Thumbnail Size', 'jnews' ),
			'desc'     => esc_html__( 'Choose your post\'s single image thumbnail size. You can also override this behaviour on your single post editor.', 'jnews' ),
			'multiple'        => 1,
			'options'         => array(
				'no-crop'  => esc_attr__( 'No Crop', 'jnews' ),
				'crop-500' => esc_attr__( 'Crop 1/2 Dimension', 'jnews' ),
				'crop-715' => esc_attr__( 'Crop Default Dimension', 'jnews' ),
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		$options['jnews_single_post_gallery_size'] = array(
			'segment'	=> 'override-category-setting',
			'id'              => 'jnews_single_post_gallery_size',
			'transport'       => 'refresh',
			'default'         => 'crop-500',
			'type'            => 'select',
			'title'           => esc_html__( 'Post Gallery Thumbnail Size', 'jnews' ),
			'desc'     => esc_html__( 'Choose your gallery image thumbnail size. You can also override this behaviour on your single post editor.', 'jnews' ),
			'multiple'        => 1,
			'options'         => array(
				'crop-500' => esc_attr__( 'Crop 1/2 Dimension', 'jnews' ),
				'crop-715' => esc_attr__( 'Crop Default Dimension', 'jnews' ),
			),
			'postvar'         => array( $single_post_tag ),
			'dependency' => array(
				$category_override_post,
				$single_post_callback
			)
		);

		return apply_filters( 'jnews_custom_option', $options );
	}

	public function get_value( $key, $term_id, $default ) {
		if ( ( $value = get_option( $key, false ) ) !== false ) {
			update_option( $this->prefix . $key, $value );
		}

		$value = get_option( $this->prefix . $key, false );

		if ( isset( $value[ $term_id ] ) ) {
			return $value[ $term_id ];
		} else {
			return $default;
		}
	}
}
