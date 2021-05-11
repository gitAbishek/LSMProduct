<?php
/**
 * Tab content - Curriculum.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

[$sections, $lessons, $dictionary] = masteriyo_make_section_to_lessons_dictionary( $GLOBALS['course'] );

/**
 * masteriyo_before_single_course_curriculum_content hook.
 */
do_action('masteriyo_before_single_course_curriculum_content');

?>

<div class="mto-stab--tcurriculum">
	<div class="mto-stab--shortinfo mto-flex mto-flex--space-between">
		<ul class="mto-shortinfo-wrap">
			<li class="mto-list-none"><?php echo count( $sections ); ?> section(s)</li>
			<li><?php echo count( $lessons ) ?> Lecture(s)</li>
			<li><?php echo masteriyo_get_lecture_hours( $GLOBALS['course'] ) ?> total length</li>
		</ul>

		<?php if ( count( $sections ) > 0 ): ?>
		<span id="mto-expand-collape-all" class="mto-link-primary mto-expand-collape-all">Expand All</span>
		<?php endif; ?>
	</div>

	<?php foreach ( $sections as $section ) { ?>
		<div class="mto-stab--citems">
			<div class="mto-cheader">
				<h5 class="mto-ctitle"><?php echo esc_html( $section->get_name() ); ?></h5>
				
				<div class="mto-ltc">
					<span class="mto-clessons"><?php echo count( $dictionary[$section->get_id()] ); ?> lessons</span>
					
					<span class="mto-csection"><?php echo masteriyo_get_lecture_hours_of_section( $section ) ?></span>
					
					<span class="mto-cplus mto-icon-svg">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z" />
						</svg>
					</span>

					<span class="mto-cminus mto-hidden mto-icon-svg">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
							<path d="M5 11h14v2H5z" />
						</svg>
					</span>
				</div>
			</div>

			<div class="mto-cbody">
					<ol class="mto-lesson-list">
						<?php foreach ( $dictionary[$section->get_id()] as $lesson ) { ?>
							<li>
								<div class="mto-flex mto-flex--space-between">
										<a href="#"><?php echo esc_html( $lesson->get_name() ); ?></a>

										<div class="mto-time-btn">
											<span class="mto-flex mto-flex-ycenter mto-time">
												<span class="mto-icon-svg">
													<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
														<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
														<path d="M13 7h-2v6h6v-2h-4z" />
													</svg>
												</span>
												<time class="mto-inline-block">
													<?php echo masteriyo_minutes_to_time_length_string( $lesson->get_video_playback_time() ); ?>
												</time>
											</span>
											<a href="#" class="mto-lesson-list--btn mto-btn mto-btn-primary"><?php echo esc_html__( 'preview', 'masteriyo' ); ?></a>
										</div>
									</div>
							</li>
						<?php } ?>
					</ol>
			</div>
		</div>
	<?php } ?>
</div>

<?php

/**
 * masteriyo_after_single_course_curriculum_content hook.
 */
do_action('masteriyo_after_single_course_curriculum_content');
