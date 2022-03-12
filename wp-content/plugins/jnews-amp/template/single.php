<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">

<?php do_action( 'jnews_amp_before_header' ); ?>

<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php $this->load_parts( array( 'sidebar-menu' ) ); ?>

<?php do_action( 'jnews_amp_before_article' ); ?>

<article class="amp-wp-article">

	<div class="amp-wp-breadcrumb">
		<?php echo jnews_render_breadcrumb(); ?>
	</div>

	<header class="amp-wp-article-header">
		<h1 class="amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h1>
		<?php if ( ! empty( $subtitle = wp_kses( get_post_meta( get_the_ID(), 'post_subtitle', true ), wp_kses_allowed_html() ) ) ) : ?>
			<h2 class="amp-wp-subtitle"><?php echo esc_html( $subtitle ); ?></h2>
		<?php endif; ?>
		<ul class="amp-wp-meta">
			<?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( 'meta-author', 'meta-time' ) ) ); ?>
		</ul>
	</header>

	<?php $this->load_parts( array( 'featured-image' ) ); ?>

	<div class="amp-wp-share">
		<?php do_action( 'jnews_share_amp_bar', get_the_ID() ); ?>
	</div>

	<?php do_action( 'jnews_amp_before_content' ); ?>

	<div class="amp-wp-article-content">
		<?php echo jnews_sanitize_output( $this->get( 'post_amp_content' ) ); ?>
	</div>
	
	<ul class="amp-wp-meta-taxonomy"><?php $this->load_parts( array( 'meta-taxonomy' ) ); ?></ul>

	<?php do_action( 'jnews_amp_after_content' ); ?>

</article>

<?php do_action( 'jnews_amp_after_article' ); ?>

<?php $this->load_parts( array( 'footer' ) ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>

</body>
</html>
