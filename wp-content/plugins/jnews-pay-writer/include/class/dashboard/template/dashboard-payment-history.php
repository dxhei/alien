<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wrap jpwt_stats">
	<h2><?php echo esc_html( apply_filters( 'jpwt_admin_menu_name', 'Pay Writer' ) ) . ' - ' . esc_html__( 'Stats', 'jnews-pay-writer' ); ?></h2>
	<div id="jpwt_stats_header_text">
		<h3>
		<?php
			$current_page = __( 'General', 'jnews-pay-writer' );
			$current_page = isset( JNews_Pay_Writer()->dashboard->author_name ) ? JNews_Pay_Writer()->dashboard->author_name : $current_page;
			echo __( 'Showing Payment History for', 'jnews-pay-writer' ) . ' - "' . $current_page . '<br>';
		?>
		</h3>
	</div>
	<?php
		JNews_Pay_Writer()->dashboard->show_history();
	?>
</div>
