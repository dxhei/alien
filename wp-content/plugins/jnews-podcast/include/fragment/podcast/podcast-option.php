<?php

use JNEWS_PODCAST\Series\Single_Series;

$single = Single_Series::get_instance();
?>
<div class="jeg_post_option">
	<?php if ( $single->show_subscribe_button() ) : ?>
		<div class="follow-wrapper"><a href="<?php echo jnews_podcast_feed_link( $single->get_series_id(), $single->get_slug() ); ?>"><i class="fa fa-rss"></i><span><?php echo jnews_print_translation( 'Subscribe', 'jnews-podcast', 'subscribe_podcast' ); ?></span></a></div>
	<?php endif; ?>
	<?php if ( $single->show_share_button() ) : ?>
		<?php echo jnews_podcast_share( $single->get_series_id() ); ?>
	<?php endif; ?>
</div>
