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
			$author       = __( 'General', 'jnews-pay-writer' );
			$author       = isset( JNews_Pay_Writer()->dashboard->author_name ) ? JNews_Pay_Writer()->dashboard->author_name : $author;
			$current_page = JNews_Pay_Writer()->dashboard->history ? 'payment history' : 'posts';

			echo __( 'Showing ' . $current_page . ' for', 'jnews-pay-writer' ) . ' - "' . $author . '"<br>';
		?>
		</h3>
	</div>
	<?php
	if ( ! JNews_Pay_Writer()->dashboard->history ) {
		JNews_Pay_Writer()->dashboard->show_stats();
	} else {
		JNews_Pay_Writer()->dashboard->show_history();
	}
	?>
</div>
