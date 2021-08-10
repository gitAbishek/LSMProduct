<?php
/**
 * The Template for displaying course review form in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/course-review-form.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

do_action( 'masteriyo_before_single_course_review_form' );

?>
<?php if ( is_user_logged_in() ) : ?>
	<div class="mto-submit-review-form-container plr-32">
		<h3 class="mto-maintitle mto-form-title"><?php esc_html_e( 'Create a new review', 'masteriyo' ); ?></h3>
		<form method="POST" class="mto-submit-review-form">
			<input type="hidden" name="id" value="">
			<input type="hidden" name="course_id" value="<?php echo esc_attr( $course->get_id() ); ?>">
			<input type="hidden" name="parent" value="0">
			<div class="mto-title">
				<label class="mto-label"><?php esc_html_e( 'Title', 'masteriyo' ); ?></label>
				<input type="text" name="title" class="mto-input" />
			</div>
			<div class="mto-rating mt-2">
				<label class="mto-label"><?php esc_html_e( 'Rating', 'masteriyo' ); ?></label>
				<input type="hidden" name="rating" value="0" />
				<div class="mto-stab-rs boxshadow-none ">
					<span class="mto-icon-svg mto-flex mto-rstar">
						<?php masteriyo_render_stars( 0, 'mto-rating-input-icon' ); ?>
					</span>
				</div>
			</div>
			<div class="mto-message mt-2">
				<label class="mto-label"><?php esc_html_e( 'Content', 'masteriyo' ); ?></label>
				<textarea type="text" name="content" class="mto-input" required column="10"></textarea>
			</div>
			<div>
				<button type="submit" name="masteriyo-submit-review" value="yes" class="mto-btn-primary">
					<?php esc_html_e( 'Submit', 'masteriyo' ); ?>
				</button>
			</div>
			<?php wp_nonce_field( 'masteriyo-submit-review' ); ?>
		</form>
	</div>
<?php else : ?>
	<div class="mto-login-msg mto-submit-review-form-container plr-32 pb-2 pt-2">
		<?php
		printf(
			/* translators: %s: Achor tag html with text "logged in" */
			esc_html__( 'You must be %s to submit a review', 'masteriyo' ),
			sprintf(
				'<a href="%s" class="mto-link-primary">%s</a>',
				masteriyo_get_page_permalink( 'myaccount' ),
				__( 'logged in', 'masteriyo' )
			)
		);
		?>
	</div>
<?php endif; ?>
<?php

do_action( 'masteriyo_after_single_course_review_form' );
