<?php
/**
 * Single course page's main content area.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * masteriyo_before_single_course_main_content hook.
 */
do_action( 'masteriyo_before_single_course_main_content' );

?>

<?php masteriyo_get_template( 'single-course/title.php' ); ?>

<?php masteriyo_get_template( 'single-course/featured-image.php' ); ?>

<div class="mto-tabs mto-px-4 md:mto-px-0">
	<?php masteriyo_get_template( 'single-course/tab-handles.php' ); ?>

	<div id="tab1Content" class="tab-content course-overview mto-block mto-break-words">
		<?php masteriyo_get_template( 'single-course/tab-content-overview.php' ); ?>
	</div>

	<div id="tab2Content" class="tab-content course-curriculum mto-hidden">
		<?php masteriyo_get_template( 'single-course/tab-content-curriculum.php' ); ?>
	</div>

	<div id="tab3Content" class="tab-content faq mto-hidden">
		<?php do_action( 'masteriyo_single_course_faqs_content' ); ?>
	</div>

	<div id="tab4Content" class="mto-hidden">
		<?php masteriyo_get_template( 'single-course/tab-content-reviews.php' ); ?>
	</div>
</div>

<?php

/**
 * masteriyo_after_single_course_main_content hook.
 */
do_action( 'masteriyo_after_single_course_main_content' );
