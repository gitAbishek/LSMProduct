<?php
/**
 * Tab content - Curriculum.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

global $course;

$sections = masteriyo_get_sections(array(
	'order' => 'asc',
	'order_by' => 'menu_order',
	'parent_id' => $course->get_id(),
));
$lessons = masteriyo_get_lessons(array(
	'order' => 'asc',
	'order_by' => 'menu_order',
	'course_id' => $course->get_id(),
));

$lessons_dictionary = array();

foreach ( $lessons as $lesson ) {
	$section_id = $lesson->get_parent_id();

	if ( ! isset( $lessons_dictionary[ $section_id ] ) ) {
		$lessons_dictionary[ $section_id ] = array();
	}

	$lessons_dictionary[ $section_id ][] = $lesson;
}

$get_lessons = function ( $section ) use( $lessons_dictionary ) {
	$section_id = $section->get_id();

	if ( isset( $lessons_dictionary[ $section_id ] ) ) {
		return $lessons_dictionary[ $section_id ];
	}
	return array();
};

/**
 * masteriyo_before_single_course_curriculum_content hook.
 */
do_action('masteriyo_before_single_course_curriculum_content');

?>

<div class="curr-accordion mto-relative">
	<div class="curr-details mto-flex mto-justify-between">
		<ul class="mto-flex mto-flex-row mto-list-disc mto-space-x-8 mto-mb-8">
			<li class="mto-list-none"><?php echo count( $sections ); ?> section(s)</li>
			<li>200 Lectures</li>
			<li> 20h 12m total length</li>
		</ul>
		<span class="curr-expand-collape-all mto-cursor-pointer mto-text-primary mto-text-sm  mto-font-bold mto-uppercase">Expand all lessions</span>
	</div>

	<?php foreach ( $sections as $section ) { ?>
		<div class="curr-accordion-item">
			<div class="curr-accordion-item-header">
				<div class="mto-flex mto-justify-between">
					<h3 class="mto-font-semibold mto-text-base"><?php echo $section->get_name(); ?></h3>
					<div class="mto-flex mto-items-center mto-space-x-3">
						<span><?php echo count( $get_lessons( $section ) ); ?> lessions</span>
						<span>20min</span>
						<span class="expand-accordion mto-block">
							<svg class="mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z" />
							</svg>
						</span>
						<span class="collapse-accordion mto-hidden">
							<svg class="mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M5 11h14v2H5z" />
							</svg>
						</span>

					</div>
				</div>
			</div>
			<div class="curr-accordion-item-body">
				<div class="curr-accordion-item-body-content">
					<ol class="mto-rounded-b mto-list-decimal">
						<?php foreach ( $get_lessons( $section ) as $lesson ) { ?>
							<li>
								<div class="mto-flex mto-justify-between mto-items-center mto-border mto-border-t-0 mto-border-gray-300 mto-p-4">
									<div><a href="#"><?php echo $lesson->get_name(); ?></a></div>
									<div class="mto-flex mto-items-center mto-space-x-8">
										<span class="mto-flex mto-items-center mto-space-x-1">
											<svg class="mto-inline-block mto-fill-current mto-text-gray-800 mto-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
												<path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z" />
												<path d="M13 7h-2v6h6v-2h-4z" />
											</svg>
											<time class="mto-inline-block">10 hours</time>
										</span>
										<a href="#" class="btn">preview</a>
									</div>
								</div>
							</li>
						<?php } ?>
					</ol>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

<?php

/**
 * masteriyo_after_single_course_curriculum_content hook.
 */
do_action('masteriyo_after_single_course_curriculum_content');
