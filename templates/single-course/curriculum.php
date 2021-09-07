<?php
/**
 * The Template for displaying course curriculum in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/curriculum.php.
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

/**
 * masteriyo_before_single_course_curriculum hook.
 */
do_action( 'masteriyo_before_single_course_curriculum' );

?>

<div class="tab-content course-curriculum masteriyo-hidden">
	<div class="mto-stab--tcurriculum">
		<div class="mto-stab--shortinfo">
			<div class="title-container">
				<h3 class="title"><?php esc_html_e( 'Curriculum', 'masteriyo' ); ?></h3>
				<ul class="mto-shortinfo-wrap">
					<li class="mto-list-none">
					<?php
						printf(
							/* translators: %d: Course sections count */
							esc_html( _nx( '%s Section', '%s Sections', $sections_count, 'Sections Count', 'masteriyo' ) ),
							esc_html( number_format_i18n( $sections_count ) )
						);
						?>
					</li>
					<li>
					<?php
						printf(
							/* translators: %d: Course lessons count */
							esc_html( _nx( '%s Lesson', '%s Lessons', $lessons_count, 'Lessons Count', 'masteriyo' ) ),
							esc_html( number_format_i18n( $lessons_count ) )
						);
						?>
					</li>
					<li>
					<?php
						printf(
							/* translators: %d: Course quiz count */
							esc_html( _nx( '%s Quiz', '%s Quizzes', $quiz_count, 'Quizzes Count', 'masteriyo' ) ),
							esc_html( number_format_i18n( $quiz_count ) )
						);
						?>
					</li>
					<li>
					<?php
						echo esc_html(
							sprintf(
								/* translators: %s: Lecture hours */
								__( '%s Duration', 'masteriyo' ),
								masteriyo_minutes_to_time_length_string( $course->get_duration() )
							)
						);
						?>
					</li>
				</ul>
			</div>

			<?php if ( $sections_count > 0 ) : ?>
				<span class="mto-link-primary mto-expand-collape-all"><?php esc_html_e( 'Expand All', 'masteriyo' ); ?></span>
			<?php endif; ?>
		</div>

		<?php foreach ( $course_structure as $section ) : ?>
			<div class="mto-stab--citems">
				<div class="mto-cheader">
					<h5 class="mto-ctitle"><?php echo esc_html( $section['name'] ); ?></h5>

					<div class="mto-ltc mto-flex-ycenter">
						<span class="mto-clessons">
							<?php
							printf(
								/* translators: %d: Section lessons count */
								esc_html( _nx( '%s Lesson', '%s Lessons', $section['lessons_count'], 'Lessons Count', 'masteriyo' ) ),
								esc_html( number_format_i18n( $section['lessons_count'] ) )
							);
							?>
						</span>
						<span class="mto-cquizzes">
							<?php
							printf(
								/* translators: %d: Section quizzes count */
								esc_html( _nx( '%s Quiz', '%s Quizzes', $section['quiz_count'], 'Quizzes Count', 'masteriyo' ) ),
								esc_html( number_format_i18n( $section['quiz_count'] ) )
							);
							?>
						</span>

						<span class="mto-cplus mto-icon-svg">
							<?php masteriyo_get_svg( 'plus-icon', true ); ?>
						</span>
						<span class="mto-cminus mto-icon-svg">
							<?php masteriyo_get_svg( 'minus-icon', true ); ?>
						</span>
					</div>
				</div>

				<div class="mto-cbody">
					<ol class="mto-lesson-list">
						<?php foreach ( $section['contents'] as $content ) : ?>
							<li>
								<div class="mto-lesson-list__content">
									<span class="mto-lesson-list__content-item">
										<span class="mto-lesson-icon">
											<?php masteriyo_get_svg( 'play', true ); ?>
										</span>
										<?php echo esc_html( $content['name'] ); ?>
								</span>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>

			</div>
		<?php endforeach; ?>
	</div>
</div>

<?php

/**
 * masteriyo_after_single_course_curriculum hook.
 */
do_action( 'masteriyo_after_single_course_curriculum' );
