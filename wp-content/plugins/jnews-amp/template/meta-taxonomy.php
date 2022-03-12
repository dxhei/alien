<?php $categories = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'jnews-amp' ) ); ?>
<?php if ( $categories ) : ?>
	<li class="amp-wp-tax-category">
		<span class="screen-reader-text"><?php echo esc_html__( 'Categories:', 'jnews-amp' ); ?></span>
		<?php echo jnews_sanitize_output( $categories ); ?>
	</li>
<?php endif; ?>

<?php $tags = get_the_tag_list( '', _x( '', 'Used between list items, there is a space after the comma.', 'jnews-amp' ) ); ?>
<?php if ( $tags && ! is_wp_error( $tags ) ) : ?>
	<li class="amp-wp-tax-tag">
		<span class="screen-reader-text"><?php echo esc_html__( 'Tags:', 'jnews-amp' ); ?></span>
		<?php echo jnews_sanitize_output( $tags ); ?>
	</li>
<?php endif; ?>
