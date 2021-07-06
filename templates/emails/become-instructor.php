<?php
/**
 * Admin cancelled order email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/become-instructor.php.
 *
 * HOWEVER, on occasion masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package masteriyo\Templates\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'masteriyo_email_header', $email_heading, $email ); ?>

<p class="email-template--info">
	<?php /* translators: %s: Site name */ ?>
	<?php printf( esc_html__( 'Become an instrcutor in %s', 'masteriyo' ), esc_html( $blogname ) ); ?>
</p>
<p class="email-template--info">
	<a href="#" class="email-template--button">Click Here</a>
</p>
<?php

do_action( 'masteriyo_email_footer', $email );
