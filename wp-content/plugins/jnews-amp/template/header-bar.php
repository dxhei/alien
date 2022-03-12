<?php $mobile_heading_style = get_theme_mod( 'jnews_header_mobile_midbar_scheme', 'dark' ); ?>

<header id="#top" class="amp-wp-header <?php echo esc_attr( $mobile_heading_style ); ?>">
	<div>
		<button on="tap:sidebar.toggle" class="toggle_btn">
			<i class="fa fa-bars"></i>
		</button>
		<a class="jeg_mobile_logo" href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>"></a>
		<a class="jeg_search_toggle" href="<?php echo esc_url( $this->get( 'home_url' ) . '?s=' ); ?>">
			<i class="fa fa-search"></i>
		</a>
	</div>
</header>
