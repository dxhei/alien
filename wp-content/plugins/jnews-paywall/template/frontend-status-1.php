<?php

/**
 * JNews Paywall - Frontend Account Subscription Status Template
 */
$subscribe_status  = get_user_option( 'jpw_subscribe_status', get_current_user_id() );
$subscription_type = get_user_option( 'jpw_subs_type', get_current_user_id() );
$subscription_id   = get_user_option( 'jpw_' . $subscription_type . '_subs_id', get_current_user_id() );
$date_format       = get_option( 'date_format' );
$expired           = get_user_option( 'jpw_expired_date', get_current_user_id() ) ? get_user_option( 'jpw_expired_date', get_current_user_id() ) : Date( $date_format );
$remaining         = date_diff( new DateTime(), new DateTime( $expired ) );
$current_date      = new DateTime();
$expired_date      = new DateTime( $expired );

if ( $subscribe_status && 'ACTIVE' === $subscribe_status && $current_date <= $expired_date ) {
	$mystatus = '<div class="jpw_leftbox">
						<span><strong>' . esc_html__( 'Subscription ID', 'jnews-paywall' ) . ' : </strong>' . $subscription_id . '</span>
                        <span><strong>' . esc_html__( 'Subscription Status', 'jnews-paywall' ) . ' : </strong>' . esc_html__( 'ACTIVE', 'jnews-paywall' ) . '</span>
                        <span><strong>' . esc_html__( 'Remaining Time', 'jnews-paywall' ) . ' : </strong>' . $remaining->format( '%a ' . esc_html__( 'days', 'jnews-paywall' ) . ' %h ' . esc_html__( 'hours', 'jnews-paywall' ) ) . '</span>
                        <span><strong>' . esc_html__( 'Next Payment Due', 'jnews-paywall' ) . ' : </strong>' . date_i18n( $date_format, strtotime( $expired ) ) . '</span>
                        <span><strong>' . esc_html__( 'Payment Type', 'jnews-paywall' ) . ' : </strong>' . ucwords( $subscription_type ) . '</span>
                    </div>
                    <div class="jpw_rightbox">
                        <a class="subscription" href>' . esc_html__( 'Cancel Subscription', 'jnews-paywall' ) . '</a>
                    </div>';
} else {
	$mystatus = '<span>' . esc_html__( 'You are not subscribed', 'jnews-paywall' ) . '</span>';
}

$output = '<div class="jpw_manage_status">
                <div class="jpw_boxed">
                    ' . $mystatus . '
                </div>
            </div>';

echo $output;
