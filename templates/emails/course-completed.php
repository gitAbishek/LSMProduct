<?php
/**
 * Admin cancelled order email
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/course-completed.php.
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
	<?php esc_html__( 'You completed the following course:', 'masteriyo' ); ?>
</p>

<?php if ( ! is_wp_error( $course ) ) : ?>
	<p class="email-template--info">
		<?php echo esc_html( $course->get_name() ); ?>
	</p>
<?php endif; ?>

do_action( 'masteriyo_email_footer', $email );
