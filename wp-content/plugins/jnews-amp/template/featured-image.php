<?php
$format         = get_post_format( get_the_ID() );
$featured_image = $this->get( 'featured_image' );

if ( $format === 'gallery' ) {
	$images        = get_post_meta( get_the_ID(), '_format_gallery_images', true );
	$carousel_html = '';

	foreach ( $images as $image_id ) {
		list( $url, $width, $height ) = wp_get_attachment_image_src( $image_id, 'jnews-featured-750', true );
		if ( ! $url ) {
			continue;
		}
		$carousel_html .= "<amp-img src=\"{$url}\" width=\"{$width}\" height=\"{$height}\" layout=\"responsive\"></amp-img>";
	}

	?>
	<amp-carousel width="600" height="480" type="slides" layout="responsive">
		<?php echo jnews_sanitize_by_pass( $carousel_html ); ?>
	</amp-carousel>
	<?php
} elseif ( $format === 'video' ) {
	$video_url    = get_post_meta( get_the_ID(), '_format_video_embed', true );
	$video_format = strtolower( pathinfo( $video_url, PATHINFO_EXTENSION ) );

	if ( jnews_check_video_type( $video_url ) === 'youtube' ) {
		$youtube_id = jnews_get_youtube_vimeo_id( $video_url );
		?>
		<p><amp-youtube data-videoid="<?php echo esc_attr( $youtube_id ); ?>" layout="responsive" width="600" height="338"></amp-youtube></p>
		<?php
	} elseif ( $video_format == 'mp4' ) {
		$video_url = jnews_remove_protocol( $video_url );
		?>
		<div class="wp-video">
			<!--[if lt IE 9]><script>document.createElement('video');</script><![endif]-->
			<amp-video class="wp-video-shortcode amp-wp-enforced-sizes" width="640" height="360" controls="" sizes="(min-width: 600px) 600px, 100vw"><source type="video/mp4" src="<?php echo esc_url( $video_url ); ?>"/></amp-video>
		</div>
		<?php
	}
} else {
	if ( empty( $featured_image ) ) {
		return;
	}
	$amp_html = $featured_image['amp_html'];
	$caption  = $featured_image['caption'];
	?>
	<figure class="amp-wp-article-featured-image wp-caption">
		<?php echo jnews_sanitize_by_pass( $amp_html ); // amphtml content; no kses ?>
		<?php if ( $caption ) : ?>
			<p class="wp-caption-text">
				<?php echo wp_kses_data( $caption ); ?>
			</p>
		<?php endif; ?>
	</figure>
	<?php
}
