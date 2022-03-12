<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Jeg\Util\Font;

class JNews_AMP {

	/**
	 * @var JNews_AMP
	 */
	private static $instance;

	/**
	 * @var boolean
	 */
	protected $amp_ads = array();

	/**
	 * @return JNews_AMP
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * JNews_AMP constructor
	 */
	private function __construct() {
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * Setup hook
	 */
	protected function setup_hook() {
		// customizer
		add_action( 'jeg_register_customizer_option', array( $this, 'customizer_option' ) );
		add_filter( 'jeg_register_lazy_section', array( $this, 'autoload_section' ) );

		// amp
		add_filter( 'amp_post_template_data', array( $this, 'add_googlefont' ) );
		add_filter( 'amp_post_template_data', array( $this, 'add_body_class' ) );
		add_filter( 'amp_post_template_dir', array( $this, 'add_template_folder' ) );

		// favicon
		add_action( 'amp_post_template_head', array( $this, 'add_script' ) );
		add_action( 'amp_post_template_head', array( $this, 'add_favicon' ) );
		add_action( 'amp_post_template_head', array( $this, 'add_fontawesome' ) );

		// ads
		add_action( 'jnews_amp_before_header', array( $this, 'above_header_ads' ) );
		add_action( 'jnews_amp_before_header', array( $this, 'google_auto_ads' ) );
		add_action( 'jnews_amp_before_article', array( $this, 'above_article_ads' ) );
		add_action( 'jnews_amp_after_article', array( $this, 'below_article_ads' ) );
		add_action( 'jnews_amp_before_content', array( $this, 'above_content_ads' ) );
		add_action( 'jnews_amp_after_content', array( $this, 'below_content_ads' ) );
		add_filter( 'the_content', array( $this, 'inline_content_ads' ) );

		// related item
		add_action( 'jnews_amp_after_content', array( $this, 'related_item' ) );

		// main share button
		add_filter( 'jnews_single_share_main_button_list', array( $this, 'share_main_button' ) );

		// search form
		add_filter( 'jnews_get_permalink', array( $this, 'get_permalink' ) );

		// AMP
		add_filter( 'amp_post_template_metadata', array( $this, 'meta_data' ), null, 2 );

		add_action(
			'pre_amp_render_post',
			function() {
				remove_filter( 'the_content', array( \JNews\Ads::getInstance(), 'inject_ads' ), 10 );
			}
		);

		// mobile truncate
		add_filter( 'theme_mod_jnews_mobile_truncate', array( $this, 'mobile_truncate' ), 99 );

		// gdpr consent
		add_action( 'amp_post_template_footer', array( $this, 'render_gdpr_compliance' ) );
		add_filter( 'jnews_global_gdpr_option', array( $this, 'amp_gdpr_option' ) );

		// google analytics
		add_action( 'amp_post_template_footer', array( $this, 'render_google_analytics' ) );
	}

	public function render_google_analytics() {
		$analytics_code = get_theme_mod( 'jnews_google_analytics_code' );

		if ( $analytics_code ) {
			?>
			<amp-analytics type="googleanalytics">
				<script type="application/json">
					{
						"vars": {
							"account": "<?php esc_html_e( $analytics_code ); ?>"
						},
						"triggers": {
							"trackPageview": {
								"on": "visible",
								"request": "pageview"
							}
						}
					}
				</script>
			</amp-analytics>
			<?php
		}
	}

	public function amp_gdpr_option( $options ) {
		$options[] = array(
			'id'    => 'jnews_option[amp_gdpr_header}',
			'type'  => 'jnews-header',
			'label' => esc_html__( 'AMP GDPR Compliance', 'jnews-amp' ),
		);

		$options[] = array(
			'id'          => 'jnews_option[amp_gdpr_enable]',
			'option_type' => 'option',
			'transport'   => 'postMessage',
			'default'     => false,
			'type'        => 'jnews-toggle',
			'label'       => esc_html__( 'Enable GDPR Compliance', 'jnews-amp' ),
			'description' => esc_html__( 'Enable this option to show GDPR compliance notice on the AMP page.', 'jnews-amp' ),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_heading]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'default'         => esc_html__( 'Headline', 'jnews-amp' ),
			'type'            => 'jnews-text',
			'label'           => esc_html__( 'Headline', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert text for headline notice.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_text]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'default'         => esc_html__( 'This is an important message requiring you to make a choice if you\'re based in the EU.', 'jnews-amp' ),
			'type'            => 'jnews-textarea',
			'label'           => esc_html__( 'Notice Content', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert text for the notice content.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_accept]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'default'         => esc_html__( 'Accept', 'jnews-amp' ),
			'type'            => 'jnews-text',
			'label'           => esc_html__( 'Accept Text', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert text for accept button.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_reject]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'default'         => esc_html__( 'Reject', 'jnews-amp' ),
			'type'            => 'jnews-text',
			'label'           => esc_html__( 'Reject Text', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert text for reject button.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_setting]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'default'         => esc_html__( 'Update Consent', 'jnews-amp' ),
			'type'            => 'jnews-text',
			'label'           => esc_html__( 'Setting Text', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert text for setting button.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_privacy]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'type'            => 'jnews-text',
			'label'           => esc_html__( 'Privacy Policy', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert text for privacy policy link.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_privacy_url]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'type'            => 'jnews-text',
			'label'           => esc_html__( 'Privacy Policy URL', 'jnews-amp' ),
			'description'     => esc_html__( 'Insert url for privacy policy link.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$options[] = array(
			'id'              => 'jnews_option[amp_gdpr_fontawesome]',
			'option_type'     => 'option',
			'transport'       => 'postMessage',
			'default'         => false,
			'type'            => 'jnews-toggle',
			'label'           => esc_html__( 'Load FontAwesome Locally', 'jnews-amp' ),
			'description'     => esc_html__( 'Enable this option to force load FontAwesome locally.', 'jnews-amp' ),
			'active_callback' => array(
				array(
					'setting'  => 'jnews_option[amp_gdpr_enable]',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		return $options;
	}

	public function render_gdpr_compliance() {
		if ( ! jnews_get_option( 'amp_gdpr_enable', false ) ) {
			return false;
		}

		$countries = array( 'al', 'ad', 'am', 'at', 'by', 'be', 'ba', 'bg', 'ch', 'cy', 'cz', 'de', 'dk', 'ee', 'es', 'fo', 'fi', 'fr', 'gb', 'ge', 'gi', 'gr', 'hu', 'hr', 'ie', 'is', 'it', 'lt', 'lu', 'lv', 'mc', 'mk', 'mt', 'no', 'nl', 'po', 'pt', 'ro', 'ru', 'se', 'si', 'sk', 'sm', 'tr', 'ua', 'uk', 'va' );
		$countries = apply_filters( 'jnews_gdpr_country_list', $countries );
		$countries = implode( '","', $countries );

		$heading = jnews_get_option( 'amp_gdpr_heading', esc_html__( 'Headline', 'jnews-amp' ) );
		$content = jnews_get_option( 'amp_gdpr_text', esc_html__( 'This is an important message requiring you to make a choice if you\'re based in the EU.', 'jnews-amp' ) );
		$accept  = jnews_get_option( 'amp_gdpr_accept', esc_html__( 'Accept', 'jnews-amp' ) );
		$reject  = jnews_get_option( 'amp_gdpr_reject', esc_html__( 'Reject', 'jnews-amp' ) );
		$setting = jnews_get_option( 'amp_gdpr_setting', esc_html__( 'Update Consent', 'jnews-amp' ) );
		$privacy = jnews_get_option( 'amp_gdpr_privacy' );
		$url     = ! empty( $privacy ) ? '<a href="' . esc_url( jnews_get_option( 'amp_gdpr_privacy_url' ) ) . '" target="_blank">' . $privacy . '</a>' : '';

		?>
		<amp-geo layout="nodisplay">
			<script type="application/json">
				{
					"ISOCountryGroups": {
						"eu":[ <?php echo '"' . $countries . '"'; ?> ]
					}
				}
			</script>
		</amp-geo>
		<amp-consent id="jnewsAmpConsent" layout="nodisplay">
			<script type="application/json">{
				"consents": {
					"eu": {
						"promptIfUnknownForGeoGroup": "eu",
						"promptUI": "gdprConsentFlow"
					}
				},
				"postPromptUI": "post-consent-ui"
			}</script>
			<div class="gdpr-consent" id="gdprConsentFlow">
				<div class="gdpr-consent-wrapper">
					<button class="gdpr-consent-close" role="button" tabindex="0" on="tap:jnewsAmpConsent.dismiss">
						<i class="fa fa-window-close" aria-hidden="true"></i>
					</button>
					<div class="gdpr-consent-content">
						<h2><?php esc_html_e( $heading ); ?></h2>
						<p>
							<?php esc_html_e( $content ); ?>
							<?php echo jnews_sanitize_by_pass( $url ); ?>
						</p>
					</div>
					<div class="gdpr-consent-button">
							<button type="submit" on="tap:jnewsAmpConsent.accept" class="btn gdpr-consent-button-y"><?php esc_html_e( $accept ); ?></button>
							<button type="submit" on="tap:jnewsAmpConsent.reject" class="btn gdpr-consent-button-n"><?php esc_html_e( $reject ); ?></button>
					</div>
				</div>
			</div>
			<div id="post-consent-ui">
				<a href="#" on="tap:jnewsAmpConsent.prompt()" class="btn"><?php esc_html_e( $setting ); ?></a>
			</div>
		</amp-consent>
		<?php
	}

	public function mobile_truncate( $value ) {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return false;
		}

		return $value;
	}

	public function meta_data( $metadata, $post ) {
		unset( $metadata['image'] );

		// Type
		$metadata['@type'] = jnews_get_option( 'article_schema_type', 'article' );

		// URL
		$metadata['url'] = get_the_permalink( $post );

		// Thumbnail URL
		if ( has_post_thumbnail( $post ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( $post );
			$thumbnail         = wp_get_attachment_image_src( $post_thumbnail_id, 'jnews-120x86' );
			$fullImage         = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );

			$metadata['thumbnailUrl'] = $thumbnail[0];
			$metadata['image']        = $fullImage[0];
		}

		// Category
		$categories = get_the_category( $post->ID );
		if ( ! empty( $categories ) ) {
			$metadata['articleSection'] = array();
			foreach ( $categories as $category ) {
				$metadata['articleSection'][] = $category->name;
			}
		}

		// Publisher
		$logo = jnews_get_option( 'main_schema_logo', '' );
		if ( ! empty( $logo ) ) {
			$metadata['publisher']['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $logo,
			);
		}

		return $metadata;
	}

	public function add_favicon() {
		if ( has_site_icon() ) {
			wp_site_icon();
		}
	}

	public function get_permalink( $url ) {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			if ( ! is_ssl() ) {
				$url = preg_replace( '/^http:/i', '', $url );
			}
		}

		return $url;
	}

	/**
	 * Setup init
	 */
	protected function setup_init() {
		$locations = array( 'above_header', 'above_article', 'below_article', 'above_content', 'inline_content', 'below_content' );

		foreach ( $locations as $location ) {
			$enable_ad = jnews_get_option( 'amp_ads_' . $location . '_enable', false );

			if ( $enable_ad ) {
				$this->amp_ads[ $location ] = true;
			}
		}
	}

	/**
	 * Load customizer option
	 */
	public function customizer_option() {
		if ( class_exists( 'Jeg\Customizer\Customizer' ) ) {
			$customizer = Jeg\Customizer\Customizer::get_instance();

			$ads_amp_section = array(
				'id'       => 'jnews_ads_amp_section',
				'title'    => esc_html__( 'AMP Ads', 'jnews-amp' ),
				'panel'    => 'jnews_ads',
				'priority' => 252,
				'type'     => 'jnews-lazy-section',
			);

			// set section
			$customizer->add_section( $ads_amp_section );
		}
	}

	public function autoload_section( $result ) {
		$result['jnews_ads_amp_section'][] = JNEWS_AMP_DIR . 'amp-option.php';
		return $result;
	}

	/**
	 * Load amp template folder
	 */
	public function add_template_folder() {
		return JNEWS_AMP_DIR . 'template';
	}

	/**
	 * Add google font
	 */
	public function add_googlefont( $amp_data ) {
		if ( class_exists( 'Jeg\Util\Style_Generator' ) ) {
			$style_instance = Jeg\Util\Style_Generator::get_instance();
			$font_url       = $style_instance->get_font_url();

			$amp_data = $this->gdpr_google_font( $amp_data );

			if ( empty( $font_url ) ) {
				return $amp_data;
			}

			$font_url = 'https:' . $font_url;

			$amp_data['font_urls'] = array(
				'customizer-fonts' => $font_url,
			);
		}

		return $amp_data;
	}

	public function gdpr_google_font( $amp_data ) {
		if ( class_exists( 'Jeg\Util\Font' ) && get_theme_mod( 'jnews_gdpr_google_font_disable', false ) ) {

			if ( isset( $amp_data['font_urls'] ) && is_array( $amp_data['font_urls'] ) ) {

				foreach ( $amp_data['font_urls'] as $key => $value ) {

					if ( Font::is_google_font( ucfirst( $key ) ) ) {
						unset( $amp_data['font_urls'][ $key ] );
					}
				}
			}
		}

		return $amp_data;
	}

	/**
	 * Add Additional Body Class
	 */
	public function add_body_class( $amp_data ) {
		if ( is_rtl() ) {
			$amp_data['body_class'] .= ' rtl';
		}

		return $amp_data;
	}

	/**
	 * Add script
	 */
	public function add_script( $amp_template ) {
		$scripts = array();
		$format  = get_post_format( get_the_ID() );

		if ( $format === 'gallery' ) {
			$scripts[] = array(
				'name'   => 'amp-carousel',
				'source' => 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js',
			);
		}

		if ( $format === 'video' ) {
			$video_url = get_post_meta( get_the_ID(), '_format_video_embed', true );

			if ( jnews_check_video_type( $video_url ) === 'youtube' ) {
				$scripts[] = array(
					'name'   => 'amp-youtube',
					'source' => 'https://cdn.ampproject.org/v0/amp-youtube-0.1.js',
				);
			}
		}

		if ( ! empty( $this->amp_ads ) ) {
			$scripts[] = array(
				'name'   => 'amp-ad',
				'source' => 'https://cdn.ampproject.org/v0/amp-ad-0.1.js',
			);
		}

		// sidebar
		$scripts[] = array(
			'name'   => 'amp-sidebar',
			'source' => 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js',
		);

		if ( jnews_get_option( 'amp_gdpr_enable', false ) ) {
			// amp geo
			$scripts[] = array(
				'name'   => 'amp-geo',
				'source' => 'https://cdn.ampproject.org/v0/amp-geo-0.1.js',
			);

			// amp consent
			$scripts[] = array(
				'name'   => 'amp-consent',
				'source' => 'https://cdn.ampproject.org/v0/amp-consent-0.1.js',
			);
		}

		// form
		if ( $this->header_search_form() ) {
			$scripts[] = array(
				'name'   => 'amp-form',
				'source' => 'https://cdn.ampproject.org/v0/amp-form-0.1.js',
			);
		}

		// Google Auto Ads
		if ( jnews_get_option( 'amp_ads_google_auto_enable', false ) ) {
			$scripts[] = array(
				'name'   => 'amp-auto-ads',
				'source' => 'https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js',
			);
		}

		// google analytics
		if ( get_theme_mod( 'jnews_google_analytics_code' ) ) {
			$scripts[] = array(
				'name'   => 'amp-analytics',
				'source' => 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js',
			);
		}

		foreach ( $scripts as $script ) {
			$loaded_script = $amp_template->get( 'amp_component_scripts', array() );

			if ( ! empty( $script['name'] ) && ! array_key_exists( $script['name'], $loaded_script ) ) {
				?>
				<script custom-element="<?php echo esc_attr( $script['name'] ); ?>" src="<?php echo esc_url( $script['source'] ); ?>" async></script>
				<?php
			}
		}
	}

	public function add_fontawesome() {
		if ( jnews_get_option( 'amp_gdpr_fontawesome', false ) ) {
			echo '<link href="' . get_parent_theme_file_uri( 'assets/fonts/font-awesome/font-awesome.min.css' ) . '" rel="stylesheet" type="text/css">';
		} else {
			echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">';
		}
	}

	/**
	 * Google Auto Ads
	 */
	public function google_auto_ads() {
		if ( jnews_get_option( 'amp_ads_google_auto_enable', false ) ) {
			$publisher_id = jnews_get_option( 'amp_ads_google_auto_publisher', false );

			if ( $publisher_id ) {
				$html = "<amp-auto-ads type=\"adsense\" data-ad-client=\"{$publisher_id}\"></amp-auto-ads>";
				echo jnews_sanitize_by_pass( $html );
			}
		}
	}

	/**
	 * Above header ads
	 */
	public function above_header_ads() {
		$location = 'above_header';

		if ( array_key_exists( $location, $this->amp_ads ) ) {
			$html = "<div class=\"amp_ad_wrapper jnews_amp_{$location}_ads\">" . $this->render_ads( $location ) . '</div>';

			echo jnews_sanitize_by_pass( $html );
		}
	}

	/**
	 * Above article ads
	 */
	public function above_article_ads() {
		$location = 'above_article';

		if ( array_key_exists( $location, $this->amp_ads ) ) {
			$html = "<div class=\"amp_ad_wrapper jnews_amp_{$location}_ads\">" . $this->render_ads( $location ) . '</div>';

			echo jnews_sanitize_by_pass( $html );
		}
	}

	/**
	 * Below article ads
	 */
	public function below_article_ads() {
		$location = 'below_article';

		if ( array_key_exists( $location, $this->amp_ads ) ) {
			$html = "<div class=\"amp_ad_wrapper jnews_amp_{$location}_ads\">" . $this->render_ads( $location ) . '</div>';

			echo jnews_sanitize_by_pass( $html );
		}
	}

	/**
	 * Above content ads
	 */
	public function above_content_ads() {
		$location = 'above_content';

		if ( array_key_exists( $location, $this->amp_ads ) ) {
			$html = "<div class=\"amp_ad_wrapper jnews_amp_{$location}_ads\">" . $this->render_ads( $location ) . '</div>';

			echo jnews_sanitize_by_pass( $html );
		}
	}

	/**
	 * Below content ads
	 */
	public function below_content_ads() {
		$location = 'below_content';

		if ( array_key_exists( $location, $this->amp_ads ) ) {
			$html = "<div class=\"amp_ad_wrapper jnews_amp_{$location}_ads\">" . $this->render_ads( $location ) . '</div>';

			echo jnews_sanitize_by_pass( $html );
		}
	}

	/**
	 * Inline content ads
	 */
	public function inline_content_ads( $content ) {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			$location = 'inline_content';

			if ( array_key_exists( $location, $this->amp_ads ) ) {
				$tag     = new \JNews\ContentTag( $content );
				$pnumber = $tag->total( 'p' );

				$adsposition = jnews_get_option( 'amp_ads_' . $location . '_paragraph', 3 );
				$adsrandom   = jnews_get_option( 'amp_ads_' . $location . '_paragraph_random', false );

				if ( $adsrandom ) {
					if ( is_array( $pnumber ) ) {
						$maxparagraph = count( $pnumber ) - 2;
						$adsposition  = rand( $adsposition, $maxparagraph );
					}
				}

				$html    = "<div class=\"amp_ad_wrapper jnews_amp_{$location}_ads\">" . $this->render_ads( $location ) . '</div>';
				$content = $this->prefix_insert_after_paragraph( $html, $adsposition, $tag );
			}
		}

		return $content;
	}

	/**
	 * Render ads
	 *
	 * @param  string $location
	 *
	 * @return string
	 */
	protected function render_ads( $location ) {
		$ads_html = '';

		if ( jnews_get_option( 'amp_ads_' . $location . '_type', 'googleads' ) == 'googleads' ) {
			$publisherid = jnews_get_option( 'amp_ads_' . $location . '_google_publisher', '' );
			$slotid      = jnews_get_option( 'amp_ads_' . $location . '_google_id', '' );

			$publisherid = str_replace( ' ', '', $publisherid );
			$slotid      = str_replace( ' ', '', $slotid );

			if ( ! empty( $publisherid ) && ! empty( $slotid ) ) {
				$ad_size = jnews_get_option( 'amp_ads_' . $location . '_size', 'auto' );

				if ( $ad_size !== 'auto' ) {
					$ad_size = explode( 'x', $ad_size );
				} else {
					$ad_size = array( '320', '50' );
				}

				$publisherid = str_replace( 'ca-', '', $publisherid );

				$gdpr_consent = jnews_get_option( 'amp_gdpr_enable', false ) ? 'data-block-on-consent="_till_accepted"' : '';

				$ads_html .=
					"<amp-ad
                        {$gdpr_consent}
                        type=\"adsense\"
                        width={$ad_size[0]} 
                        height={$ad_size[1]}
                        data-ad-client=\"ca-{$publisherid}\"
                        data-ad-slot=\"{$slotid}\">
                    </amp-ad>";
			}
		} else {
			$ads_html = jnews_get_option( 'amp_ads_' . $location . '_custom', '' );
		}

		return $ads_html;
	}

	/**
	 * Insert ads into certain paragraph
	 *
	 * @param  string            $insertion
	 * @param  int               $paragraph_id
	 * @param  \JNews\ContentTag $tag
	 *
	 * @return string
	 */
	protected function prefix_insert_after_paragraph( $insertion, $paragraph_id, $tag ) {
		$line = $tag->find( 'p', $paragraph_id );
		return jeg_string_insert( $tag->get_content(), $insertion, $line );
	}

	/**
	 * Generate related post item
	 */
	public function related_item() {
		if ( class_exists( 'JNews\\Module\\ModuleQuery' ) ) {
			$match         = get_theme_mod( 'jnews_single_post_related_match', 'category' );
			$post_per_page = get_theme_mod( 'jnews_single_number_post_related', 5 );
			$related_amp   = '';

			$category = $tag = $result = array();

			if ( $match === 'category' ) {
				$this->recursive_category( get_the_category(), $result );

				if ( $result ) {
					foreach ( $result as $cat ) {
						$category[] = $cat->term_id;
					}
				}
			} elseif ( $match === 'tag' ) {

				$tags = get_the_tags();

				if ( $tags ) {
					foreach ( $tags as $cat ) {
						$tag[] = $cat->term_id;
					}
				}
			}

			$attr = array(
				'post_type'              => 'post',
				'pagination_number_post' => $post_per_page,
				'number_post'            => $post_per_page,
				'include_category'       => implode( ',', $category ),
				'include_tag'            => implode( ',', $tag ),
				'exclude_post'           => get_the_ID(),
				'sort_by'                => 'latest',
				'post_offset'            => 0,
			);

			$result   = JNews\Module\ModuleQuery::do_query( $attr );
			$contents = $result['result'];

			if ( ! empty( $contents ) ) {
				$related_content = '';

				foreach ( $contents as $content ) {
					$author      = $content->post_author;
					$author_name = get_the_author_meta( 'display_name', $author );
					$date        = jeg_get_post_date( '', $content );
					$image       = '';

					if ( has_post_thumbnail( $content->ID ) ) {
						$image = get_the_post_thumbnail_url( $content->ID, 'jnews-120x86' );
						$image = "<amp-img src='{$image}' width='120' height='86' layout='responsive' class='amp-related-image'></amp-img>";
					}

					$related_content .=
						"<div class='amp-related-content'>
                            {$image}
                            <div class='amp-related-text'>
                                <h3><a href='" . get_permalink( $content->ID ) . "'>{$content->post_title}</a></h3>
                                <div class='amp-related-meta'>
                                    " . jnews_return_translation( 'By', 'jnews-amp', 'by' ) . "
                                    <span class='amp-related-author'>{$author_name}</span>
                                    <span class='amp-related-date'>{$date}</span>
                                </div>
                            </div>
                        </div>";
				}

				$related_amp =
					"<div class='amp-related-wrapper'>
                        <h2>" . jnews_return_translation( 'Related Content', 'jnews-amp', 'related_content' ) . "</h2>
                        {$related_content}
                    </div>";
			}

			echo jnews_sanitize_by_pass( $related_amp );
		}
	}

	/**
	 * Get category list of post
	 *
	 * @param  array $categories
	 * @param  array &$result
	 */
	protected function recursive_category( $categories, &$result ) {
		foreach ( $categories as $category ) {
			$result[] = $category;
			$children = get_categories( array( 'parent' => $category->term_id ) );

			if ( ! empty( $children ) ) {
				$this->recursive_category( $children, $result );
			}
		}
	}

	protected function header_search_form() {
		$top_element    = get_theme_mod( 'jnews_hb_element_mobile_drawer_top_center', jnews_header_default( 'drawer_element_top' ) );
		$bottom_element = get_theme_mod( 'jnews_hb_element_mobile_drawer_bottom_center', jnews_header_default( 'drawer_element_bottom' ) );
		$elements       = array_merge( $top_element, $bottom_element );

		if ( in_array( 'search_form', $elements ) ) {
			return true;
		}

		return false;
	}

	public function share_main_button( $main_button ) {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			foreach ( $main_button as $key => $value ) {
				if ( $value['social_share'] == 'wechat' ) {
					unset( $main_button[ $key ] );
				}
			}
		}

		return $main_button;
	}
}

