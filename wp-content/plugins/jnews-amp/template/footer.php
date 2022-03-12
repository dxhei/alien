<footer class="amp-wp-footer">
	<div class="amp-wp-footer-inner">
		<a href="#top" class="back-to-top"><?php jnews_print_translation( 'Back to top', 'jnews-amp', 'back_to_top' ); ?></a>

		<p class="copyright">
			<?php
				$copyright = get_theme_mod( 'jnews_footer_copyright', jnews_get_footer_copyright_text() );
				echo wp_kses( do_shortcode( $copyright ), wp_kses_allowed_html() );
			?>
		</p>
		
		<div class="amp-wp-social-footer">
			<?php jnews_generate_social_icon_block(); ?>
		</div>
	</div>
</footer>
