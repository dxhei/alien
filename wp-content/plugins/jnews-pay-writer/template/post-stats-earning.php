<?php
if ( isset( $status ) && 'ok' === $status ) {
	$posts = $data['result'];
	if ( 0 < count( $posts ) ) {
		foreach ( $posts as $post ) {
			$post_status   = get_post_status_object( get_post_status( $post ) )->label;
			$total_earning = $post->jpwt_payment['total'];
			$view_count    = $post->jpwt_count['view'];
			do_action( 'jnews_json_archive_push', $post->ID );
			?>
			<article <?php post_class( 'jeg_post jeg_pl_sm' ); ?>>
				<div class="jeg_thumb">
					<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo apply_filters( 'jnews_image_thumbnail', $post->ID, 'jnews-120x86' ); ?></a>
				</div>
				<div class="jeg_postblock_content">
					<h3 class="jeg_post_title">
						<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
					</h3>
					<div class="jeg_post_meta">
						<div class="jeg_post_status <?php echo esc_attr( $post_status ); ?>"><?php echo esc_html( $post_status ); ?></div><span>–</span>
						<div class="jeg_meta_date"><a href="<?php echo get_the_permalink( $post ); ?>"><?php echo esc_html( jeg_get_post_date( '', $post ) ); ?></a></div>
						<div class="jeg_meta_views"><a href="<?php echo get_the_permalink( $post ); ?>"><i class="fa fa-eye"></i> <?php echo jnews_sanitize_output( $view_count ); ?> </a></div>
						<div class="jeg_meta_earning"><a href="<?php echo get_the_permalink( $post ); ?>"><i class="jpwt-icon jpwt-pay"></i> <?php echo \JNews\PAY_WRITER\Dashboard\Generate_Stats::format_payment( $total_earning ); ?> </a></div>
					</div>
					<div class="jeg_post_control">
						<a class="jeg_post_action edit" href="<?php echo get_the_permalink( $post ); ?>"><?php esc_html_e( 'View Post', 'jnews-pay-writer' ); ?></a>
					</div>
				</div>
			</article>
			<?php
		}
	} else {
		?>
			<p style="text-align: center;"><?php _e( "Looks like your site's activity is a little low right now.<br>Spread the word and come back later!", 'jnews-pay-writer' ); ?></p>
		<?php
	}
}

