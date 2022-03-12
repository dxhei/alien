<?php
/**
 * @author : Jegtheme
 */

use JNEWS_PODCAST\Series\Category_Series_Archive;

get_header();
$archive = new Category_Series_Archive();
?>

<div class="jeg_main <?php $archive->main_class(); ?>">
	<div class="jeg_container">
		<div class="jeg_content">

			<div class="jeg_section">
				<div class="container">

					<?php do_action( 'jnews_archive_above_content' ); ?>

					<div class="jeg_cat_content row">
						<div
								class="jeg_main_content col-sm-<?php echo esc_attr( $archive->get_content_width() ); ?>">

							<div class="jeg_inner_content">

								<div class="jeg_archive_header">

									<h1 class="jeg_archive_title"><?php echo jnews_sanitize_output( $archive->get_page_title() ); ?></h1>

								</div>

								<div class="jnews_podcast_content_wrapper">
									<?php echo jnews_sanitize_output( $archive->render_content() ); ?>
								</div>
							</div>
						</div>
						<?php $archive->render_sidebar(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php do_action( 'jnews_after_main' ); ?>
	</div>
</div>

<?php get_footer(); ?>
