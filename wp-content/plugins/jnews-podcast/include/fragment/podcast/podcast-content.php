<?php
// we create class instance outside loop, so we need to take wp query instance directly
use JNEWS_PODCAST\Series\Single_Series;

$single = Single_Series::get_instance();
?>

<div class="jeg_sidebar col-md-<?php echo '1' === $single->get_template() ? '4' : '12'; ?>">
	<div class="jeg_inner_content">
		<?php echo jnews_sanitize_output( $single->featured_image( 'medium' ) ); ?>
		<div class="entry-header">
			<h1 class="jeg_post_title">
				<?php
				the_archive_title();
				?>
			</h1>
			<?php the_archive_description(); ?>
			<div class="jeg_meta_container"><?php $single->render_post_meta(); ?></div>
			<div class="jeg_option_container"><?php $single->render_post_option(); ?></div>
		</div>
	</div>
</div>
<div class="jeg_main_content col-md-<?php echo '1' === $single->get_template() ? '8' : '12'; ?>">
	<div class="jeg_series_contents">
		<h3 class="post-title"><?php echo jnews_return_translation( 'List Episodes', 'jnews-podcast', 'list_episodes' ); ?></h3>
		<?php echo jnews_sanitize_output( $single->render_content() ); ?>
	</div>
</div>
