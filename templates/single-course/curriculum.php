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

<div class="tab-content course-curriculum mto-hidden">
	<div class="mto-stab--tcurriculum">
		<div class="mto-stab--shortinfo mto-flex mto-flex--space-between mto-flex-ycenter plr-32">
			<div class="title-container">
				<h3 class="title"><?php esc_html_e( 'Curriculum', 'masteriyo' ); ?></h3>
				<ul class="mto-shortinfo-wrap">
					<li class="mto-list-none">
						<?php /* translators: %d: Course sections count */ ?>
						<?php echo esc_html( sprintf( __( '%d section(s)', 'masteriyo' ), count( $sections ) ) ); ?>
					</li>
					<li>
						<?php /* translators: %d: Course lessons count */ ?>
						<?php echo esc_html( sprintf( __( '%d Lecture(s)', 'masteriyo' ), count( $lessons ) ) ); ?>
					</li>
					<li>
						<?php /* translators: %s: Lecture hours */ ?>
						<?php echo esc_html( sprintf( __( '%s total length', 'masteriyo' ), masteriyo_get_lecture_hours( $course ) ) ); ?>
					</li>
				</ul>
			</div>

			<?php if ( count( $sections ) > 0 ) : ?>
				<span class="mto-link-primary mto-expand-collape-all"><?php esc_html_e( 'Expand All', 'masteriyo' ); ?></span>
			<?php endif; ?>
		</div>

		<?php foreach ( $sections as $section ) : ?>
			<div class="mto-stab--citems">
				<div class="mto-cheader  plr-32 pt-2 pb-2">
					<h5 class="mto-ctitle"><?php echo esc_html( $section->get_name() ); ?></h5>

					<div class="mto-ltc mto-flex-ycenter">
						<span class="mto-clessons">
							<?php /* translators: %d: Lessons count */ ?>
							<?php echo esc_html( sprintf( __( '%d lessons', 'masteriyo' ), count( $dictionary[ $section->get_id() ] ) ) ); ?>
						</span>

						<span class="mto-csection">
							<?php echo esc_html( masteriyo_get_lecture_hours_of_section( $section ) ); ?>
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
						<?php foreach ( $dictionary[ $section->get_id() ] as $lesson ) : ?>
							<li>
								<div class="mto-flex mto-flex--space-between mto-flex-ycenter plr-32">
									<span class="mto-flex mto-flex--space-between mto-flex-ycenter">
										<span class="mto-lesson-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M7 6v12l10-6z"></path></svg>
										</span>
										<?php echo esc_html( $lesson->get_name() ); ?>
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
