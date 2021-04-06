<?php
/**
 * The Template for displaying single course.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

global $course;

/**
 * masteriyo_before_single_course_content hook.
 */
do_action( 'masteriyo_before_single_course_content' );

?>

<div id="course-<?php the_ID(); ?>" class="antialiased mto-bg-gray-50">
    <div class="mto-container mto-flex mto-flex-col md:mto-flex-row mto-my-20 mto-mx-auto">
        <div class="mto-w-full md:mto-w-9/12 md:mto-mr-4">
			<?php masteriyo_get_template( 'single-course/main-content.php' ); ?>
        </div>
        <aside class="mto-px-4 md:mto-ml-4 mto-w-full md:mto-w-3/12 mto-bg-grey-700">
			<?php masteriyo_get_template( 'single-course/sidebar-content.php' ); ?>
        </aside>
    </div>
</div>

<?php

/**
 * masteriyo_after_single_course_content hook.
 */
do_action( 'masteriyo_after_single_course_content' );

