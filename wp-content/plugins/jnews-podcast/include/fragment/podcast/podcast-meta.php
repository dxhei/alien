<?php

use JNEWS_PODCAST\Series\Single_Series;

$single = Single_Series::get_instance();
if ( $single->show_author_meta() || $single->show_total_episode_meta() ) {
	$meta   = jnews_podcast_attribute(
		$single->get_series_id(),
		array(
			'fields' => array(
				'author',
				'count_series',
			),
		)
	);
	$author = $meta['author'];
}
?>
<div class="jeg_post_meta jeg_post_meta_1">

	<div class="meta_left">
		<?php if ( $single->show_author_meta() ) : ?>
			<div class="jeg_meta_author">
				<?php
				if ( $single->show_author_meta_image() ) {
					echo get_avatar( get_the_author_meta( 'ID', $author ), 80, null, get_the_author_meta( 'display_name', $author ) );
				}
				?>
				<span
					class="meta_text"><?php jnews_print_translation( 'Created by', 'jnews-podcast', 'created_by' ); ?></span>
				<?php jnews_the_author_link( $author ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $single->show_total_episode_meta() ) : ?>
			<div class="jeg_meta_total_episode">
				<a href="<?php the_permalink(); ?>"><?php echo $meta['count_series']; ?> <?php echo jnews_return_translation( 'Episodes', 'jnews-podcast', 'episodes' ); ?></a>
			</div>
		<?php endif; ?>

	</div>
</div>
