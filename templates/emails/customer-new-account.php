<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 *
 * @since 0.1.0
 */

defined( 'ABSPATH' ) || exit;

$name = $user->get_first_name();

if ( empty( $name ) ) {
	$name = $user->get_username();
}

do_action( 'masteriyo_email_header', $email_heading, $email ); ?>

<p class="email-template--info">
	<?php /* translators: %s: Customer username */ ?>
	<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $name ) ); ?>
</p>

<p class="email-template--info">
	<?php
		/* translators: %1$s: Site title, %2$s: Username, %3$s: account link */
		printf( esc_html__( 'Thanks for creating an account on %1$s. You can access your account area and view your courses, account details, and more from %2$s', 'masteriyo' ), make_clickable( esc_url( get_home_url() ) ), make_clickable( esc_url( masteriyo_get_page_permalink( 'account' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</p>

<?php

do_action( 'masteriyo_email_footer', $email );
