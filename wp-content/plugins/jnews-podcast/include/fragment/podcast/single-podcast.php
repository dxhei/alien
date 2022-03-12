<?php

use JNEWS_PODCAST\Series\Single_Series;

$single   = Single_Series::get_instance();
$identity = isset( $wp_query->post ) ? $wp_query->post->ID : $wp_query->query['jnews-series'];
get_header();
?>
<div class="post-wrapper single-series">

	<div class="post-wrap">


		<?php do_action( 'jnews_single_post_begin', $identity ); ?>

		<div class="jeg_main <?php $single->main_class(); ?> <?php $single->template_class(); ?>">
			<div class="jeg_container">
				<div class="jeg_content  jeg_singlepage">
					<div class="container">

						<div class="jeg_ad jeg_article jnews_article_top_ads">
							<?php do_action( 'jnews_article_top_ads' ); ?>
						</div>

						<div class="row">
							<?php
							jnews_podcast_get_template_part( '/include/fragment/podcast/podcast', 'content' );
							?>
						</div>

						<div class="jeg_ad jeg_article jnews_article_bottom_ads">
							<?php do_action( 'jnews_article_bottom_ads' ); ?>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php do_action( 'jnews_single_post_end', $identity ); ?>

	</div>

	<?php get_template_part( 'fragment/post/post-overlay' ); ?>

</div>
<?php get_footer(); ?>
