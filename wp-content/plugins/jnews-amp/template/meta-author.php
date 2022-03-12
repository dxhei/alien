<?php $post_author = $this->get( 'post_author' ); ?>
<li class="amp-wp-byline">
	<?php if ( function_exists( 'get_avatar_url' ) ) : ?>
	<amp-img src="
		<?php
		echo esc_url(
			get_avatar_url(
				$post_author->user_email,
				array(
					'size' => 24,
				)
			)
		);
		?>" width="24" height="24" layout="fixed"></amp-img>
	<?php endif; ?>
	<span class="amp-wp-author"><?php echo jnews_return_translation( 'By', 'jnews-amp', 'by' ); ?> <a href="<?php echo esc_url( get_author_posts_url( $post_author->ID ) ); ?>"><?php echo esc_html( $post_author->display_name ); ?></a></span>
</li>
