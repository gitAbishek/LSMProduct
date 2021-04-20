<?php
/**
 * User password reset email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


do_action( 'masteriyo_email_header', $email_heading, $email );

?>
<div class="email-template--header">
	<h2 class="email-template--title"><?php esc_html_e( 'Massteriyo Password Reset', 'masteriyo' ); ?></h2>
</div>
<?php /* translators: %s: Username */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'masteriyo' ), esc_html( $user->get_username() ) ); ?></p>
<?php /* translators: %s: Site name */ ?>
<p class="email-template--info"><?php printf( esc_html__( 'Seems like you forgot your password on %s.', 'masteriyo' ), esc_html( $blogname ) ); ?></p>
<p class="email-template--info"><?php esc_html_e( 'If you didn\'t make this request, just ignore this email.', 'masteriyo' ); ?></p>
<p class="email-template--info"><?php esc_html_e( 'If you did, then you can use the following button to reset your password:', 'masteriyo' ); ?></p>
<a class="email-template--button" href="<?php echo esc_url( masteriyo_get_password_reset_link( $reset_key, $user->get_id() ) ); ?>">
	<?php esc_html_e( 'Reset your password', 'masteriyo' ); ?>
</a>
<p class="email-template--info"><?php esc_html_e( 'If you don’t use this link within 2 hours, it will expire.', 'masteriyo' ); ?></p>
<p class="email-template--footer">
	<?php esc_html_e( 'Thanks,', 'masteriyo' ); ?><br/>
	<?php esc_html_e( 'The Masteriyo Team', 'masteriyo' ); ?>
</p>
<?php

/**
 * Show user-defined additional content.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'masteriyo_email_footer', $email );
