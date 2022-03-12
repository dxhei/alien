<?php
	/* Global Color */
	$body_color    = get_theme_mod( 'jnews_body_color', '#53585c' );
	$body_bg_color = get_theme_mod( 'jnews_container_background', '#FFFFFF' );
	$accent_color  = get_theme_mod( 'jnews_accent_color', '#f70d28' );

	$mobile_heading_color     = get_theme_mod( 'jnews_header_mobile_midbar_background_color', '' );
	$mobile_heading_btn_color = get_theme_mod( 'jnews_header_nav_icon_mobilcolor', '' );

	$mobile_logo          = get_theme_mod( 'jnews_mobile_logo', get_template_directory_uri() . '/assets/img/logo_mobile.png' );
	$mobile_logo_retina   = get_theme_mod( 'jnews_mobile_logo_retina', get_template_directory_uri() . '/assets/img/logo_mobile@2x.png' );
	$mobile_header_height = get_theme_mod( 'jnews_header_mobile_midbar_height', 60 );

	$menu_social_color               = get_theme_mod( 'jnews_header_drawer_social_icon_text_color', '#a0a0a0' );
	$mobile_drawer_bg_color          = get_theme_mod( 'jnews_header_mobile_drawer_background_color', '#fff' );
	$mobile_drawer_overlay_color     = get_theme_mod( 'jnews_header_mobile_drawer_overlay_color', '' );
	$mobile_drawer_social_icon_color = get_theme_mod( 'jnews_header_drawer_social_icon_text_color', '' );

	$mobile_drawer_bg_image           = get_theme_mod( 'jnews_header_mobile_drawer_background_image', '' );
	$mobile_drawer_bg_image_repeat    = get_theme_mod( 'jnews_header_mobile_drawer_background_repeat', '' );
	$mobile_drawer_bg_image_posistion = get_theme_mod( 'jnews_header_mobile_drawer_background_position', '' );
	$mobile_drawer_bg_image_fixed     = get_theme_mod( 'jnews_header_mobile_drawer_background_fixed', '' );
	$mobile_drawer_bg_image_size      = get_theme_mod( 'jnews_header_mobile_drawer_background_size', '' );

	$heading_color = get_theme_mod( 'jnews_heading_color', '#212121' );

	/* Font */
	$settings = apply_filters( 'jeg_fonts_option_setting', '' );
	$fonts    = array();

if ( is_array( $settings ) ) {
	foreach ( $settings as $setting ) {
		$option = get_theme_mod( $setting );

		if ( ! empty( $option['font-family'] ) ) {
			$fonts[ $setting ] = $option['font-family'];
		}
	}
}
?>

/*** Generic WP ***/
/*.alignright {
	float: right;
}
.alignleft {
	float: left;
}*/
.aligncenter {
	display: block;
	margin-left: auto;
	margin-right: auto;
}
.amp-wp-enforced-sizes {
	/** Our sizes fallback is 100vw, and we have a padding on the container; the max-width here prevents the element from overflowing. **/
	max-width: 100%;
	margin: 0 auto;
}
.amp-wp-unknown-size img {

	/** Worst case scenario when we can't figure out dimensions for an image. **/

	/** Force the image into a box of fixed dimensions and use object-fit to scale. **/
	object-fit: contain;
}

/* Clearfix */
.clearfix:before, .clearfix:after {
	content: " ";
	display: table;
}

.clearfix:after {
	clear: both;
}

/*** Theme Styles ***/
.amp-wp-content, .amp-wp-title-bar div {
	margin: 0 auto;
	max-width: 600px;
}
body, html {
	height: 100%;
	margin: 0;
}
body {
	background-color: <?php echo esc_attr( $body_bg_color ); ?>;
	color: <?php echo esc_attr( $body_color ); ?>;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif;
	font-size: 14px;
	line-height: 1.785714285714286em;
	text-rendering: optimizeLegibility;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}
h1,
h2,
h3,
h4,
h5,
h6,
.amp-wp-title {
	color: <?php echo esc_attr( $heading_color ); ?>;
}

<?php if ( isset( $fonts['jnews_body_font'] ) && $fonts['jnews_body_font'] ) : ?>
body,
input,
textarea,
select,
.chosen-container-single .chosen-single,
.btn,
.button {
	font-family: <?php echo esc_attr( $fonts['jnews_body_font'] ); ?>;
}
<?php endif; ?>

<?php if ( isset( $fonts['jnews_p_font'] ) && $fonts['jnews_p_font'] ) : ?>
.amp-wp-article-content p {
	font-family: <?php echo esc_attr( $fonts['jnews_p_font'] ); ?>;
}
<?php endif; ?>

<?php if ( isset( $fonts['jnews_header_font'] ) && $fonts['jnews_header_font'] ) : ?>
.jeg_mobile_wrapper {
	font-family: <?php echo esc_attr( $fonts['jnews_header_font'] ); ?>;
}
<?php endif; ?>

<?php if ( isset( $fonts['jnews_main_menu_font'] ) && $fonts['jnews_main_menu_font'] ) : ?>
.jeg_mobile_menu {
	font-family: <?php echo esc_attr( $fonts['jnews_main_menu_font'] ); ?>;
}
<?php endif; ?>

<?php if ( isset( $fonts['jnews_h1_font'] ) && $fonts['jnews_h1_font'] ) : ?>
.amp-wp-title {
	font-family: <?php echo esc_attr( $fonts['jnews_h1_font'] ); ?>;
}
<?php endif; ?>

::-moz-selection {
	background: #fde69a;
	color: #212121;
	text-shadow: none;
}
::-webkit-selection {
	background: #fde69a;
	color: #212121;
	text-shadow: none;
}
::selection {
	background: #fde69a;
	color: #212121;
	text-shadow: none;
}
p, ol, ul, figure {
	margin: 0 0 1em;
	padding: 0;
}
a, a:visited {
	text-decoration: none;
}
a:hover, a:active, a:focus {
	color: #212121;
}

/*** Global Color ***/
a,
a:visited,
#breadcrumbs a:hover,
.amp-related-content h3 a:hover,
.amp-related-content h3 a:focus,
.bestprice .price, .jeg_review_title
{
	color: <?php echo esc_attr( $accent_color ); ?>;
}

/*** Header ***/
.amp-wp-header {
	text-align: center;
	background-color: #fff;
	height: <?php echo esc_attr( $mobile_header_height ); ?>px;
	box-shadow: 0 2px 6px rgba(0, 0, 0,.1);
}
.amp-wp-header.dark {
	background-color: #212121;
}
.amp-wp-header .jeg_mobile_logo {
	flex: 1 0 0%;
	background-image: url(<?php echo esc_url( $mobile_logo ); ?>);
}
@media
only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (     -o-min-device-pixel-ratio: 2/1),
only screen and (        min-device-pixel-ratio: 2),
only screen and (                min-resolution: 192dpi),
only screen and (                min-resolution: 2dppx) {
	.amp-wp-header .jeg_mobile_logo {
		background-image: url(<?php echo esc_url( $mobile_logo_retina ); ?>);
		background-size: auto 40px;
	}
}
<?php if ( ! empty( $mobile_heading_color ) ) : ?>
.amp-wp-header,
.amp-wp-header.dark  {
	background-color: <?php echo esc_attr( $mobile_heading_color ); ?>;
}
<?php endif; ?>

.amp-wp-header div {
	color: #fff;
	font-size: 1em;
	font-weight: 400;
	margin: 0 auto;
	position: relative;
	display: flex;
	flex-wrap: wrap;
	flex-direction: row;
	width: 100%;
	height: 100%;
}
.amp-wp-header a {
	text-align: center;
	width: 100%;
	height: 100%;
	display: block;
	background-position: center center;
	background-repeat: no-repeat;
}
.amp-wp-site-icon {
	vertical-align: middle;
}

/*** Article ***/
.amp-wp-article {
	color: #333;
	font-size: 16px;
	line-height: 1.625em;
	margin: 22px auto 30px;
	padding: 0 15px;
	max-width: 840px;
	overflow-wrap: break-word;
	word-wrap: break-word;
}

/* Article Breadcrumb */
.amp-wp-breadcrumb {
	margin: -5px auto 10px;
	font-size: 11px;
	color: #a0a0a0;
}
#breadcrumbs a {
	color: #53585c;
}
#breadcrumbs .fa {
	padding: 0 3px
}
#breadcrumbs .breadcrumb_last_link a {
	color: #a0a0a0
}

/* Article Header */
.amp-wp-article-header {
	margin-bottom: 15px;
}
.amp-wp-title {
	display: block;
	width: 100%;
	font-size: 32px;
	font-weight: bold;
	line-height: 1.15;
	margin: 0 0 .4em;
	letter-spacing: -0.04em;
}

h2.amp-wp-subtitle {
	font: inherit;
	font-size: 20px;
	line-height: 1.4em;
	margin-bottom: 20px;
	color: #a0a0a0;
}

/* Article Meta */
.amp-wp-meta {
	color: #a0a0a0;
	list-style: none;
	font-size: smaller;
}
.amp-wp-meta li {
	display: inline-block;
	line-height: 1;
}
.amp-wp-byline amp-img, .amp-wp-byline .amp-wp-author {
	display: inline-block;
}
.amp-wp-author a {
	font-weight: bold;
}
.amp-wp-byline amp-img {
	border-radius: 100%;
	position: relative;
	margin-right: 6px;
	vertical-align: middle;
}
.amp-wp-posted-on {
	margin-left: 5px;
}
.amp-wp-posted-on:before {
	content: '\2014';
	margin-right: 5px;
}

/* Featured image */
/* .amp-wp-article .amp-wp-article-featured-image {
	margin: 0 -15px 15px;
	max-width: none;
} */
.amp-wp-article-featured-image amp-img {
	margin: 0 auto;
}
.amp-wp-article-featured-image.wp-caption .wp-caption-text {
	margin: 0 18px;
}

/* Social Share */
.jeg_share_amp_container {
	margin: 0 0 15px;
}
.jeg_sharelist {
	float: none;
	display: -webkit-flex;
	display: -ms-flexbox;
	display: flex;
	-webkit-flex-wrap: wrap;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	-webkit-align-items: flex-start;
	-ms-flex-align: start;
	align-items: flex-start;
}
.jeg_share_button a {
	display: -webkit-flex;
	display: -ms-flexbox;
	display: flex;
	-webkit-flex: 1;
	-ms-flex: 1;
	flex: 1;
	-webkit-justify-content: center;
	-ms-flex-pack: center;
	justify-content: center;
	-webkit-backface-visibility: hidden;
	backface-visibility: hidden;
}
.jeg_share_button a {
	float: left;
	width: auto;
	height: 32px;
	line-height: 32px;
	white-space: nowrap;
	padding: 0 10px;
	color: #fff;
	background: #212121;
	margin: 0 5px 5px 0;
	border-radius: 3px;
	text-align: center;
	-webkit-transition: .2s;
	-o-transition: .2s;
	transition: .2s;
}
.jeg_share_button a:last-child {
	margin-right: 0
}
.jeg_share_button a:hover {
	opacity: .75
}
.jeg_share_button a > span {
	display: none;
}
.jeg_share_button .fa {
	font-size: 16px;
	line-height: inherit;
}
.jeg_share_button .jeg_btn-facebook {
	background: #45629f;
}
.jeg_share_button .jeg_btn-twitter {
	background: #5eb2ef;
}
.jeg_share_button .jeg_btn-pinterest {
	background: #e02647;
}
.jeg_share_button .jeg_btn-google-plus {
	background: #df5443;
}

/*** Article Content ***/
.amp-wp-article-content {
	color: <?php echo esc_attr( $body_color ); ?>;
}
.amp-wp-article-content ul, .amp-wp-article-content ol {
	margin: 0 0 1.5em 1.5em;
}
.amp-wp-article-content li {
	margin-bottom: 0.5em;
}
.amp-wp-article-content ul {
	list-style: square;
}
.amp-wp-article-content ol {
	list-style: decimal;
}
.amp-wp-article-content ul.fa-ul {
	list-style: none;
	margin-left: inherit;
	padding-left: inherit;
}
.amp-wp-article-content amp-img {
	margin: 0 auto 15px;
}
.amp-wp-article-content .wp-caption amp-img {
	margin-bottom: 0px;
}
.amp-wp-article-content amp-img.alignright {
	margin: 5px -15px 15px 15px;
	max-width: 60%;
}
.amp-wp-article-content amp-img.alignleft {
	margin: 5px 15px 15px -15px;
	max-width: 60%;
}

.amp-wp-article-content h1, .amp-wp-article-content h2, .amp-wp-article-content h3, .amp-wp-article-content h4, .amp-wp-article-content h5, .amp-wp-article-content h6 {
	font-weight: 500;
}

dt {
	font-weight: 600;
}
dd {
	margin-bottom: 1.25em;
}
em, cite {
	font-style: italic;
}
ins {
	background: #fcf8e3;
}
sub, sup {
	font-size: 62.5%;
}
sub {
	vertical-align: sub;
	bottom: 0;
}
sup {
	vertical-align: super;
	top: 0.25em;
}

/* Table */
table {
	width: 100%;
	margin: 1em 0 30px;
	line-height: normal;
	color: #7b7b7b;
}
tr {
	border-bottom: 1px solid #eee;
}
tbody tr:hover {
	color: #53585c;
	background: #f7f7f7;
}
thead tr {
	border-bottom: 2px solid #eee;
}
th, td {
	font-size: 0.85em;
	padding: 8px 20px;
	text-align: left;
	border-left: 1px solid #eee;
	border-right: 1px solid #eee;
}
th {
	color: #53585c;
	font-weight: bold;
	vertical-align: middle;
}
tbody tr:last-child, th:first-child, td:first-child, th:last-child, td:last-child {
	border: 0;
}

/* Quotes */
blockquote {
	display: block;
	color: #7b7b7b;
	font-style: italic;
	padding-left: 1em;
	border-left: 4px solid #eee;
	margin: 0 0 15px 0;
}
blockquote p:last-child {
	margin-bottom: 0;
}

/* Captions */
.wp-caption {
	max-width: 100%;
	box-sizing: border-box;
}
.wp-caption.alignleft {
	margin: 5px 20px 20px 0;
}
.wp-caption.alignright {
	margin: 5px 0 20px 20px;
}
.wp-caption .wp-caption-text {
	margin: 3px 0 1em;
	font-size: 12px;
	color: #a0a0a0;
	text-align: center;
}
.wp-caption a {
	color: #a0a0a0;
	text-decoration: underline;
}

/* AMP Media */
article > amp-carousel {
	margin-top: -25px;
}
.amp-wp-article-content amp-carousel amp-img {
	border: none;
}
amp-carousel > amp-img > img {
	object-fit: contain;
}
.amp-wp-iframe-placeholder {
	background-color: #212121;
	background-size: 48px 48px;
	min-height: 48px;
}

/* Shortcodes */
.intro-text {
	font-size: larger;
	line-height: 1.421em;
	letter-spacing: -0.01em;
}

.dropcap {
	display: block;
	float: left;
	margin: 0.04em 0.2em 0 0;
	color: #212121;
	font-size: 3em;
	line-height: 1;
	padding: 10px 15px;
}
.dropcap.rounded {
	border-radius: 10px;
}

/* Pull Quote */
.pullquote {
	font-size: larger;
	border: none;
	padding: 0 1em;
	position: relative;
	text-align: center;
}
.pullquote:before, .pullquote:after {
	content: '';
	display: block;
	width: 50px;
	height: 2px;
	background: #eee;
}
.pullquote:before {
	margin: 1em auto 0.65em;
}
.pullquote:after {
	margin: 0.75em auto 1em;
}

/* Article Review */
.jeg_review_wrap {
	border-top: 3px solid #eee;
	padding-top: 20px;
	margin: 40px 0;
}
.jeg_reviewheader {
	margin-bottom: 20px;
}
.jeg_review_title {
	font-weight: bold;
	margin: 0 0 20px;
}
.jeg_review_wrap .jeg_review_subtitle {
	font-size: smaller;
	line-height: 1.4em;
	margin: 0 0 5px;
}
.jeg_review_wrap h3 {
	font-size: 16px;
	font-weight: bolder;
	margin: 0 0 10px;
	text-transform: uppercase;
}
.review_score {
	float: left;
	color: #fff;
	text-align: center;
	width: 70px;
	margin-right: 15px;
}
.review_score .score_text {
	background: rgba(0, 0, 0, 0.08);
}
.score_good {
	background: #0D86F7;
}
.score_avg {
	background: #A20DF7;
}
.score_bad {
	background: #F70D0D;
}
.score_value {
	display: block;
	font-size: 26px;
	font-weight: bold;
	line-height: normal;
	padding: 10px 0;
}
.score_value .percentsym {
	font-size: 50%;
	vertical-align: super;
	margin-right: -0.45em;
}
.score_text {
	display: block;
	padding: 3px 0;
	font-size: 12px;
	letter-spacing: 1.5px;
	text-transform: uppercase;
	text-rendering: auto;
	-webkit-font-smoothing: auto;
	-moz-osx-font-smoothing: auto;
}
.jeg_reviewdetail {
	margin-bottom: 30px;
	background: #f5f5f5;
	border-radius: 3px;
	width: 100%;
}
.conspros {
	padding: 15px 20px;
}
.jeg_reviewdetail .conspros:first-child {
	border-bottom: 1px solid #e0e0e0;
}
.jeg_reviewdetail ul {
	margin: 0;
	list-style-type: none;
	font-size: smaller;
}
.jeg_reviewdetail li {
	padding-left: 22px;
	position: relative;
}
.jeg_reviewdetail li > i {
	color: #a0a0a0;
	position: absolute;
	left: 0;
	top: -1px;
	font-style: normal;
	font-size: 14px;
}
.jeg_reviewdetail li > i:before {
	font-family: 'FontAwesome';
	content: "\f00c";
	display: inline-block;
	text-rendering: auto;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}
.jeg_reviewdetail .conspros:last-child li > i:before {
	content: "\f00d";
}

/* Review Breakdown */
.jeg_reviewscore {
	padding: 15px 20px;
	border: 2px solid #eee;
	border-radius: 3px;
	margin-bottom: 30px;
}
.jeg_reviewscore ul {
	margin: 0;
	font-family: inherit;
}
.jeg_reviewscore li {
	margin: 1em 0;
	padding: 0;
	font-size: 13px;
	list-style: none;
}

/* Review Stars */
.jeg_review_stars {
	font-size: 14px;
	color: #F7C90D;
}
.jeg_reviewstars li {
	border-top: 1px dotted #eee;
	padding-top: 8px;
	margin: 8px 0;
}
.jeg_reviewstars .reviewscore {
	float: right;
	font-size: 18px;
	color: #F7C90D;
}

/* Review Bars */
.jeg_reviewbars .reviewscore {
	font-weight: bold;
	float: right;
}
.jeg_reviewbars .jeg_reviewbar_wrap, .jeg_reviewbars .barbg {
	height: 4px;
	border-radius: 2px;
}
.jeg_reviewbars .jeg_reviewbar_wrap {
	position: relative;
	background: #eee;
	clear: both;
}
.jeg_reviewbars .barbg {
	position: relative;
	display: block;
	background: #F7C90D;
	background: -moz-linear-gradient(left, rgba(247, 201, 13, 1) 0%, rgba(247, 201, 13, 1) 45%, rgba(247, 126, 13, 1) 100%);
	background: -webkit-linear-gradient(left, rgba(247, 201, 13, 1) 0%, rgba(247, 201, 13, 1) 45%, rgba(247, 126, 13, 1) 100%);
	background: linear-gradient(to right, rgba(247, 201, 13, 1) 0%, rgba(247, 201, 13, 1) 45%, rgba(247, 126, 13, 1) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f7c90d', endColorstr='#f77e0d', GradientType=1);
}
.jeg_reviewbars .barbg:after {
	width: 10px;
	height: 10px;
	border-radius: 100%;
	background: #fff;
	content: '';
	position: absolute;
	right: 0;
	display: block;
	border: 3px solid #F77E0D;
	top: -3px;
	box-sizing: border-box;
}

/* Product Deals */
.jeg_deals h3 {
	text-transform: none;
	font-size: 18px;
	margin-bottom: 5px;
	font-weight: bold;
}
.dealstitle p {
	font-size: smaller;
}
.bestprice {
	margin-bottom: 1em;
}
.bestprice h4 {
	font-size: smaller;
	font-weight: bold;
	text-transform: uppercase;
	display: inline;
}
.bestprice .price {
	font-size: 1em;
}
.jeg_deals .price {
	font-weight: bold;
}
.jeg_deals a {
	color: #212121
}

/* Deals on Top Article */
.jeg_deals_float {
	width: 100%;
	padding: 10px 15px 15px;
	background: #f5f5f5;
	border-radius: 3px;
	margin: 0 0 1.25em;
	box-sizing: border-box;
}
.jeg_deals_float h3 {
	font-size: smaller;
	margin: 0 0 5px;
}
.jeg_deals_float .jeg_storelist li {
	padding: 3px 7px;
	font-size: small;
}
ul.jeg_storelist {
	list-style: none;
	margin: 0;
	border: 1px solid #e0e0e0;
	font-family: inherit;
}
.jeg_storelist li {
	background: #fff;
	border-bottom: 1px solid #e0e0e0;
	padding: 5px 10px;
	margin: 0;
	font-size: 13px;
}
.jeg_storelist li:last-child {
	border-bottom: 0
}
.jeg_storelist li .priceinfo {
	float: right;
}
.jeg_storelist .productlink {
	display: inline-block;
	padding: 0 10px;
	color: #fff;
	border-radius: 2px;
	font-size: 11px;
	line-height: 20px;
	margin-left: 5px;
	text-transform: uppercase;
	font-weight: bold;
}

/* Article Footer Meta */
.amp-wp-meta-taxonomy {
	display: block;
	list-style: none;
	margin: 20px 0;
	border-bottom: 2px solid #eee;
}
.amp-wp-meta-taxonomy span {
	font-weight: bold;
}
.amp-wp-tax-category, .amp-wp-tax-tag {
	font-size: smaller;
	line-height: 1.4em;
	margin: 0 0 1em;
}
.amp-wp-tax-tag span {
	font-weight: bold;
	margin-right: 3px;
}
.amp-wp-tax-tag a {
	color: #616161;
	background: #f5f5f5;
	display: inline-block;
	line-height: normal;
	padding: 3px 8px;
	margin: 0 3px 5px 0;
	-webkit-transition: all 0.2s linear;
	-o-transition: all 0.2s linear;
	transition: all 0.2s linear;
}
.amp-wp-tax-tag a:hover,
.jeg_storelist .productlink,
.jpw_btn_wrapper .btn {
	color: #fff;
	background: <?php echo esc_attr( $accent_color ); ?>;
}

/* AMP Related */
.amp-related-wrapper h2 {
	font-size: 16px;
	font-weight: bold;
	margin-bottom: 10px;
}
.amp-related-content {
	margin-bottom: 15px;
	overflow: hidden;
}
.amp-related-content amp-img {
	float: left;
	width: 100px;
}
.amp-related-text {
	margin-left: 100px;
	padding-left: 15px;
}
.amp-related-content h3 {
	font-size: 14px;
	font-weight: 500;
	line-height: 1.4em;
	margin: 0 0 5px;
}
.amp-related-content h3 a {
	color: #212121;
}
.amp-related-content .amp-related-meta {
	color: #a0a0a0;
	font-size: 10px;
	line-height: normal;
	text-transform: uppercase;
}
.amp-related-date {
	margin-left: 5px;
}
.amp-related-date:before {
	content: '\2014';
	margin-right: 5px;
}

/* AMP Comment */
.amp-wp-comments-link {
}
.amp-wp-comments-link a {
}

/* AMP Footer */
.amp-wp-footer {
	background: #f5f5f5;
	color: #999;
	text-align: center;
}
.amp-wp-footer .amp-wp-footer-inner {
	margin: 0 auto;
	padding: 15px;
	position: relative;
}
.amp-wp-footer h2 {
	font-size: 1em;
	line-height: 1.375em;
	margin: 0 0 .5em;
}
.amp-wp-footer .back-to-top {
	font-size: 11px;
	text-transform: uppercase;
	letter-spacing: 1px;
}
.amp-wp-footer p {
	font-size: 12px;
	line-height: 1.5em;
	margin: 1em 2em .6em;
}
.amp-wp-footer a {
	color: #53585c;
	text-decoration: none;
}
.amp-wp-social-footer a:not(:last-child) {
	margin-right: 0.8em;
}

/* AMP Ads */
.amp_ad_wrapper {
	text-align: center;
}

/* AMP Sidebar */
.toggle_btn,
.amp-wp-header .jeg_search_toggle {
	color: #212121;
	background: transparent;
	font-size: 24px;
	flex: 0 1 50px;
	width: 50px;
	height: <?php echo esc_attr( $mobile_header_height ); ?>px;
	line-height: <?php echo esc_attr( $mobile_header_height ); ?>px;
	text-align: center;
	border: none;
	padding: 0;
	outline: 0;
}

.dark .toggle_btn,
.dark.amp-wp-header .jeg_search_toggle {
	color: #fff;
}

<?php if ( $mobile_heading_btn_color ) : ?>
	.toggle_btn,
	.amp-wp-header .jeg_search_toggle,
	.dark .toggle_btn,
	.dark.amp-wp-header .jeg_search_toggle {
		color: <?php echo esc_attr( $mobile_heading_btn_color ); ?>;
	}
<?php endif ?>

.amp-wp-header .jeg_search_toggle {
	left: auto;
	right: 0;
}
#sidebar {
	background-color: <?php echo esc_attr( $mobile_drawer_bg_color ); ?>;
	width: 100%;
	max-width: 320px;
}
#sidebar > div:nth-child(3) {
	display: none;
}
.jeg_mobile_wrapper {
	height: 100%;
	overflow-x: hidden;
	overflow-y: auto;
}
.jeg_mobile_wrapper .nav_wrap {
	min-height: 100%;
	display: flex;
	flex-direction: column;
	position: relative;
}
.jeg_mobile_wrapper .nav_wrap:before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	min-height: 100%;
	z-index: -1;
}
.jeg_mobile_wrapper .item_main {
	flex: 1;
}
.jeg_mobile_wrapper .item_bottom {
	-webkit-box-pack: end;
	-ms-flex-pack: end;
	justify-content: flex-end;
}
.jeg_aside_item {
	display: block;
	padding: 20px;
	border-bottom: 1px solid #eee;
}
.item_bottom .jeg_aside_item {
	padding: 10px 20px;
	border-bottom: 0;
}
.item_bottom .jeg_aside_item:first-child {
	padding-top: 20px;
}
.item_bottom .jeg_aside_item:last-child {
	padding-bottom: 20px;
}
.jeg_aside_item:last-child {
	border-bottom: 0;
}
.jeg_aside_item:after {
	content: "";
	display: table;
	clear: both;
}
<?php if ( ! empty( $mobile_drawer_bg_image ) ) : ?>
	.jeg_mobile_wrapper {
		background-image: url(<?php echo esc_url( $mobile_drawer_bg_image ); ?>);
		background-attachment: <?php echo esc_attr( $mobile_drawer_bg_image_fixed ); ?>;
		background-size: <?php echo esc_attr( $mobile_drawer_bg_image_size ); ?>;
		background-repeat: <?php echo esc_attr( $mobile_drawer_bg_image_repeat ); ?>;
		background-position: <?php echo esc_attr( $mobile_drawer_bg_image_posistion ); ?>;
	}
<?php endif ?>

<?php if ( $mobile_drawer_overlay_color ) : ?>
	.jeg_mobile_wrapper .nav_wrap {
		background: <?php echo esc_attr( $mobile_drawer_overlay_color ); ?>;
	}
<?php endif ?>

/* Mobile Aside Widget */
.jeg_mobile_wrapper .widget {
	display: block;
	padding: 20px;
	margin-bottom: 0;
	border-bottom: 1px solid #eee;
}
.jeg_mobile_wrapper .widget:last-child {
	border-bottom: 0;
}

.jeg_mobile_wrapper .widget .jeg_ad_module {
	margin-bottom: 0;
}

/* Mobile Menu Account */
.jeg_aside_item.jeg_mobile_profile {
	display: none;
}

/* Mobile Menu */

.jeg_navbar_mobile_wrapper {
	position: relative;
	z-index: 9;
}
.jeg_mobile_menu li a {
	color: #212121;
	margin-bottom: 15px;
	display: block;
	font-size: 18px;
	line-height: 1.444em;
	font-weight: bold;
	position: relative;
}
.jeg_mobile_menu li.sfHover > a, .jeg_mobile_menu li a:hover {
	color: #f70d28;
}
.jeg_mobile_menu,
.jeg_mobile_menu ul {
	list-style: none;
	margin: 0px;
}
.jeg_mobile_menu ul {
	padding-bottom: 10px;
	padding-left: 20px;
}
.jeg_mobile_menu ul li a {
	color: #757575;
	font-size: 15px;
	font-weight: normal;
	margin-bottom: 12px;
	padding-bottom: 5px;
	border-bottom: 1px solid #eee;
}
.jeg_mobile_menu.sf-arrows .sfHover .sf-with-ul:after {
	-webkit-transform: rotate(90deg);
	transform: rotate(90deg);
}

/** Mobile Socials **/
.jeg_mobile_wrapper .socials_widget {
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
}
.jeg_mobile_wrapper .socials_widget a {
	margin-bottom: 0;
	display: block;
}

/* Mobile: Social Icon */
.jeg_mobile_topbar .jeg_social_icon_block.nobg a {
	margin-right: 10px;
}
.jeg_mobile_topbar .jeg_social_icon_block.nobg a .fa {
	font-size: 14px;
}
<?php if ( $mobile_drawer_social_icon_color ) : ?>
	.jeg_aside_item.socials_widget > a > i.fa:before {
		color: <?php echo esc_attr( $mobile_drawer_social_icon_color ); ?>;
	}
<?php endif ?>

/* Mobile Search */
.jeg_navbar_mobile .jeg_search_wrapper {
	position: static;
}
.jeg_navbar_mobile .jeg_search_popup_expand {
	float: none;
}
.jeg_search_expanded .jeg_search_popup_expand .jeg_search_toggle {
	position: relative;
}
.jeg_navbar_mobile .jeg_search_expanded .jeg_search_popup_expand .jeg_search_toggle:before {
	border-color: transparent transparent #fff;
	border-style: solid;
	border-width: 0 8px 8px;
	content: "";
	right: 0;
	position: absolute;
	bottom: -1px;
	z-index: 98;
}
.jeg_navbar_mobile .jeg_search_expanded .jeg_search_popup_expand .jeg_search_toggle:after {
	border-color: transparent transparent #eee;
	border-style: solid;
	border-width: 0 9px 9px;
	content: "";
	right: -1px;
	position: absolute;
	bottom: 0px;
	z-index: 97;
}
.jeg_navbar_mobile .jeg_search_popup_expand .jeg_search_form:before,
.jeg_navbar_mobile .jeg_search_popup_expand .jeg_search_form:after {
	display: none;
}
.jeg_navbar_mobile .jeg_search_popup_expand .jeg_search_form {
	width: auto;
	border-left: 0;
	border-right: 0;
	left: -15px;
	right: -15px;
	padding: 20px;
	-webkit-transform: none;
	transform: none;
}
.jeg_navbar_mobile .jeg_search_popup_expand .jeg_search_result {
	margin-top: 84px;
	width: auto;
	left: -15px;
	right: -15px;
	border: 0;
}
.jeg_navbar_mobile .jeg_search_form .jeg_search_button {
	font-size: 18px;
}
.jeg_navbar_mobile .jeg_search_wrapper .jeg_search_input {
	font-size: 18px;
	padding: .5em 40px .5em 15px;
	height: 42px;
}
.jeg_navbar_mobile .jeg_nav_left .jeg_search_popup_expand .jeg_search_form:before {
	right: auto;
	left: 16px;
}
.jeg_navbar_mobile .jeg_nav_left .jeg_search_popup_expand .jeg_search_form:after {
	right: auto;
	left: 15px;
}
.jeg_search_wrapper .jeg_search_input {
	width: 100%;
	vertical-align: middle;
	height: 40px;
	padding: 0.5em 30px 0.5em 14px;
	box-sizing: border-box;
}
.jeg_mobile_wrapper .jeg_search_result {
	width: 100%;
	border-left: 0;
	border-right: 0;
	right: 0;
}
.admin-bar .jeg_mobile_wrapper {
	padding-top: 32px;
}
.admin-bar .jeg_show_menu .jeg_menu_close {
	top: 65px;
}

/* Mobile Copyright */
.jeg_aside_copyright {
	font-size: 11px;
	color: #757575;
	letter-spacing: .5px;
}
.jeg_aside_copyright a {
	color: inherit;
	border-bottom: 1px solid #aaa;
}
.jeg_aside_copyright p {
	margin-bottom: 1.2em;
}
.jeg_aside_copyright p:last-child {
	margin-bottom: 0;
}

/* Social Icon */
.socials_widget a {
	display: inline-block;
	margin: 0 10px 10px 0;
	text-decoration: none;
}
.socials_widget.nobg a {
	margin: 0 20px 15px 0;
}
.socials_widget a:last-child {
	margin-right: 0
}
.socials_widget.nobg a .fa {
	font-size: 18px;
	width: auto;
	height: auto;
	line-height: inherit;
	background: transparent;
}
.socials_widget a .fa {
	font-size: 1em;
	display: inline-block;
	width: 38px;
	line-height: 36px;
	white-space: nowrap;
	color: #fff;
	text-align: center;
	-webkit-transition: all 0.2s ease-in-out;
	-o-transition: all 0.2s ease-in-out;
	transition: all 0.2s ease-in-out;
}
.socials_widget.circle a .fa {
	border-radius: 100%;
}

/* Social Color */
.socials_widget .jeg_rss .fa {
	background: #ff6f00;
}
.socials_widget .jeg_facebook .fa {
	background: #45629f;
}
.socials_widget .jeg_twitter .fa {
	background: #5eb2ef;
}
.socials_widget .jeg_google-plus .fa {
	background: #df5443;
}
.socials_widget .jeg_linkedin .fa {
	background: #0083bb;
}
.socials_widget .jeg_instagram .fa {
	background: #125d8f;
}
.socials_widget .jeg_pinterest .fa {
	background: #e02647;
}
.socials_widget .jeg_behance .fa {
	background: #1e72ff;
}
.socials_widget .jeg_dribbble .fa {
	background: #eb5590;
}
.socials_widget .jeg_reddit .fa {
	background: #5f99cf;
}
.socials_widget .jeg_stumbleupon .fa {
	background: #ff4e2e;
}
.socials_widget .jeg_vimeo .fa {
	background: #a1d048;
}
.socials_widget .jeg_github .fa {
	background: #313131;
}
.socials_widget .jeg_flickr .fa {
	background: #ff0077;
}
.socials_widget .jeg_tumblr .fa {
	background: #2d4862;
}
.socials_widget .jeg_soundcloud .fa {
	background: #ffae00;
}
.socials_widget .jeg_youtube .fa {
	background: #c61d23;
}
.socials_widget .jeg_twitch .fa {
	background: #6441a5;
}
.socials_widget .jeg_vk .fa {
	background: #3e5c82;
}
.socials_widget .jeg_weibo .fa {
	background: #ae2c00;
}

/* Social Color No Background*/
.socials_widget.nobg .jeg_rss .fa {
	color: #ff6f00;
}
.socials_widget.nobg .jeg_facebook .fa {
	color: #45629f;
}
.socials_widget.nobg .jeg_twitter .fa {
	color: #5eb2ef;
}
.socials_widget.nobg .jeg_google-plus .fa {
	color: #df5443;
}
.socials_widget.nobg .jeg_linkedin .fa {
	color: #0083bb;
}
.socials_widget.nobg .jeg_instagram .fa {
	color: #125d8f;
}
.socials_widget.nobg .jeg_pinterest .fa {
	color: #e02647;
}
.socials_widget.nobg .jeg_behance .fa {
	color: #1e72ff;
}
.socials_widget.nobg .jeg_dribbble .fa {
	color: #eb5590;
}
.socials_widget.nobg .jeg_reddit .fa {
	color: #5f99cf;
}
.socials_widget.nobg .jeg_stumbleupon .fa {
	color: #ff4e2e;
}
.socials_widget.nobg .jeg_vimeo .fa {
	color: #a1d048;
}
.socials_widget.nobg .jeg_github .fa {
	color: #313131;
}
.socials_widget.nobg .jeg_flickr .fa {
	color: #ff0077;
}
.socials_widget.nobg .jeg_tumblr .fa {
	color: #2d4862;
}
.socials_widget.nobg .jeg_soundcloud .fa {
	color: #ffae00;
}
.socials_widget.nobg .jeg_youtube .fa {
	color: #c61d23;
}
.socials_widget.nobg .jeg_twitch .fa {
	color: #6441a5;
}
.socials_widget.nobg .jeg_vk .fa {
	color: #3e5c82;
}
.socials_widget.nobg .jeg_weibo .fa {
	color: #ae2c00;
}

/** Mobile Search Form **/
.jeg_search_wrapper {
	position: relative;
}
.jeg_search_wrapper .jeg_search_form {
	display: block;
	position: relative;
	line-height: normal;
	min-width: 60%;
}
.jeg_search_no_expand .jeg_search_toggle {
	display: none;
}
.jeg_mobile_wrapper .jeg_search_result {
	width: 100%;
	border-left: 0;
	border-right: 0;
	right: 0;
}
.jeg_search_hide {
	display: none;
}
.jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input {
	box-shadow: inset 0 2px 2px rgba(0,0,0,.05);
}
.jeg_mobile_wrapper .jeg_search_result {
	width: 100%;
	border-left: 0;
	border-right: 0;
	right: 0;
}
.jeg_search_no_expand.round .jeg_search_input {
	border-radius: 33px;
	padding: .5em 15px;
}
.jeg_search_no_expand.round .jeg_search_button {
	padding-right: 12px;
}
input:not([type="submit"]) {
	display: inline-block;
	background: #fff;
	border: 1px solid #e0e0e0;
	border-radius: 0;
	padding: 7px 14px;
	height: 40px;
	outline: none;
	font-size: 14px;
	font-weight: 300;
	margin: 0;
	width: 100%;
	max-width: 100%;
	-webkit-transition: all 0.2s ease;
	transition: .25s ease;
	box-shadow: none;
}
input[type="submit"], .btn {
	border: none;
	background: #f70d28;
	color: #fff;
	padding: 0 20px;
	line-height: 40px;
	height: 40px;
	display: inline-block;
	cursor: pointer;
	text-transform: uppercase;
	font-size: 13px;
	font-weight: bold;
	letter-spacing: 2px;
	outline: 0;
	-webkit-appearance: none;
	-webkit-transition: .3s ease;
	transition: .3s ease;
}
.jeg_search_wrapper .jeg_search_button {
	color: #212121;
	background: transparent;
	border: 0;
	font-size: 14px;
	outline: none;
	cursor: pointer;
	position: absolute;
	height: auto;
	min-height: unset;
	top: 0;
	bottom: 0;
	right: 0;
	padding: 0 10px;
	transition: none;
}

/** Mobile Dark Scheme **/
.dark .jeg_bg_overlay {
	background: #fff;
}
.dark .jeg_mobile_wrapper {
	background-color: #212121;
	color: #f5f5f5;
}
.dark .jeg_mobile_wrapper .jeg_search_result {
	background: rgba(0, 0, 0, .9);
	color: #f5f5f5;
	border: 0;
}
.dark .jeg_menu_close {
	color: #212121;
}
.dark .jeg_aside_copyright,
.dark .profile_box a,
.dark .jeg_mobile_menu li a,
.dark .jeg_mobile_wrapper .jeg_search_result a,
.dark .jeg_mobile_wrapper .jeg_search_result .search-link {
	color: #f5f5f5;
}
.dark .jeg_aside_copyright a {
	border-color: rgba(255, 255, 255, .8)
}
.dark .jeg_aside_item,
.dark .jeg_mobile_menu ul li a,
.dark .jeg_search_result.with_result .search-all-button {
	border-color: rgba(255, 255, 255, .15);
}

.dark .profile_links li a,
.dark .jeg_mobile_menu.sf-arrows .sf-with-ul:after,
.dark .jeg_mobile_menu ul li a {
	color: rgba(255, 255, 255, .5)
}

.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input {
	background: rgba(255, 255, 255, 0.1);
	border: 0;
}
.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_button,
.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input {
	color: #fafafa;
}
.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input::-webkit-input-placeholder {
	color: rgba(255, 255, 255, 0.75);
}
.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input:-moz-placeholder {
	color: rgba(255, 255, 255, 0.75);
}
.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input::-moz-placeholder {
	color: rgba(255, 255, 255, 0.75);
}
.dark .jeg_mobile_wrapper .jeg_search_no_expand .jeg_search_input:-ms-input-placeholder {
	color: rgba(255, 255, 255, 0.75);
}

/* RTL */
.rtl .socials_widget.nobg a {
	margin: 0 0 15px 20px;
}
.rtl .amp-wp-social-footer a:not(:last-child) {
	margin-left: 0.8em;
	margin-right: 0;
}
.rtl .jeg_search_no_expand.round .jeg_search_input {
	padding: 15px 2.5em 15px .5em;
}
.rtl .jeg_share_button a {
	margin: 0 0px 5px 5px;
}
.rtl .jeg_share_button a:last-child {
	margin-left: 0;
}
.rtl blockquote {
	padding-left: 0;
	padding-right: 1em;
	border-left: 0;
	border-right-width: 4px;
	border-right: 4px solid #eee;
}
.rtl .jeg_reviewstars .reviewscore,
.rtl .jeg_storelist li .priceinfo,
.rtl .jeg_reviewbars .reviewscore {
	float: left;
}
.rtl .jeg_reviewdetail li {
	padding-right: 22px;
	padding-left: unset;
}
.rtl .jeg_reviewdetail li > i {
	right: 0;
	left: unset;
}
.rtl .dealstitle {
	float: right;
}
.rtl .bestprice {
	float: left;
	text-align: left;
}
/* Font Awesome Right to Left */
/* Horizontal Flip */
.rtl .fa-star-half-o {
	-moz-transform: scale(-1, 1);
	-webkit-transform: scale(-1, 1);
	-o-transform: scale(-1, 1);
	-ms-transform: scale(-1, 1);
	transform: scale(-1, 1);
}
.rtl .jeg_reviewbars .barbg:after {
	left: 0;
	right: unset;
}
.rtl .amp-carousel-button-next {
	left: 16px;
	right: unset;
	-webkit-transform: scaleX(-1);
	transform: scaleX(-1);

}

.rtl .amp-carousel-button-prev {
	right: 16px;
	left: unset;
	-webkit-transform: scaleX(-1) translateY(-50%);
	transform: scaleX(-1) translateY(-50%);
}
amp-consent {
	position: relative;
}
.gdpr-consent {
	bottom: 0;
	left: 0;
	overflow: hiddent;
	z-index: 1000;
	width: 100%;
	position: fixed;
}
.gdpr-consent-wrapper {
	text-align: center;
	padding: 15px 10px;
	background: #f5f5f5;
	position: relative;
}
.gdpr-consent-content h2 {
	margin: 10px 0;
}
.gdpr-consent-close {
	position: absolute;
	top: 10px;
	right: 10px;
	font-size: 24px;
	border:none;
}
.btn.gdpr-consent-button-y {
	background: #ddd;
	color: #212121;
	margin-right:10px;
}
#post-consent-ui {
	text-align: center;
}
#post-consent-ui a {
	background: none;
	color: #666;
	text-decoration: underline;
	font-weight: normal;
	font-size: 12px;
	letter-spacing: normal;
}

/* Responsive */
@media screen and (max-width: 782px) {
	.admin-bar .jeg_mobile_wrapper {
		padding-top: 46px;
	}
}
@media only screen and (max-width: 320px) {
	#sidebar {
		max-width: 275px;
	}
}

/* Paywall button */
.jpw-truncate-btn {
	height: fit-content;
	left: 0;
	position: relative;
	right: 0;
	text-align: center;
	margin-bottom: 40px;
}

.jpw-truncate-btn:before {
	background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
	content: "";
	height: 200px;
	margin-top: -200px;
	left: 0;
	position: absolute;
	right: 0;
	width: 100%;
}

.jpw-truncate-btn span {
	display: block;
	font-size: 14px;
	padding: 0 5px;
}

.jpw-truncate-btn .jpw_subscribe span,
.jpw-truncate-btn .jpw_unlock span {
	min-height:65px;
}

.jpw-truncate-btn h3 {
	font-size: 1.5em;
	margin: 0.5em 0 1em;
}

.jpw-truncate-btn .jpw-truncate-header {
	padding: 30px 10px;
}

.jpw-truncate-btn .jpw_subscribe,
.jpw-truncate-btn .jpw_unlock { 
	border: 1px solid #ededed;
	display: inline-block;
	width: 40%;
	max-width: 400px;
	margin: 5px 10px;
	padding: 20px;
	vertical-align: top;
}

.jpw-truncate-btn .button {
	background-color: #555;
	border-radius: 100px;
	display: inline-block;
	height: fit-content;
	margin: 5px 15px;
	padding: 0;
	width: fit-content;
}

.jpw-truncate-btn .button a {
	color: #fff;
	display: block;
	padding: 5px 20px;
	text-decoration: none;
}

.jpw-truncate-btn .button input {
	color: #fff;
	display: block;
	padding: 0 20px;
	text-decoration: none;
}

.jpw-truncate.jpw-no-comment div#respond {
	display: none;
}

@media only screen and (max-width: 567px) {
	.jpw-truncate-btn .jpw_subscribe,
	.jpw-truncate-btn .jpw_unlock { 
		display: block;
		margin: 10px auto;
		width: 90%;
	}
}
