<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/new-order.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package masteriyo\Templates\Emails\HTML
 */

defined( 'ABSPATH' ) || exit;

do_action( 'masteriyo_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer billing full name */ ?>
<p><?php printf( esc_html__( 'You’ve received the following order from %s:', 'masteriyo' ), $order->get_formatted_billing_full_name() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php

do_action( 'masteriyo_email_order_details', $order, $email );

do_action( 'masteriyo_email_order_meta', $order, $email );

do_action( 'masteriyo_email_customer_details', $order, $email );

do_action( 'masteriyo_email_footer', $email );
