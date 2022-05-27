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
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Fires before rendering curriculum section in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_single_course_curriculum' );

?>

<div class="tab-content course-curriculum masteriyo-hidden">
	<div class="masteriyo-stab--tcurriculum">
		<div class="masteriyo-stab--shortinfo">
			<div class="title-container">
				<h3 class="title"><?php esc_html_e( 'Curriculum', 'masteriyo' ); ?></h3>
				<ul class="masteriyo-shortinfo-wrap">
					<li class="masteriyo-list-none">
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
				<span class="masteriyo-link-primary masteriyo-expand-collape-all"><?php esc_html_e( 'Expand All', 'masteriyo' ); ?></span>
			<?php endif; ?>
		</div>

		<?php

		foreach ( $course_structure as $index => $section ) :
			?>
			<div class="masteriyo-stab--citems <?php echo esc_attr( 0 === $index ? 'active' : '' ); ?>">
				<div class="masteriyo-cheader">
					<h5 class="masteriyo-ctitle"><?php echo esc_html( $section['name'] ); ?></h5>

					<div class="masteriyo-ltc masteriyo-flex-ycenter">
						<span class="masteriyo-clessons">
							<?php
							printf(
								/* translators: %d: Section lessons count */
								esc_html( _nx( '%s Lesson', '%s Lessons', $section['lessons_count'], 'Lessons Count', 'masteriyo' ) ),
								esc_html( number_format_i18n( $section['lessons_count'] ) )
							);
							?>
						</span>
						<span class="masteriyo-cquizzes">
							<?php
							printf(
								/* translators: %d: Section quizzes count */
								esc_html( _nx( '%s Quiz', '%s Quizzes', $section['quiz_count'], 'Quizzes Count', 'masteriyo' ) ),
								esc_html( number_format_i18n( $section['quiz_count'] ) )
							);
							?>
						</span>

						<span class="masteriyo-cplus masteriyo-icon-svg">
							<?php masteriyo_get_svg( 'plus', true ); ?>
						</span>
						<span class="masteriyo-cminus masteriyo-icon-svg">
							<?php masteriyo_get_svg( 'minus', true ); ?>
						</span>
					</div>
				</div>

				<div class="masteriyo-cbody">
					<ol class="masteriyo-lesson-list">
						<?php foreach ( $section['contents'] as $content ) : ?>
							<li>
								<div class="masteriyo-lesson-list__content">
									<span class="masteriyo-lesson-list__content-item">
										<span class="masteriyo-lesson-icon">
											<?php if ( 'lesson' === $content['type'] && $content['has_video'] ) : ?>
												<?php masteriyo_get_svg( 'play', true ); ?>
											<?php elseif ( 'lesson' === $content['type'] && ! $content['has_video'] ) : ?>
												<?php masteriyo_get_svg( 'left-align', true ); ?>
											<?php elseif ( 'quiz' === $content['type'] ) : ?>
												<?php masteriyo_get_svg( 'timer', true ); ?>
											<?php endif; ?>
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
 * Fires after rendering curriculum section in single course page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_single_course_curriculum' );
