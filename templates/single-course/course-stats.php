<?php
/**
 * The Template for displaying course stats in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/course-stats.php.
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

do_action( 'masteriyo_before_single_course_stats' );

?>
<div class="mto-single-course-stats">
	<!-- Duration -->
	<div class="duration">
		<div class="mto-single-course--mdetail mto-icon-svg">
			<?php masteriyo_get_svg( 'time', true ); ?>
			<span>
				<?php /* translators: %s: Human understanble time string */ ?>
				<?php echo esc_html( sprintf( '%s', masteriyo_minutes_to_time_length_string( $course->get_duration() ) ) ); ?>
			</span>
		</div>
	</div>

	<!-- Comment -->
	<div class="comment">
		<div class="mto-single-course--mdetail mto-icon-svg">
			<?php masteriyo_get_svg( 'comment', true ); ?>
			<span>
				<?php
					printf(
						/* translators: %d: Course comments count */
						esc_html( _nx( '%s Comment', '%s Comments', $comments_count, 'Comments Count', 'masteriyo' ) ),
						esc_html( number_format_i18n( $comments_count ) )
					);
					?>
			</span>
		</div>
	</div>

	<!-- Student -->
	<div class="student">
		<div class="mto-single-course--mdetail mto-icon-svg">
			<?php masteriyo_get_svg( 'group', true ); ?>
			<span>
			<?php
				printf(
					/* translators: %d: Enrolled  students count */
					esc_html( _nx( '%s Student', '%s Students', $enrolled_users_count, 'Enrolled Students Count', 'masteriyo' ) ),
					esc_html( number_format_i18n( $enrolled_users_count ) )
				);
				?>
			</span>
		</div>
	</div>

	<!-- Difficulty -->
	<?php if ( $course->get_difficulty() ) : ?>
	<div class="difficulty">
		<div class="mto-single-course--mdetail mto-icon-svg">
			<?php masteriyo_get_svg( 'level', true ); ?>
			<span>
				<?php echo esc_html( $course->get_difficulty()['name'] ); ?>
			</span>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php

do_action( 'masteriyo_after_single_course_stats' );
