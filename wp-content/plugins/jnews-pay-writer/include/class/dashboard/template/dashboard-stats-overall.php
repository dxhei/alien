<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$overall_stats = JNews\PAY_WRITER\Dashboard\Generate_Stats::get_overall_stats( JNews_Pay_Writer()->dashboard->stats['raw_stats'] );
?>

<div class="card">
	<h2 class="title"><?php esc_html_e( 'Summary', 'jnews-pay-writer' ); ?></h2>
	<table class="widefat fixed">
		<tr>
			<td width="60%"><?php _e( 'Total displayed posts :', 'jnews-pay-writer' ); ?></td>
			<td align="left" width="40%"><?php echo $overall_stats['posts']; ?></td>
		</tr>
		<tr>
			<td width="60%"><?php _e( 'Total displayed payment :', 'jnews-pay-writer' ); ?></td>
			<td align="left" width="40%"><?php echo JNews\PAY_WRITER\Dashboard\Generate_Stats::format_payment( $overall_stats['total_payment'] ); ?></td>
		</tr>
	</table>
</div>
