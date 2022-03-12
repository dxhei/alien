<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="jpwt-payout-overlay-wrapper">
	<form id="jpwt-payout" method="POST">
		<div class="jpwt-payout-overlay main card">
			<div class="jpwt-payout-overlay loading card">
				<span><i class="fa fa-circle-o-notch fa-spin" ></i></span>
			</div>
			<div class="jpwt-payout-overlay-header">
				<span class="dashicons dashicons-warning"></span><h2 class="title"><?php esc_html_e( 'Payout Confirmation', 'jnews-pay-writer' ); ?></h2><span class="close fa fa-times"></span>
			</div>
			<div class="jpwt-payout-overlay-content">
				<h3 class="title"><?php esc_html_e( 'Payout Detail', 'jnews-pay-writer' ); ?></h3>
				<ul>
					<li> <?php esc_html_e( 'Recipient : ', 'jnews-pay-writer' ); ?><span id="recipient"><span> </li>
					<li> <?php esc_html_e( 'Address : ', 'jnews-pay-writer' ); ?><span id="paypal-account"><span></li>
					<li> <?php esc_html_e( 'Total : ', 'jnews-pay-writer' ); ?><span id="total-payout"><span> <?php echo JNews_Pay_Writer()->options['payment']['payment_currency']; ?> </li>
				</ul>
			</div>
			<div class="jpwt-payout-overlay-menu">
				<input type="button" class="jpwt-payout-btn jpwt-payout-pay button-primary" value="<?php esc_attr_e( 'Pay', 'jnews-pay-writer' ); ?>"><input type="button" class="jpwt-payout-btn jpwt-payout-cancel button-secondary" value="<?php esc_attr_e( 'Cancel', 'jnews-pay-writer' ); ?>">
			</div>
		</div>
		<div class="jpwt-payout-overlay result card">
			<div class="jpwt-payout-overlay loading card">
				<span><i class="fa fa-circle-o-notch fa-spin" ></i></span>
			</div>
			<div class="jpwt-payout-overlay-header">
				<span class="dashicons dashicons-warning"></span><h2 class="title"><?php esc_html_e( 'Payout Result', 'jnews-pay-writer' ); ?></h2><span class="close fa fa-times"></span>
			</div>
			<div class="jpwt-payout-overlay-content">
				<div class="jpwt-result-success">
					<div id="jpwt-single-payout-result">
						<ul id="payout-result-content">
							<div class="jpwt-accordion-btn" >
								<div class="accordion-btn-item-wrapper" >
									<li class="content-item receiver"><span class="payout-receiver-label"><?php esc_html_e( 'Address: ', 'jnews_pay_writer' ); ?></span><span class="payout-receiver"></span></li>
									<li class="content-item status"><span class="transaction_status_icon"></span><span class="payout-transaction_status"></span><span class="accordion-dropdown"></span></li>
								</div>
							</div>
							<div class="jpwt-accordion-panel" >
								<div class="accordion-panel-item-wrapper" >
									<li class="content-item"><?php esc_html_e( 'Amount: ', 'jnews_pay_writer' ); ?><span class="payout-amount"></span><span class="payout-amount-currency"></span></li>
									<li class="content-item"><?php esc_html_e( 'Fee: ', 'jnews_pay_writer' ); ?><span class="payout-fee"></span><span class="payout-fee-currency"></span></li>
									<li class="content-item error"><?php esc_html_e( 'Error: ', 'jnews_pay_writer' ); ?><span class="payout-item_error"></span></li>
								</div>
							<div>
						</ul>
					</div>
					<div id="jpwt-bulk-payout-result">
					</div>
				</div>
				<div class="jpwt-result-error">
					<span id="payout-message"><span>
				</div>
			</div>
		</div>
	</form>
</div>
