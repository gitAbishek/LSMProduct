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

do_action( 'masteriyo_email_header', $email_heading, $email ); ?>

<p class="email-template--info">
	<?php /* translators: %s: Customer username */ ?>
	<?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $user->get_username() ) ); ?>
</p>
<p class="email-template--info">
	<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
	<?php printf( esc_html__( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'masteriyo' ), esc_html( $blogname ), '<strong>' . esc_html( $user->get_username() ) . '</strong>', make_clickable( esc_url( masteriyo_get_page_permalink( 'myaccount' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</p>
<p class="email-template--info">
	<?php /* translators: %s: Auto generated password */ ?>
	<?php printf( esc_html__( 'Your password is: %s', 'masteriyo' ), '<strong>' . esc_html( $user->get_password() ) . '</strong>' ); ?>
</p>

<?php

do_action( 'masteriyo_email_footer', $email );
