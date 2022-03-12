<?php $mobile_drawer_style = get_theme_mod( 'jnews_header_mobile_drawer_scheme', 'normal' ); ?>

<amp-sidebar id="sidebar" layout="nodisplay" side="left" class="<?php echo esc_attr( $mobile_drawer_style ); ?>">
	<div class="jeg_mobile_wrapper">
		<?php do_action( 'jnews_mobile_menu_cotent' ); ?>
	</div>
</amp-sidebar>
